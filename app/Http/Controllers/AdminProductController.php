<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $categoryId = $request->integer('category_id');
        $stockFilter = $request->string('stock')->toString(); // available|low|out

        $products = Product::query()
            ->with('category')
            ->when($q, fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                  ->orWhere('sku', 'like', "%$q%")
                  ->orWhere('barcode', 'like', "%$q%");
            }))
            ->when($categoryId, fn($qr) => $qr->where('category_id', $categoryId))
            ->when($stockFilter === 'available', fn($qr) => $qr->where('stock', '>', 0))
            ->when($stockFilter === 'low', fn($qr) => $qr->where('stock', '<', 10)->where('stock', '>', 0))
            ->when($stockFilter === 'out', fn($qr) => $qr->where('stock', '=', 0))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $product = new Product();
        $product->name = $data['name'];
        $product->sku = $data['sku'];
        $product->category_id = $data['category_id'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->barcode = $data['barcode'] ?? null;
        $product->description = $data['description'] ?? null;
        $product->is_active = (bool) ($data['is_active'] ?? false);
        $product->sold_count = 0;
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $product->fill([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'barcode' => $data['barcode'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return back()->with('status', 'Produk dihapus');
    }
}
