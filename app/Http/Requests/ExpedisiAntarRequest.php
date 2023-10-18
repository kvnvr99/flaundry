<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpedisiAntarRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'deliver_at'           => 'required',
        ];
    }

    public function messages() {
        return [
            'deliver_at.required'  => 'silahkan pilih pengantar',
        ];
    }
}
