<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopupRequestUpdate extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'nama'              => 'required',
        ];
    }

    public function messages() {
        return [
            'nama.required'     => 'nama member harus diisi',
            'nominal.required'  => 'nominal harus diisi',
        ];
    }
}
