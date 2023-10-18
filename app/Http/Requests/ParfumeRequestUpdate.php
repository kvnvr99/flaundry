<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParfumeRequestUpdate extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'kode'                  => 'required|max:50|unique:parfumes',
            'nama'                  => 'required|max:50|unique:parfumes',
        ];
    }

    public function messages() {
        return [
            'kode.required'                     => 'kode parfume harus diisi',
            'kode.max'                          => 'kode maksimal 50 karakter',
            'kode.unique'                       => 'kode parfume sudah terdaftar',
            'nama.required'                     => 'nama parfume harus diisi',
            'nama.max'                          => 'nama maksimal 50 karakter',
            'nama.unique'                       => 'nama parfume sudah terdaftar',
        ];
    }
}
