@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('roles.store') : route('roles.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')
    <div class="form-group mb-3">
        <label class="required form-label">Nama Role</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail']->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('name') : old('name', $data['detail']->name) }}" type="text" name="name" class="form-control mb-2 @error('name') is-invalid @enderror" placeholder="Role" />
        @if($errors->has('name'))
            <div class="text-danger">
                {{ $errors->first('name')}}
            </div>
        @endif
    </div>
    <div class="form-group mb-3">
        <label class="required form-label">Akses Role</label>
        @foreach ($data['permissions'] as $permission)
        @php $permission_name = $permission->name @endphp
            <div class="checkbox checkbox-info checkbox-circle mb-2">
                <input id="{{ $permission->id }}" type="checkbox"name="permissions[]" value="{{$permission->id}}" {{ isset($data['role_permission']) && in_array($permission->id, $data['role_permission']) ? 'checked' : '' }}>
                <label for="{{ $permission->id }}"> {{ $permission_name }} </label>
            </div>
        @endforeach
        <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%;"></div>
        </div>
    </div>
    @endslot
@endcomponent
@endsection
@push('script')
<script>

</script>
@endpush
