<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpedisiJadwalJemputRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'picked_name'           => 'required',
        ];
    }

    public function messages() {
        return [
            'picked_name.required'  => 'silahkan pilih penjemput',
        ];
    }
}
