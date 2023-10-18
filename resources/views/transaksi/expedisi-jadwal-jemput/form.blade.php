@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('expedisi-jadwal-jemput.store') : route('expedisi-jadwal-jemput.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')

    <div class="form-group mb-3">
        <label class="required">Nama Pemesan</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('nama_pemesan') : old('name', $data['detail'][0]->name) }}" type="text" name="nama_pemesan" class="form-control flatpickr-input active mb-2 @error('nama_pemesan') is-invalid @enderror" placeholder="nama pemesan" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat Penjemputan</label>
        <input value="{{ !isset($data['detail']) ? $data['info']->address : old('alamat', $data['detail'][0]->alamat) }}" type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat penjemputan" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Permintaan Tanggal Penjemputan</label>
        <input value="{{ !isset($data['detail']) ? old('tanggal') : old('name', $data['detail'][0]->tanggal) }}" type="text" name="tanggal" class="form-control flatpickr-input active mb-2 @error('tanggal') is-invalid @enderror" placeholder="tanggal penjemputan" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Permintaan Waktu Penjemputan</label><label style="color:red; font-size:10px;">&nbsp;*Format 24 Jam</label>
        <input value="{{ !isset($data['detail']) ? old('waktu') : old('waktu', $data['detail'][0]->waktu) }}" type="text" name="waktu" class="form-control clockpicker active mb-2 @error('waktu') is-invalid @enderror" placeholder="waktu penjemputan" data-autoclose="true" autocomplete="off" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Catatan dari Pelanggan</label>
        <textarea value="" type="text" name="catatan" class="form-control mb-2" readonly>
        {{ !isset($data['detail']) ? old('catatan') : old('catatan', $data['detail'][0]->catatan) }}
        </textarea>
    </div>

    <div class="form-group mb-3">
        <label class="required">Karyawan yang Menjemput </label>
        <div class="input-group">
            <input value="{{ !isset($data['detail']) ? old('picked_name') : old('picked_name', $data['detail'][0]->picked_name) }}" type="text" name="picked_name" id="picked_name" class="form-control mb-2 @error('picked_name') is-invalid @enderror" placeholder="nama penjemput" autocomplete="off" readonly>
            <div class="input-group-append">
                <button class="btn btn-dark waves-effect waves-light mb-2" type="button" data-toggle="modal" onclick="ambil_user()"> Cari Karyawan</button>
            </div>
            <input value="{{ !isset($data['detail']) ? old('picked_by') : old('picked_by', $data['detail'][0]->picked_by) }}" type="hidden" name="picked_by" id="picked_by" class="form-control mb-2 @error('picked_by') is-invalid @enderror" placeholder="picked_by" autocomplete="off"/>
        </div>
        @if($errors->has('picked_name'))
            <div class="text-danger"> {{ $errors->first('picked_name')}} </div>
        @endif
    </div>
   

<div class="modal fade" id="modal-user" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Karyawan</h4>
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
    function ambil_user(){
        $('#modal-user').modal('show');
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
                    url: "{!! route('expedisi-jadwal-jemput.get-data-user') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'name', name: 'name'}
                ]
            });

        var datatable = $('#state-saving-datatable').DataTable();

        $('#state-saving-datatable tbody').on('click', 'tr', function () {
            var data = datatable.row(this).data();
            $("#picked_by").val(data.id);
            $("#picked_name").val(data.name);
            
            $('#modal-user').modal('hide');   
        });           
    }
</script>
@endpush
