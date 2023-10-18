<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutleteRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'nama'                  => 'required|max:50|unique:outlets',
            'alamat'                => 'sometimes|max:500',
            'no_telepon'            => 'sometimes|digits_between:0,14',
        ];
    }

    public function messages() {
        return [
            'nama.required'                     => 'nama user harus diisi',
            'nama.max'                          => 'nama maksimal 50 karakter',
            'nama.unique'                       => 'nama outlet sudah terdaftar',
            'alamat.max'                        => 'alamat maksimal 500 karakter',
            'no_telepon.digits_between'         => 'masukan nomor dan maksimal 14 digit',
        ];
    }
}
