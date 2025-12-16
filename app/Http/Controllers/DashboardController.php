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
        // GRAFIK HARIAN (LINE)
        // =========================
        $harian = (clone $query)
            ->select('tanggal', DB::raw('SUM(jumlah) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $harian->pluck('tanggal');
        $values = $harian->pluck('total');

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
            'poliLabels',
            'poliValues',
            'bulanLabels',
            'bulanValues',
            'listPoli'
        ));
    }
    
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

        $harian = (clone $query)
            ->select('tanggal', DB::raw('SUM(jumlah) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $perPoli = (clone $query)
            ->select('poli', DB::raw('SUM(jumlah) as total'))
            ->groupBy('poli')
            ->get();

        return response()->json([
            'labels' => $harian->pluck('tanggal'),
            'values' => $harian->pluck('total'),
            'poliLabels' => $perPoli->pluck('poli'),
            'poliValues' => $perPoli->pluck('total'),
            'total' => $harian->sum('total'),
            'avg' => round($harian->avg('total'), 1),
            'max' => $harian->max('total'),
        ]);
    }
}