<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LayananRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'kode'                  => 'required|max:50|unique:layanans',
            'nama'                  => 'required|max:50|unique:layanans',
        ];
    }

    public function messages() {
        return [
            'kode.required'                     => 'kode user harus diisi',
            'kode.max'                          => 'kode maksimal 50 karakter',
            'kode.unique'                       => 'kode outlet sudah terdaftar',
            'nama.required'                     => 'nama user harus diisi',
            'nama.max'                          => 'nama maksimal 50 karakter',
            'nama.unique'                       => 'nama outlet sudah terdaftar',
        ];
    }
}
