<?php

namespace App\Http\Requests\LapanganBadminton;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_penyewa' => 'required|string|max:255',
            'nomor_lapangan' => 'required|integer',
            'tanggal_sewa' => 'required|date',
            'jam_awal_sewa' => 'required|integer',
            'jam_akhir_sewa' => 'required|integer',
            'harga_sewa' => 'required',
            'total_harga_sewa' => 'required',
        ];
    }
}
