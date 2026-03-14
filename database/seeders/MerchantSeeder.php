<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keepers = User::role('keeper')->orderBy('id')->get();

        if ($keepers->isEmpty()) {
            return;
        }

        $merchants = [
            [
                'name' => 'Toko Maju Jaya',
                'address' => 'Jl. Sukajadi No. 101, Bandung',
                'photo' => 'merchants/toko-maju-jaya.jpg',
                'phone' => '082200000001',
                'keeper_index' => 0,
            ],
            [
                'name' => 'Warung Berkah Abadi',
                'address' => 'Jl. Cihanjuang No. 15, Cimahi',
                'photo' => 'merchants/warung-berkah-abadi.jpg',
                'phone' => '082200000002',
                'keeper_index' => 0,
            ],
            [
                'name' => 'Minimarket Sinar Niaga',
                'address' => 'Jl. Prabu Gajah Agung No. 7, Sumedang',
                'photo' => 'merchants/minimarket-sinar-niaga.jpg',
                'phone' => '082200000003',
                'keeper_index' => 0,
            ],
        ];

        foreach ($merchants as $merchant) {
            $keeper = $keepers[$merchant['keeper_index'] % $keepers->count()];

            Merchant::updateOrCreate(
                ['name' => $merchant['name']],
                [
                    'address' => $merchant['address'],
                    'photo' => $merchant['photo'],
                    'phone' => $merchant['phone'],
                    'keeper_id' => $keeper->id,
                ]
            );
        }
    }
}
