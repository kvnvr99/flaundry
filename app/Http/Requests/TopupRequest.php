<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopupRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'member_id'             => 'required',
        ];
    }

    public function messages() {
        return [
            'member_id.required'    => 'nama member harus diisi',
            'nominal.required'      => 'nominal harus diisi',
        ];
    }
}
