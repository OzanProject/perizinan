<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisPerizinanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We assume authorization is handled via middleware/policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['nullable', 'string', 'max:50'],
            'masa_berlaku_nilai' => ['required', 'integer', 'min:1'],
            'masa_berlaku_unit' => ['required', 'in:Tahun,Bulan'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable'], // Since it's a checkbox, it might be present or not
        ];
    }
}
