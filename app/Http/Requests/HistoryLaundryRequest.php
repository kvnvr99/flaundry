<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoryLaundryRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
        ];
    }

    public function messages() {
        return [
        ];
    }
}
