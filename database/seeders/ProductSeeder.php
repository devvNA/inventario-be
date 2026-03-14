<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'name');

        $products = [
            ['name' => 'Air Mineral 600ml', 'thumbnail' => 'products/air-mineral-600ml.jpg', 'about' => 'Air mineral kemasan botol 600ml untuk kebutuhan harian.', 'price' => 4000, 'category' => 'Minuman', 'is_popular' => true],
            ['name' => 'Teh Botol Melati', 'thumbnail' => 'products/teh-botol-melati.jpg', 'about' => 'Minuman teh siap saji rasa melati dengan ukuran praktis.', 'price' => 5500, 'category' => 'Minuman', 'is_popular' => true],
            ['name' => 'Kopi Susu Kaleng', 'thumbnail' => 'products/kopi-susu-kaleng.jpg', 'about' => 'Kopi susu kaleng dingin untuk etalase minuman cepat jual.', 'price' => 8000, 'category' => 'Minuman', 'is_popular' => false],
            ['name' => 'Keripik Kentang Original', 'thumbnail' => 'products/keripik-kentang-original.jpg', 'about' => 'Snack keripik kentang renyah rasa original.', 'price' => 12000, 'category' => 'Makanan Ringan', 'is_popular' => true],
            ['name' => 'Biskuit Cokelat Family Pack', 'thumbnail' => 'products/biskuit-cokelat-family-pack.jpg', 'about' => 'Biskuit cokelat ukuran keluarga untuk stok display rak.', 'price' => 18500, 'category' => 'Makanan Ringan', 'is_popular' => false],
            ['name' => 'Wafer Vanila Crispy', 'thumbnail' => 'products/wafer-vanila-crispy.jpg', 'about' => 'Wafer renyah rasa vanila untuk camilan harian.', 'price' => 9000, 'category' => 'Makanan Ringan', 'is_popular' => false],
            ['name' => 'Beras Premium 5kg', 'thumbnail' => 'products/beras-premium-5kg.jpg', 'about' => 'Beras premium 5kg untuk kebutuhan rumah tangga.', 'price' => 78000, 'category' => 'Sembako', 'is_popular' => true],
            ['name' => 'Gula Pasir 1kg', 'thumbnail' => 'products/gula-pasir-1kg.jpg', 'about' => 'Gula pasir kemasan 1kg untuk dapur dan usaha.', 'price' => 17500, 'category' => 'Sembako', 'is_popular' => true],
            ['name' => 'Minyak Goreng 1L', 'thumbnail' => 'products/minyak-goreng-1l.jpg', 'about' => 'Minyak goreng pouch 1 liter untuk kebutuhan masak harian.', 'price' => 21000, 'category' => 'Sembako', 'is_popular' => true],
            ['name' => 'Sabun Cuci Piring Lemon', 'thumbnail' => 'products/sabun-cuci-piring-lemon.jpg', 'about' => 'Sabun cuci piring aroma lemon untuk rumah dan warung.', 'price' => 11000, 'category' => 'Kebersihan', 'is_popular' => false],
            ['name' => 'Pembersih Lantai Floral', 'thumbnail' => 'products/pembersih-lantai-floral.jpg', 'about' => 'Pembersih lantai dengan aroma floral segar.', 'price' => 16000, 'category' => 'Kebersihan', 'is_popular' => false],
            ['name' => 'Tisu Gulung Soft 10 Pack', 'thumbnail' => 'products/tisu-gulung-soft-10-pack.jpg', 'about' => 'Tisu gulung lembut kemasan ekonomis untuk keluarga.', 'price' => 24500, 'category' => 'Kebersihan', 'is_popular' => true],
            ['name' => 'Shampoo Fresh 170ml', 'thumbnail' => 'products/shampoo-fresh-170ml.jpg', 'about' => 'Shampoo harian ukuran 170ml untuk kebutuhan personal care.', 'price' => 19000, 'category' => 'Perawatan Diri', 'is_popular' => false],
            ['name' => 'Sabun Mandi Herbal', 'thumbnail' => 'products/sabun-mandi-herbal.jpg', 'about' => 'Sabun mandi herbal batang dengan aroma segar.', 'price' => 6500, 'category' => 'Perawatan Diri', 'is_popular' => false],
            ['name' => 'Pasta Gigi Cool Mint', 'thumbnail' => 'products/pasta-gigi-cool-mint.jpg', 'about' => 'Pasta gigi rasa mint dingin untuk kebutuhan keluarga.', 'price' => 13500, 'category' => 'Perawatan Diri', 'is_popular' => true],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'thumbnail' => $product['thumbnail'],
                    'about' => $product['about'],
                    'price' => $product['price'],
                    'category_id' => $categories[$product['category']],
                    'is_popular' => $product['is_popular'],
                ]
            );
        }
    }
}
