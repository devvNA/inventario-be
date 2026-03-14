<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            WarehouseSeeder::class,
            MerchantSeeder::class,
            WarehouseProductSeeder::class,
            MerchantProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
