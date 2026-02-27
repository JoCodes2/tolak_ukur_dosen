<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class KomponenRequest extends FormRequest
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
            'id_prodi' => 'required|exists:program_studi',
            'id_mk' => 'required|exists:mata_kuliah',
            'id_periode' => 'required|exists:periode',
            'nama_komponen' => 'required|string|max:255',
        ];
    }


    public function messages(): array
    {
        return [
            'id_prodi.required' => 'Program studi wajib dipilih.',
            'id_prodi.exists' => 'Program studi tidak ditemukan.',

            'id_mk.required' => 'Mata kuliah wajib dipilih.',
            'id_mk.exists' => 'Mata kuliah tidak ditemukan.',

            'id_periode.required' => 'Periode wajib dipilih.',
            'id_periode.exists' => 'Periode tidak ditemukan.',

            'nama_komponen.required' => 'Nama komponen wajib diisi.',
            'nama_komponen.string' => 'Nama komponen harus berupa teks.',
            'nama_komponen.max' => 'Nama komponen maksimal 255 karakter.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'code'    => 422,
                'status'  => 'validation_failed',
                'message' => 'Check your input data',
                'data'    => $validator->errors(),
            ], 422)
        );
    }
}
