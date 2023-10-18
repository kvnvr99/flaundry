<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanLaundryRequestUpdate extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'tanggal'           => 'required',
            'waktu'             => 'required',
            'alamat'            => 'required',
        ];
    }

    public function messages() {
        return [
            'tanggal.required'  => 'tanggal harus diisi',
            'waktu.required'    => 'waktu harus diisi',
            'alamat.required'   => 'alamat harus diisi',
        ];
    }
}
