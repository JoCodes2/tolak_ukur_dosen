<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MahasiswaRequest extends FormRequest
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

            'nim' => [
                'required',
                $id ? 'unique:mahasiswa,nim,' . $id : 'unique:mahasiswa,nim',
            ],
            'nama' => 'required',
            'angkatan' => 'required',
            'id_prodi' => 'required',
        ];
    }


    public function messages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique'   => 'NIM sudah terdaftar, silakan gunakan NIM lain.',

            'nama.required' => 'Nama wajib diisi.',

            'angkatan.required' => 'Angkatan wajib diisi.',

            'id_prodi.required' => 'Program Studi wajib diisi.',
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
