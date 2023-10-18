@extends('layouts.main')
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
        <label class="required">Nama</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('name') : old('name', $data['detail']->nama) }}" type="text"  class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">Alamat</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('alamat') : old('alamat', $data['detail']->alamat) }}" type="text" class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">No Telepon</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('no_telepon') : old('no_telepon', $data['detail']->no_telepon) }}" type="text" class="form-control mb-2" />
    </div>
    

    @endslot
@endcomponent
@endsection
