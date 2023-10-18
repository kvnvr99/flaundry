<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class MemberRequest extends FormRequest{

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'                  => 'required|max:50',
            'email'                 => 'sometimes|required|unique:users|max:50|email',
            'password_confirmation' => 'required',
            'password'              => ['required', 'confirmed', Password::min(8)],
            'phone'                 => 'required|numeric|digits_between:8,14',
            'address'               => 'required|max:500'
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
            'phone.required'                    => 'kontak telpon harus diisi',
            'phone.numeric'                     => 'masukan nomor',
            'phone.digits_between'              => 'nomor minimal 8 digit dan maksimal 14 digit',
            'address.required'                  => 'alamat harus diisi',
            'address.max'                       => 'maksimal 500 karakter'
        ];
    }
}
