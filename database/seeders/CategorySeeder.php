<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Minuman',
                'photo' => 'categories/minuman.jpg',
                'tagline' => 'Stok minuman harian paling cepat terjual.',
            ],
            [
                'name' => 'Makanan Ringan',
                'photo' => 'categories/makanan-ringan.jpg',
                'tagline' => 'Snack favorit untuk display toko modern.',
            ],
            [
                'name' => 'Sembako',
                'photo' => 'categories/sembako.jpg',
                'tagline' => 'Kebutuhan pokok dengan perputaran stabil.',
            ],
            [
                'name' => 'Kebersihan',
                'photo' => 'categories/kebersihan.jpg',
                'tagline' => 'Produk kebersihan untuk rumah dan usaha.',
            ],
            [
                'name' => 'Perawatan Diri',
                'photo' => 'categories/perawatan-diri.jpg',
                'tagline' => 'Produk personal care untuk pelanggan harian.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'photo' => $category['photo'],
                    'tagline' => $category['tagline'],
                ]
            );
        }
    }
}
