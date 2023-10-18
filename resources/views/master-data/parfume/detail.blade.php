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
        <label class="required">Kode</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('kode') : old('kode', $data['detail']->kode) }}" type="text"  class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('nama') : old('nama', $data['detail']->nama) }}" type="text"  class="form-control mb-2" />
    </div>
    

    @endslot
@endcomponent
@endsection
