@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('user-member.store') : route('user-member.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')

    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail']->id }}" name="member_id">
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail']->user->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('name') : old('name', $data['detail']->user->name) }}" type="text" name="name" class="form-control mb-2 @error('name') is-invalid @enderror" placeholder="name" autocomplete="off" />
        @if($errors->has('name'))
            <div class="text-danger"> {{ $errors->first('name')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Email</label>
        <input value="{{ !isset($data['detail']) ? old('email') : old('email', $data['detail']->user->email) }}" type="email" name="email" class="form-control mb-2 @error('email') is-invalid @enderror" placeholder="email" autocomplete="off" />
        @if($errors->has('email'))
            <div class="text-danger"> {{ $errors->first('email')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Telephone/Whatsapp</label>
        <input value="{{ !isset($data['detail']) ? old('phone') : old('phone', $data['detail']->phone) }}" type="text" name="phone" class="form-control mb-2 @error('phone') is-invalid @enderror" placeholder="phone" autocomplete="off" />
        @if($errors->has('phone'))
            <div class="text-danger"> {{ $errors->first('phone')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat</label>
        <input value="{{ !isset($data['detail']) ? old('address') : old('address', $data['detail']->address) }}" type="text" name="address" class="form-control mb-2 @error('address') is-invalid @enderror" placeholder="address" autocomplete="off" />
        @if($errors->has('address'))
            <div class="text-danger"> {{ $errors->first('address')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="{{ !isset($data['detail']) ? 'required' : '' }}">Password</label>
        <input autocomplete="off" type="password" name="password" class="form-control mb-2" placeholder="password" />
        @if($errors->has('password'))
            <div class="text-danger"> {{ $errors->first('password')}} </div>
        @endif
    </div>
    <div class="form-group mb-3">
        <label class="{{ !isset($data['detail']) ? 'required' : '' }}">Konfirmasi Password</label>
        <input autocomplete="off" type="password" name="password_confirmation" class="form-control mb-2" placeholder="konfirmasi password" />
        @if($errors->has('password_confirmation'))
            <div class="text-danger"> {{ $errors->first('password_confirmation')}} </div>
        @endif
    </div>

    @endslot
@endcomponent
@endsection
@push('script')
<script>

</script>
@endpush
