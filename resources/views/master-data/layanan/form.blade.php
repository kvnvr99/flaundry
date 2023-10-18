@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('layanan.store') : route('layanan.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')
    <div class="form-group mb-3">
        <label class="required">Kode</label>
        <input value="{{ !isset($data['detail']) ? old('kode') : old('kode', $data['detail']->kode) }}" type="text" name="kode" class="form-control mb-2 @error('kode') is-invalid @enderror" placeholder="kode" autocomplete="off" />
        @if($errors->has('kode'))
            <div class="text-danger"> {{ $errors->first('kode')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail']->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('nama') : old('nama', $data['detail']->nama) }}" type="text" name="nama" class="form-control mb-2 @error('nama') is-invalid @enderror" placeholder="nama" autocomplete="off" />
        @if($errors->has('nama'))
            <div class="text-danger"> {{ $errors->first('nama')}} </div>
        @endif
    </div>

    @endslot
@endcomponent
@endsection
@push('script')
<script>

</script>
@endpush
