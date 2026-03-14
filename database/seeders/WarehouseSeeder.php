<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Gudang Pusat Bandung',
                'address' => 'Jl. Industri Utama No. 10, Bandung',
                'photo' => 'warehouses/gudang-pusat-bandung.jpg',
                'phone' => '081100000001',
            ],
            [
                'name' => 'Gudang Cabang Cimahi',
                'address' => 'Jl. Raya Cimahi No. 22, Cimahi',
                'photo' => 'warehouses/gudang-cabang-cimahi.jpg',
                'phone' => '081100000002',
            ],
            [
                'name' => 'Gudang Distribusi Sumedang',
                'address' => 'Jl. Logistik Timur No. 8, Sumedang',
                'photo' => 'warehouses/gudang-distribusi-sumedang.jpg',
                'phone' => '081100000003',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['name' => $warehouse['name']],
                [
                    'address' => $warehouse['address'],
                    'photo' => $warehouse['photo'],
                    'phone' => $warehouse['phone'],
                ]
            );
        }
    }
}
