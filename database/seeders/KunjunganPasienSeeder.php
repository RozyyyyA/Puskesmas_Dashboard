<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KunjunganPasienSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        $polis = [
            'Umum'   => [45, 52, 38, 60, 55, 48, 62, 58, 61, 50, 57, 59, 63, 54],
            'Gigi'   => [20, 25, 18, 30, 28, 22, 35, 32, 34, 27, 29, 31, 36, 33],
            'KIA'    => [15, 18, 12, 20, 17, 14, 22, 19, 21, 16, 18, 20, 23, 19],
            'Lansia' => [10, 12, 9, 14, 11, 13, 16, 15, 17, 12, 14, 15, 18, 16],
            'TB'     => [5, 7, 6, 8, 7, 6, 9, 8, 10, 7, 8, 9, 11, 10],
            'Imunisasi' => [25, 30, 28, 35, 32, 29, 38, 36, 40, 34, 37, 39, 42, 41],
        ];

        $startDate = \Carbon\Carbon::create(2025, 12, 1);

        foreach ($polis as $poli => $jumlahs) {
            foreach ($jumlahs as $i => $jumlah) {
                $data[] = [
                    'tanggal'    => $startDate->copy()->addDays($i)->format('Y-m-d'),
                    'poli'       => $poli,
                    'jumlah'     => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('kunjungan_pasien')->insert($data);
    }
}
