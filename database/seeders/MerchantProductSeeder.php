<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\MerchantProduct;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Database\Seeder;

class MerchantProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = Merchant::query()->orderBy('id')->get();
        $warehouses = Warehouse::query()->orderBy('id')->get();

        if ($merchants->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        $warehouseProducts = WarehouseProduct::query()
            ->with('product')
            ->orderBy('warehouse_id')
            ->orderBy('product_id')
            ->get()
            ->groupBy('warehouse_id');

        foreach ($merchants as $merchantIndex => $merchant) {
            $warehouse = $warehouses[$merchantIndex % $warehouses->count()];
            $stocks = $warehouseProducts->get($warehouse->id);

            if (!$stocks) {
                continue;
            }

            foreach ($stocks->take(8) as $stockIndex => $warehouseProduct) {
                $merchantStock = max(8, (int) floor($warehouseProduct->stock / (4 + $stockIndex % 3)));

                MerchantProduct::updateOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'product_id' => $warehouseProduct->product_id,
                        'warehouse_id' => $warehouse->id,
                    ],
                    [
                        'stock' => min($merchantStock, $warehouseProduct->stock),
                    ]
                );
            }
        }
    }
}
