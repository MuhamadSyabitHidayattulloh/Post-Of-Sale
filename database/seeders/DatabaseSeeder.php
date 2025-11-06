<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product as ProductModel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed categories
        $categories = collect(['Elektronik', 'Fashion', 'Makanan', 'Minuman', 'Peralatan'])->map(function ($name) {
            return Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        });

        // // seed products
        // $faker = \Faker\Factory::create('id_ID');
        // foreach (range(1, 50) as $i) {
        //     $category = $categories->random();
        //     Product::updateOrCreate(
        //         ['sku' => 'SKU-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT)],
        //         [
        //             'name' => 'Produk ' . $i,
        //             'category_id' => $category->id,
        //             'barcode' => $faker->ean13(),
        //             'price' => $faker->numberBetween(10000, 500000),
        //             'stock' => $faker->numberBetween(0, 200),
        //             'is_active' => $faker->boolean(90),
        //             'description' => $faker->sentence(10),
        //             'image_url' => null,
        //             'sold_count' => $faker->numberBetween(0, 500),
        //         ]
        //     );
        // }

        // seed tiers
        $tiersData = [
            ['name' => 'Bronze', 'code' => 'bronze', 'color' => '#f97316', 'description' => 'Tier Pemula', 'min_total' => 0, 'discount_percent' => 5, 'points_multiplier' => 1, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Silver', 'code' => 'silver', 'color' => '#9ca3af', 'description' => 'Tier Menengah', 'min_total' => 1_000_000, 'discount_percent' => 10, 'points_multiplier' => 2, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Gold', 'code' => 'gold', 'color' => '#eab308', 'description' => 'Tier Premium', 'min_total' => 5_000_000, 'discount_percent' => 15, 'points_multiplier' => 3, 'is_active' => true, 'sort_order' => 3],
        ];
        $tiers = collect();
        foreach ($tiersData as $t) {
            $tiers->push(Tier::updateOrCreate(['code' => $t['code']], $t));
        }

        // seed users
        // admin
        User::updateOrCreate(
            ['email' => 'admin@kasir.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'member_tier_id' => null,
                'points' => 0,
                'phone' => '081234567890',
            ]
        );

        // // kasir users
        // foreach (range(1, 5) as $i) {
        //     User::updateOrCreate(
        //         ['email' => "kasir{$i}@kasir.com"],
        //         [
        //             'name' => $faker->name(),
        //             'password' => Hash::make('password'),
        //             'role' => 'kasir',
        //             'status' => 'active',
        //             'member_tier_id' => null,
        //             'points' => 0,
        //             'phone' => $faker->phoneNumber(),
        //         ]
        //     );
        // }

        // // member users
        // $tierIds = $tiers->pluck('id')->all();
        // foreach (range(1, 30) as $i) {
        //     $email = "member{$i}@mail.com";
        //     User::updateOrCreate(
        //         ['email' => $email],
        //         [
        //             'name' => $faker->name(),
        //             'password' => Hash::make('password'),
        //             'role' => 'member',
        //             'status' => $faker->randomElement(['active', 'inactive']),
        //             'member_tier_id' => $faker->randomElement($tierIds),
        //             'points' => $faker->numberBetween(0, 5000),
        //             'phone' => $faker->phoneNumber(),
        //             'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
        //             'updated_at' => now(),
        //         ]
        //     );
        // }

        // // seed transactions
        // $cashierIds = User::where('role', 'kasir')->pluck('id')->all();
        // $memberIds = User::where('role', 'member')->pluck('id')->all();
        // $productIds = Product::pluck('id')->all();

        // if (!empty($cashierIds) && !empty($productIds)) {
        //     foreach (range(1, 80) as $i) {
        //         $cashierId = $faker->randomElement($cashierIds);
        //         $memberId = $faker->optional(0.6)->randomElement($memberIds);

        //         $itemsCount = $faker->numberBetween(1, 5);
        //         $pickedProductIds = $faker->randomElements($productIds, $itemsCount);

        //         $subtotal = 0;
        //         $itemsPayload = [];
        //         foreach ($pickedProductIds as $pid) {
        //             $product = ProductModel::find($pid);
        //             if (!$product) continue;
        //             $qty = $faker->numberBetween(1, 3);
        //             $price = (float) $product->price;
        //             $lineTotal = $qty * $price;
        //             $subtotal += $lineTotal;
        //             $itemsPayload[] = compact('pid', 'qty', 'price', 'lineTotal', 'product');
        //         }

        //         // discount from member tier
        //         $discountPercent = 0;
        //         if ($memberId) {
        //             $member = User::find($memberId);
        //             if ($member && $member->tier) {
        //                 $discountPercent = (float) ($member->tier->discount_percent ?? 0);
        //             }
        //         }
        //         $discount = round($subtotal * ($discountPercent / 100), 2);
        //         $taxable = $subtotal - $discount;
        //         $tax = round($taxable * 0.11, 2);
        //         $total = $taxable + $tax;

        //         $trx = Transaction::create([
        //             'code' => 'TRX-' . now()->format('ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
        //             'cashier_id' => $cashierId,
        //             'member_id' => $memberId,
        //             'subtotal' => $subtotal,
        //             'discount' => $discount,
        //             'tax' => $tax,
        //             'total' => $total,
        //             'payment_method' => $faker->randomElement(['cash', 'transfer', 'qris']),
        //             'paid_amount' => $total,
        //             'change_amount' => 0,
        //             'status' => 'paid',
        //             'notes' => null,
        //             'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
        //             'updated_at' => now(),
        //         ]);

        //         foreach ($itemsPayload as $row) {
        //             TransactionItem::create([
        //                 'transaction_id' => $trx->id,
        //                 'product_id' => $row['pid'],
        //                 'quantity' => $row['qty'],
        //                 'price' => $row['price'],
        //                 'total' => $row['lineTotal'],
        //             ]);

        //             // update product stock and sold_count optimistically
        //             $p = $row['product'];
        //             $p->sold_count = ($p->sold_count ?? 0) + $row['qty'];
        //             $p->stock = max(0, (int) $p->stock - $row['qty']);
        //             $p->save();
        //         }
        //     }
        // }
    }
}
