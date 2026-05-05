<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Sale;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = Storage::path('Dataset.csv');
        if (!file_exists($file)) {
            return;
        }

        $data = array_map('str_getcsv', file($file));
        $header = array_shift($data);

        foreach ($data as $row) {
            if (count($row) == 5) {
                Sale::create([
                    'hari' => (int) $row[0],
                    'tanggal' => (int) $row[1],
                    'kegiatan' => (int) $row[2],
                    'curah_hujan' => (float) $row[3],
                    'penjualan' => (int) $row[4],
                ]);
            }
        }
    }
}
