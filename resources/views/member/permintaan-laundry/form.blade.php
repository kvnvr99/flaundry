@extends('layouts.main_user')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('permintaan-laundry.store') : route('permintaan-laundry.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')

    <div class="form-group mb-3">
        <label class="required">Tanggal Penjemputan</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->id }}" name="id">
        <input type="hidden" value="{{ !isset($data['detail']) ? $data['info']->id : $data['detail'][0]->member_id }}" name="member_id">
        <input value="{{ !isset($data['detail']) ? old('tanggal') : old('name', $data['detail'][0]->tanggal) }}" type="text" name="tanggal" class="form-control flatpickr-input active mb-2 @error('tanggal') is-invalid @enderror" placeholder="tanggal penjemputan" id="basic-datepicker">
        @if($errors->has('tanggal'))
            <div class="text-danger"> {{ $errors->first('tanggal')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Waktu Penjemputan</label><label style="color:red; font-size:10px;">&nbsp;*Format 24 Jam</label>
        <input value="{{ !isset($data['detail']) ? old('waktu') : old('waktu', $data['detail'][0]->waktu) }}" type="text" name="waktu" class="form-control clockpicker active mb-2 @error('waktu') is-invalid @enderror" placeholder="waktu penjemputan" data-autoclose="true" autocomplete="off">
        @if($errors->has('waktu'))
            <div class="text-danger"> {{ $errors->first('waktu')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat Penjemputan</label>
        <input value="{{ !isset($data['detail']) ? $data['info']->address : old('alamat', $data['detail'][0]->alamat) }}" type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat penjemputan" />
        @if($errors->has('alamat'))
            <div class="text-danger"> {{ $errors->first('alamat')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="required">Layanan</label>
        <div class="input-group">
            <input value="{{ !isset($data['detail']) ? old('layanan_id') : old('layanan_id', $data['detail'][0]->layanan_id) }}" type="hidden" name="layanan_id" id="layanan_id" class="form-control mb-2 @error('layanan_id') is-invalid @enderror" placeholder="layanan id" autocomplete="off"/>
            <input value="{{ !isset($data['detail']) ? old('nama_layanan') : old('nama_layanan', $data['detail'][0]->nama_layanan) }}" type="text" name="nama_layanan" id="nama_layanan" class="form-control mb-2 @error('nama_layanan') is-invalid @enderror" placeholder="nama layanan" autocomplete="off" readonly>
            <div class="input-group-append">
                <button class="btn btn-dark waves-effect waves-light mb-2" type="button" data-toggle="modal" onclick="ambil_layanan()"> Cari Layanan</button>
            </div>
            @if($errors->has('nama_layanan'))
                <div class="text-danger"> {{ $errors->first('nama_layanan')}} </div>
            @endif
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="required">Parfume</label>
        <div class="input-group">
            <input value="{{ !isset($data['detail']) ? old('parfume_id') : old('parfume_id', $data['detail'][0]->parfume_id) }}" type="hidden" name="parfume_id" id="parfume_id" class="form-control mb-2 @error('parfume_id') is-invalid @enderror" placeholder="parfume_id" autocomplete="off"/>
            <input value="{{ !isset($data['detail']) ? old('nama_parfume') : old('nama_parfume', $data['detail'][0]->nama_parfume) }}" type="text" name="nama_parfume" id="nama_parfume" class="form-control mb-2 @error('nama_parfume') is-invalid @enderror" placeholder="nama parfume" autocomplete="off" readonly>
            <div class="input-group-append">
                <button class="btn btn-dark waves-effect waves-light mb-2" type="button" data-toggle="modal" onclick="ambil_parfume()"> Cari Parfume</button>
            </div>
            @if($errors->has('nama_parfume'))
                <div class="text-danger"> {{ $errors->first('nama_parfume')}} </div>
            @endif
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="required">Catatan</label>
        <textarea value="" type="text" name="catatan" class="form-control mb-2">
        {{ !isset($data['detail']) ? old('catatan') : old('catatan', $data['detail'][0]->catatan) }}
        </textarea>
        @if($errors->has('catatan'))
            <div class="text-danger"> {{ $errors->first('catatan')}} </div>
        @endif
    </div>

<div class="modal fade" id="modal-layanan" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Layanan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" id="layanan-datatable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-parfume" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Parfume</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" id="state-saving-datatable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

    @endslot
@endcomponent
@endsection
@push('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script>
    function ambil_layanan(){
        $('#modal-layanan').modal('show');
            $("#layanan-datatable").DataTable().destroy(); 
            $('#layanan-datatable tbody').remove(); 
            $('#layanan-datatable').DataTable({
                responsive: true,
                processing: true,
                "lengthMenu": [[5, 10], [5, 10]],
                "language": {
                    "lengthMenu": "_MENU_"
                },
                serverSide: true,
                method: "POST",
                scrollX: true,
                ajax: {
                    url: "{!! route('permintaan-laundry.get-data-layanan') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'nama', name: 'nama'}
                ]
            });

        var datatable = $('#layanan-datatable').DataTable();

        $('#layanan-datatable tbody').on('click', 'tr', function () {
            var data = datatable.row(this).data();
            $("#layanan_id").val(data.id);
            $("#nama_layanan").val(data.nama);
            
            $('#modal-layanan').modal('hide');   
        });           
    }

    function ambil_parfume(){
        $('#modal-parfume').modal('show');
            $("#state-saving-datatable").DataTable().destroy(); 
            $('#state-saving-datatable tbody').remove(); 
            $('#state-saving-datatable').DataTable({
                responsive: true,
                processing: true,
                "lengthMenu": [[5, 10], [5, 10]],
                "language": {
                    "lengthMenu": "_MENU_"
                },
                serverSide: true,
                method: "POST",
                scrollX: true,
                ajax: {
                    url: "{!! route('permintaan-laundry.get-data-parfume') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'nama', name: 'nama'}
                ]
            });

        var datatable = $('#state-saving-datatable').DataTable();

        $('#state-saving-datatable tbody').on('click', 'tr', function () {
            var data = datatable.row(this).data();
            $("#parfume_id").val(data.id);
            $("#nama_parfume").val(data.nama);
            
            $('#modal-parfume').modal('hide');   
        });           
    }
</script>
@endpush
