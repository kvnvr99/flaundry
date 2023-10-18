<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpedisiJadwalAntarRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'deliver_name'           => 'required',
        ];
    }

    public function messages() {
        return [
            'deliver_name.required'  => 'silahkan pilih pengantar',
        ];
    }
}
