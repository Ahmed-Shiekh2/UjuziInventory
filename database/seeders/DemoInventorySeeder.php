<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoInventorySeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $user = User::oldest('id')->first();

            if (! $user) {
                throw new RuntimeException('Demo inventory requires at least one registered user.');
            }

            $definitions = [
                [
                    'name' => 'Rice',
                    'sku' => 'UJM-RICE-001',
                    'description' => 'Premium quality rice suitable for everyday household meals.',
                    'price' => 8500,
                    'opening_quantity' => 50,
                    'image_path' => 'images/rice.png',
                    'sale_quantity' => 5,
                    'payment_method' => 'mtn_mobile_money',
                    'phone_number' => '0772123456',
                    'transaction_reference' => 'MTN-UJM-001',
                ],
                [
                    'name' => 'Bread',
                    'sku' => 'UJM-BREAD-002',
                    'description' => 'Fresh packaged bread for daily household consumption.',
                    'price' => 5000,
                    'opening_quantity' => 40,
                    'image_path' => 'images/bread.png',
                    'sale_quantity' => 4,
                    'payment_method' => 'cash',
                    'phone_number' => null,
                    'transaction_reference' => null,
                ],
                [
                    'name' => 'Cooking Oil',
                    'sku' => 'UJM-OIL-003',
                    'description' => 'Refined cooking oil for home and commercial food preparation.',
                    'price' => 12000,
                    'opening_quantity' => 35,
                    'image_path' => 'images/oil.png',
                    'sale_quantity' => 3,
                    'payment_method' => 'airtel_money',
                    'phone_number' => '0752123456',
                    'transaction_reference' => 'AIR-UJM-002',
                ],
                [
                    'name' => 'Sugar',
                    'sku' => 'UJM-SUGAR-004',
                    'description' => 'Fine white sugar for beverages, baking and household use.',
                    'price' => 6500,
                    'opening_quantity' => 45,
                    'image_path' => 'images/sugar.png',
                    'sale_quantity' => 6,
                    'payment_method' => 'mtn_mobile_money',
                    'phone_number' => '0773123456',
                    'transaction_reference' => 'MTN-UJM-003',
                ],
                [
                    'name' => 'Wheat Flour',
                    'sku' => 'UJM-FLOUR-005',
                    'description' => 'Quality wheat flour suitable for baking and general cooking.',
                    'price' => 7000,
                    'opening_quantity' => 55,
                    'image_path' => 'images/flour.png',
                    'sale_quantity' => 7,
                    'payment_method' => 'cash',
                    'phone_number' => null,
                    'transaction_reference' => null,
                ],
            ];

            foreach ($definitions as $definition) {
                $product = Product::where('sku', $definition['sku'])->first();
                $productData = [
                    'name' => $definition['name'],
                    'description' => $definition['description'],
                    'category' => 'Food',
                    'image_path' => $definition['image_path'],
                    'price' => $definition['price'],
                ];

                if ($product) {
                    $product->update($productData);
                } else {
                    $product = Product::create($productData + [
                        'sku' => $definition['sku'],
                        'quantity' => $definition['opening_quantity'],
                    ]);
                }

                $product->stockMovements()->firstOrCreate(
                    ['type' => 'stock_in', 'note' => 'Opening stock'],
                    ['quantity' => $definition['opening_quantity']]
                );
            }

            foreach ($definitions as $index => $definition) {
                $product = Product::where('sku', $definition['sku'])->lockForUpdate()->firstOrFail();
                $existingSale = Sale::whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                    ->where('payment_method', $definition['payment_method'])
                    ->when(
                        $definition['transaction_reference'],
                        function ($query, $reference) {
                            $query->where('transaction_reference', $reference);
                        },
                        function ($query) {
                            $query->whereNull('transaction_reference');
                        }
                    )
                    ->first();

                if ($existingSale) {
                    continue;
                }

                if ($product->quantity < $definition['sale_quantity']) {
                    throw new RuntimeException('Insufficient stock while seeding ' . $product->name . '.');
                }

                $subtotal = round((float) $product->price * $definition['sale_quantity'], 2);
                $saleData = [
                    'user_id' => $user->id,
                    'total_amount' => $subtotal,
                    'payment_method' => $definition['payment_method'],
                    'phone_number' => $definition['phone_number'],
                    'transaction_reference' => $definition['transaction_reference'],
                    'payment_status' => 'successful',
                ];

                $sale = $index === 0 ? Sale::doesntHave('items')->oldest('id')->first() : null;

                if ($sale) {
                    $sale->update($saleData);
                } else {
                    $sale = Sale::create($saleData);
                }

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $definition['sale_quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->quantity -= $definition['sale_quantity'];
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'stock_out',
                    'quantity' => $definition['sale_quantity'],
                    'note' => 'Sale #' . $sale->id,
                ]);
            }
        });
    }
}
