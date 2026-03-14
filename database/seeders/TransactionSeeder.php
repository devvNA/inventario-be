<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\MerchantProduct;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = Merchant::query()->orderBy('id')->get();

        if ($merchants->isEmpty()) {
            return;
        }

        foreach ($merchants as $merchantIndex => $merchant) {
            $merchantProducts = MerchantProduct::query()
                ->with('product')
                ->where('merchant_id', $merchant->id)
                ->orderBy('product_id')
                ->get();

            if ($merchantProducts->count() < 2) {
                continue;
            }

            for ($transactionNumber = 1; $transactionNumber <= 3; $transactionNumber++) {
                DB::transaction(function () use ($merchant, $merchantProducts, $merchantIndex, $transactionNumber) {
                    $selectedProducts = $merchantProducts->slice(($transactionNumber - 1) % 2, 3)->values();
                    $subTotal = 0;
                    $lineItems = [];

                    foreach ($selectedProducts as $lineIndex => $merchantProduct) {
                        $quantity = min(1 + (($merchantIndex + $transactionNumber + $lineIndex) % 3), max(1, $merchantProduct->stock));
                        $price = $merchantProduct->product->price;
                        $lineSubTotal = $quantity * $price;
                        $subTotal += $lineSubTotal;

                        $lineItems[] = [
                            'product_id' => $merchantProduct->product_id,
                            'quantity' => $quantity,
                            'price' => $price,
                            'sub_total' => $lineSubTotal,
                        ];
                    }

                    $taxTotal = (int) round($subTotal * 0.1);
                    $grandTotal = $subTotal + $taxTotal;

                    $transaction = Transaction::updateOrCreate(
                        [
                            'merchant_id' => $merchant->id,
                            'name' => 'Pelanggan ' . $merchant->id . '-' . $transactionNumber,
                            'phone' => '083300000' . str_pad((string) (($merchantIndex * 3) + $transactionNumber), 3, '0', STR_PAD_LEFT),
                        ],
                        [
                            'sub_total' => $subTotal,
                            'tax_total' => $taxTotal,
                            'grand_total' => $grandTotal,
                        ]
                    );

                    foreach ($lineItems as $lineItem) {
                        TransactionProduct::updateOrCreate(
                            [
                                'transaction_id' => $transaction->id,
                                'product_id' => $lineItem['product_id'],
                            ],
                            [
                                'quantity' => $lineItem['quantity'],
                                'price' => $lineItem['price'],
                                'sub_total' => $lineItem['sub_total'],
                            ]
                        );
                    }
                });
            }
        }
    }
}
