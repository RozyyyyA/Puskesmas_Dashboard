<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KunjunganPasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kunjungan_pasien')->insert([
            [
                'tanggal' => '2025-12-01',
                'poli' => 'Umum',
                'jumlah' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-12-02',
                'poli' => 'Umum',
                'jumlah' => 52,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-12-03',
                'poli' => 'Umum',
                'jumlah' => 38,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-12-01',
                'poli' => 'Gigi',
                'jumlah' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-12-02',
                'poli' => 'Gigi',
                'jumlah' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-12-03',
                'poli' => 'Gigi',
                'jumlah' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
