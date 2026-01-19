<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HewanService;
use App\Http\Requests\LapanganBadminton\StoreRequest;
use App\Http\Requests\Pet\StoreRequest as PetStoreRequest;

class HewanController extends Controller
{
    protected $hewanService;
    public function __construct(HewanService $hewanService)
    {
        $this->hewanService = $hewanService;
    }

    public function index(Request $request)
    {
        $title = 'Hewan';
        $data = [
            'perPage' => $request->input('perPage', 10),
            'ajax' => $request->ajax(),
        ];

        $hewans = $this->hewanService->index($data);

        if ($request->ajax()) {
            return response()->json($hewans);
        }

        return view('pages.tables.hewan', compact('hewans', 'title'));
    }

    public function store(PetStoreRequest $request)
    {
        $this->hewanService->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hewan berhasil ditambahkan!'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $bookings = $this->hewanService->search($query);

        return response()->json($bookings);
    }
}
