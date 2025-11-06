<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;

class KasirPosController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $categoryId = $request->integer('category_id');

        $products = Product::query()
            ->when($q !== '', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->when($categoryId, fn($qq) => $qq->where('category_id', $categoryId))
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(48)
            ->get(['id','name','sku','price','stock','category_id']);

        $categories = \App\Models\Category::orderBy('name')->get(['id','name']);
        $members = User::where('role', 'member')->orderByDesc('id')->limit(50)->get(['id','name','member_tier_id']);
        $tiers = \App\Models\Tier::pluck('code','id');

        $role = 'kasir';
        $userName = 'Kasir';

        // Build lightweight payloads for Alpine to avoid Blade parser issues
        $productsLite = $products->map(function($p){
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float) $p->price,
                'stock' => (int) $p->stock,
                'sku' => $p->sku,
                'category_id' => $p->category_id,
            ];
        })->values();
        $categoriesLite = $categories->map(function($c){
            return [ 'id' => $c->id, 'name' => $c->name ];
        })->values();
        $membersLite = $members->map(function($m) use ($tiers){
            $tierCode = $tiers[$m->member_tier_id] ?? 'bronze';
            return [ 'id' => $m->id, 'name' => $m->name, 'tier' => $tierCode ];
        })->values();

        $posPayload = [
            'products' => $productsLite,
            'categories' => $categoriesLite,
            'members' => $membersLite,
        ];

        return view('kasir.pos', compact('products','categories','members','tiers','role','userName','posPayload'));
    }

    public function barcode(Request $request)
    {
        $code = $request->string('code')->toString();
        if ($code === '') {
            return response()->json(['success' => false, 'message' => 'Barcode kosong'], 422);
        }
        $product = Product::where('barcode', $code)->orWhere('sku', $code)->first(['id','name','sku','price','stock']);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function apiProducts(Request $request)
    {
        $q = $request->string('q')->toString();
        $categoryId = $request->integer('category_id');
        $products = Product::query()
            ->when($q !== '', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->when($categoryId, fn($qq) => $qq->where('category_id', $categoryId))
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(48)
            ->get(['id','name','sku','price','stock']);

        return response()->json(['success' => true, 'products' => $products]);
    }
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'member_id' => 'nullable|integer|exists:users,id',
            'payment_method' => 'required|string|in:cash,transfer,qris',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $payload = $validator->validated();

        return DB::transaction(function () use ($payload) {
            $itemsInput = $payload['items'];

            // lock products for update to avoid race conditions
            $productIds = collect($itemsInput)->pluck('product_id')->unique()->values();
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0.0;
            $normalizedItems = [];
            foreach ($itemsInput as $row) {
                $product = $products[$row['product_id']] ?? null;
                if (!$product) {
                    return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
                }
                $qty = (int) $row['quantity'];
                if ($product->stock < $qty) {
                    return response()->json(['success' => false, 'message' => "Stok produk {$product->name} tidak mencukupi"], 400);
                }
                $price = (float) $product->price;
                $lineTotal = $qty * $price;
                $subtotal += $lineTotal;
                $normalizedItems[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $lineTotal,
                ];
            }

            // discount from member tier
            $memberId = $payload['member_id'] ?? null;
            $discountPercent = 0.0;
            if ($memberId) {
                $member = User::find($memberId);
                if ($member && $member->tier) {
                    $discountPercent = (float) ($member->tier->discount_percent ?? 0);
                }
            }
            $discount = round($subtotal * ($discountPercent / 100), 2);
            $taxable = $subtotal - $discount;
            $tax = round($taxable * 0.11, 2);
            $total = $taxable + $tax;

            if ($payload['paid_amount'] + 1e-6 < $total && $payload['payment_method'] === 'cash') {
                return response()->json(['success' => false, 'message' => 'Pembayaran tunai kurang dari total'], 400);
            }

            $trx = Transaction::create([
                'code' => 'TRX-' . now()->format('ymdHis') . '-' . strtoupper(str()->random(4)),
                'cashier_id' => User::where('role', 'kasir')->inRandomOrder()->value('id'),
                'member_id' => $memberId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $payload['payment_method'],
                'paid_amount' => $payload['paid_amount'],
                'change_amount' => max(0, $payload['paid_amount'] - $total),
                'status' => 'paid',
            ]);

            foreach ($normalizedItems as $row) {
                TransactionItem::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $row['product']->id,
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'total' => $row['total'],
                ]);

                $p = $row['product'];
                $p->decrement('stock', $row['quantity']);
                $p->increment('sold_count', $row['quantity']);
            }

            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $trx->id,
                    'code' => $trx->code,
                    'total' => $trx->total,
                    'tax' => $trx->tax,
                    'discount' => $trx->discount,
                    'paid_amount' => $trx->paid_amount,
                    'change_amount' => $trx->change_amount,
                ]
            ], 201);
        });
    }

    public function members(Request $request)
    {
        $q = $request->string('q')->toString();

        $members = User::query()
            ->where('role', 'member')
            ->with('tier')
            ->when($q !== '', fn($qq) => $qq->where(function($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $tiers = \App\Models\Tier::orderBy('id')->get();

        return view('kasir.members', compact('members', 'tiers'));
    }

    public function storeMember(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'member_tier_id' => 'required|exists:tiers,id',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->phone = $data['phone'] ?? null;
        $user->address = $data['address'] ?? null;
        $user->role = 'member';
        $user->member_tier_id = $data['member_tier_id'];
        $user->points = 0;
        $user->save();

        return redirect()->route('kasir.members')->with('status', 'Member berhasil ditambahkan!');
    }
}
