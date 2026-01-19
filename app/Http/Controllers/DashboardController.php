<?php

namespace App\Http\Controllers;

use BookingHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $tanggal = $request->tanggal_sewa ?? now()->toDateString();
        $bulan = $request->tanggal_sewa ?? now()->format('Y-m');
        $tahun = $request->tanggal_sewa ?? now()->year;

        return view('pages.dashboard.dashboard', compact('tanggal', 'bulan', 'tahun'));
    }

    public function jadwalLapangan(Request $request)
    {
        $tanggal = $request->tanggal_sewa ?? now()->toDateString();

        return response()->json([
            'tanggal' => $tanggal,
            'jadwal' => $this->dashboardService->filterJadwalLapangan($tanggal),
        ]);
    }

    public function filterHari(Request $request)
    {
        $tanggal = $request->tanggal_sewa ?? now()->toDateString();

        return response()->json([
            'tanggal' => $tanggal,
            'total' => $this->dashboardService->filterHari($tanggal),
        ]);
    }

    public function filterBulan(Request $request)
    {
        $bulan = $request->bulan_sewa
        ? Carbon::createFromFormat('Y-m', $request->bulan_sewa)
        : now();

        return response()->json([
            'bulan' => $bulan->format('Y-m'),
            'total' => $this->dashboardService->filterBulan($bulan->month, $bulan->year),
        ]);
    }

    public function filterTahun(Request $request)
    {
        $tahun = $request->tahun_sewa ?? now()->year;

        return response()->json([
            'tahun' => $tahun,
            'total' => $this->dashboardService->filterTahun($tahun)
        ]);
    }

    public function grafikPendapatan(Request $request)
    {
        $tahun = $request->tahun_sewa ?? now()->year;

        return response()->json([
            'tahun' => $tahun,
            'data' => $this->dashboardService->grafikPendapatan($tahun)
        ]);
    }
}
