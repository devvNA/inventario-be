<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Database\Seeder;

class WarehouseProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::query()->orderBy('id')->get();
        $products = Product::query()->orderBy('id')->get();

        if ($warehouses->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($warehouses as $warehouseIndex => $warehouse) {
            foreach ($products as $productIndex => $product) {
                $stock = 120 + (($warehouseIndex + 1) * 15) + (($productIndex + 1) * 7);

                WarehouseProduct::updateOrCreate(
                    [
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'stock' => $stock,
                    ]
                );
            }
        }
    }
}
