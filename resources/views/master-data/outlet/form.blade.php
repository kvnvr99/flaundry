@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('outlet.store') : route('outlet.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')
    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail']->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('nama') : old('nama', $data['detail']->nama) }}" type="text" name="nama" class="form-control mb-2 @error('nama') is-invalid @enderror" placeholder="nama" autocomplete="off" />
        @if($errors->has('nama'))
            <div class="text-danger"> {{ $errors->first('nama')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat</label>
        <input value="{{ !isset($data['detail']) ? old('alamat') : old('alamat', $data['detail']->alamat) }}" type="text" name="alamat" class="form-control mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat" autocomplete="off" />
        @if($errors->has('alamat'))
            <div class="text-danger"> {{ $errors->first('alamat')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">No Telepon</label>
        <input value="{{ !isset($data['detail']) ? old('no_telepon') : old('no_telepon', $data['detail']->no_telepon) }}" type="text" name="no_telepon" class="form-control mb-2 @error('no_telepon') is-invalid @enderror" placeholder="no telepon" autocomplete="off"/>
        @if($errors->has('no_telepon'))
            <div class="text-danger"> {{ $errors->first('no_telepon')}} </div>
        @endif
    </div>

    @endslot
@endcomponent
@endsection
@push('script')
<script>

</script>
@endpush
