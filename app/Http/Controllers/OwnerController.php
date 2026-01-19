<?php

namespace App\Http\Controllers;

use App\Http\Requests\Owner\StoreRequest;
use App\Models\Owner;
use App\Services\OwnerService;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    protected $ownerService;

    public function __construct(OwnerService $ownerService)
    {
        $this->ownerService = $ownerService;
    }

    public function index(Request $request)
    {
        $title = 'Owner';
        $data = [
            'perPage' => $request->input('perPage', 10),
            'ajax' => $request->ajax(),
        ];

        $owners = $this->ownerService->index($data);

        if ($request->ajax()) {
            return response()->json($owners);
        }
        return view('pages.tables.owner', compact('title', 'owners'));
    }

    public function store(StoreRequest $request)
    {
        $this->ownerService->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Owner berhasil ditambahkan!'
        ]);
    }

    public function validOwners()
    {
    return response()->json(
            $this->ownerService->getValidOwners()
    );
    }
}
