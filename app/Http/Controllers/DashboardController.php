<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('kunjungan_pasien');

        // =========================
        // FILTER TANGGAL
        // =========================
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // =========================
        // FILTER POLI
        // =========================
        if ($request->poli) {
            $query->where('poli', $request->poli);
        }

        // =========================
        // GRAFIK HARIAN TOTAL (LINE - LAMA)
        // =========================
        $harian = (clone $query)
            ->select('tanggal', DB::raw('SUM(jumlah) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $harian->pluck('tanggal');
        $values = $harian->pluck('total');

        // =========================
        // GRAFIK HARIAN PER POLI (LINE MULTI DATASET) ✅ BARU
        // =========================
        $polis = DB::table('kunjungan_pasien')
            ->distinct()
            ->pluck('poli');

        $datasetsPoli = [];

        $colors = [
            'Umum' => '#1e3a8a',     
            'Gigi' => '#2563eb',      
            'KIA'  => '#4f46e5',      
            'Lansia' => '#6d28d9',    
            'TB' => '#0f766e',
            'Imunisasi' => '#0284c7',
        ];

        foreach ($polis as $poliItem) {
            $data = (clone $query)
                ->where('poli', $poliItem)
                ->select('tanggal', DB::raw('SUM(jumlah) as total'))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->pluck('total');

            $datasetsPoli[] = [
                'label' => $poliItem,
                'data' => $data,
                'borderColor' => $colors[$poliItem] ?? '#020617',
                'backgroundColor' => 'transparent',
                'borderWidth' => 2,
                'tension' => 0.4,
            ];
        }

        // =========================
        // GRAFIK PER POLI (BAR)
        // =========================
        $poli = (clone $query)
            ->select('poli', DB::raw('SUM(jumlah) as total'))
            ->groupBy('poli')
            ->get();

        $poliLabels = $poli->pluck('poli');
        $poliValues = $poli->pluck('total');

        // =========================
        // GRAFIK BULANAN (BAR)
        // =========================
        $bulanan = (clone $query)
            ->select(
                DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan'),
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = $bulanan->pluck('bulan');
        $bulanValues = $bulanan->pluck('total');

        // =========================
        // LIST POLI (FILTER)
        // =========================
        $listPoli = DB::table('kunjungan_pasien')
            ->distinct()
            ->pluck('poli');

        return view('dashboard', compact(
            'labels',
            'values',
            'datasetsPoli',   // 🔥 BARU
            'poliLabels',
            'poliValues',
            'bulanLabels',
            'bulanValues',
            'listPoli'
        ));
    }

    // =========================
    // AJAX (TIDAK DIUBAH)
    // =========================
    public function ajaxData(Request $request)
    {
        $query = DB::table('kunjungan_pasien');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->poli) {
            $query->where('poli', $request->poli);
        }

        // =========================
        // LABEL TANGGAL (WAJIB SAMA)
        // =========================
        $labels = (clone $query)
            ->select('tanggal')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('tanggal');

        // =========================
        // DATASET PER POLI
        // =========================
        $polis = (clone $query)
            ->distinct()
            ->pluck('poli');

        $colors = [
            'Umum' => '#2563eb',
            'Gigi' => '#0ea5e9',
            'KIA' => '#6366f1',
            'Lansia' => '#8b5cf6',
            'TB' => '#14b8a6',
            'Imunisasi' => '#0284c7',
        ];

        $datasetsPoli = [];

        foreach ($polis as $poliItem) {
            $data = (clone $query)
                ->where('poli', $poliItem)
                ->select('tanggal', DB::raw('SUM(jumlah) as total'))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->pluck('total');

            $datasetsPoli[] = [
                'label' => $poliItem,
                'data' => $data,
                'borderColor' => $colors[$poliItem] ?? '#64748b',
                'borderWidth' => 2,
                'tension' => 0.4,
                'fill' => true,
            ];
        }

        // =========================
        // BAR CHART PER POLI
        // =========================
        $perPoli = (clone $query)
            ->select('poli', DB::raw('SUM(jumlah) as total'))
            ->groupBy('poli')
            ->get();

        return response()->json([
            'labels' => $labels,
            'datasetsPoli' => $datasetsPoli,
            'poliLabels' => $perPoli->pluck('poli'),
            'poliValues' => $perPoli->pluck('total'),
            
        ]);
    }
}