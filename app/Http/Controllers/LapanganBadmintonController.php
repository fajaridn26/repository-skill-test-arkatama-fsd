<?php

namespace App\Http\Controllers;

use App\Http\Requests\LapanganBadminton\StoreRequest;
use App\Models\Booking;
use App\Services\LapanganBadmintonService;
use Illuminate\Http\Request;

class LapanganBadmintonController extends Controller
{
    protected $lapanganBadmintonService;
    public function __construct(LapanganBadmintonService $lapanganBadmintonService)
    {
        $this->lapanganBadmintonService = $lapanganBadmintonService;
    }

    public function index(Request $request)
    {
        $title = 'Lapangan Badminton';
        $tanggal = $request->tanggal_sewa ?? now()->toDateString();
        $data = [
            'perPage' => $request->input('perPage', 10),
            'ajax' => $request->ajax(),
        ];

        $bookings = $this->lapanganBadmintonService->index($data);

        if ($request->ajax()) {
            return response()->json($bookings);
        }

        return view('pages.tables.lapangan-badminton', compact('bookings', 'title', 'tanggal'));
    }

    public function store(StoreRequest $request)
    {
        $this->lapanganBadmintonService->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil disimpan!'
        ]);
    }

    public function jadwalLapangan(Request $request)
    {
        $tanggal = $request->tanggal_sewa ?? now()->toDateString();

        return response()->json([
            'tanggal' => $tanggal,
            'jadwal' => $this->lapanganBadmintonService->filterJadwalLapangan($tanggal),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $bookings = $this->lapanganBadmintonService->search($query);

        return response()->json($bookings);
    }
}
