@extends('layouts.main_user')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', '#')
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')

    <div class="form-group mb-3">
        <label class="required">Tanggal Penjemputan</label>
        <input value="{{ !isset($data['detail']) ? old('tanggal') : old('name', $data['detail']->tanggal) }}" type="text" name="tanggal" class="form-control mb-2" placeholder="tanggal penjemputan" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Waktu Penjemputan</label><label style="color:red; font-size:10px;">&nbsp;*Format 24 Jam</label>
        <input value="{{ !isset($data['detail']) ? old('waktu') : old('waktu', $data['detail']->waktu) }}" type="text" name="waktu" class="form-control mb-2" placeholder="waktu penjemputan" data-autoclose="true" autocomplete="off" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat Penjemputan</label>
        <input value="{{ !isset($data['detail']) ? old('alamat') : old('alamat', $data['detail']->alamat) }}" type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat penjemputan" />
    </div>
    <div class="form-group mb-3">
        <label class="required">Catatan</label>
        <textarea type="text" name="catatan" class="form-control mb-2">
        {{ !isset($data['detail']) ? old('catatan') : old('catatan', $data['detail']->catatan) }}
        </textarea>
    </div>
    @endslot
@endcomponent
@endsection

