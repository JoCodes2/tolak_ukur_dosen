<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MataKuliahRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->input('id');

        return [
            'kode_mk' => [
                'required',
                $id ? 'unique:mata_kuliah,kode_mk,' . $id : 'unique:mata_kuliah,kode_mk',
            ],
            'nama_mk' => 'required',
            'sks' => 'required|integer|min:1',
        ];
    }


    public function messages(): array
    {
        return [
            'kode_mk.required' => 'Kode mk wajib diisi.',
            'kode_mk.unique' => 'Kode mk sudah digunakan.',
            'nama_mk.required' => 'Nama mk wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
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
