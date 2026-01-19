<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HewanService;
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'name'     => 'required|string',
            'type'     => 'required|string',
            'age'      => 'required|integer|min:1',
            'weight'   => 'required|numeric|min:0.1',
        ]);

        $this->hewanService->update($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data hewan berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $this->hewanService->destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Data hewan berhasil dihapus'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $hewans = $this->hewanService->search($query);

        return response()->json($hewans);
    }
}
