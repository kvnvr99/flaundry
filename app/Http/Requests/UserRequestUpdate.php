<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequestUpdate extends FormRequest{

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'                  => 'required|max:50',
            'email'                 => ['required', 'max:50', 'email', 'unique:users,email,'.$this->id],
            'password_confirmation' => 'sometimes',
            'password'              => ['sometimes', 'confirmed'],
            'role'                  => 'required|exists:roles,id'
        ];
    }

    public function messages() {
        return [
            'name.required'                     => 'nama user harus diisi',
            'name.max'                          => 'nama maksimal 50 karakter',
            'email.required'                    => 'email user harus diisi',
            'email.unique'                      => 'email ini sudah terdaftar',
            'email.max'                         => 'email maksimal 50 karakter',
            'email.email'                       => 'email tidak valid',
            'password_confirmation.required'    => 'konfirmasi password harus diisi',
            'password.required'                 => 'password harus diisi',
            'password.confirmed'                => 'password harus sama',
            'role.required'                     => 'silahkan pilih role user',
            'role.exists'                       => 'role yang dipilih tidak tersedia'
        ];
    }
}
