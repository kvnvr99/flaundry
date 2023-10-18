<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KasirRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'outlet'             => 'required',
            'nama'                  => 'required|max:100',
            'bayar'                 => 'required',
            'pembayaran'            => 'required',
        ];
    }

    public function messages() {
        return [
            'outlet.required'    => 'Silahkan pilih outlet',
            'nama.required'         => 'nama harus diisi',
            'total.required'        => 'total harus diisi',
            'bayar.required'        => 'bayar harus diisi',
            'pembayaran.required'   => 'pembayaran harus diisi',
        ];
    }
}
