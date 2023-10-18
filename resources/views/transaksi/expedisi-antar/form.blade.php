@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('expedisi-jadwal-antar.update') : route('expedisi-jadwal-antar.update'))
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')

    <div class="form-group mb-3">
        <label class="required">Nama Pemesan</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->id }}" name="id">
        <input value="{{ !isset($data['detail']) ? old('nama_pemesan') : old('nama', $data['detail'][0]->nama) }}" type="text" name="nama_pemesan" class="form-control flatpickr-input active mb-2 @error('nama_pemesan') is-invalid @enderror" placeholder="nama pemesan" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat Penantaran</label>
        <input value="{{ !isset($data['detail']) ? $data['info']->address : old('alamat', $data['detail'][0]->alamat) }}" type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat penantaran" readonly>
    </div>

    

    <div class="form-group mb-3">
        <label class="required">Karyawan yang Menantar </label>
        <div class="input-group">
            <input value="{{ !isset($data['detail']) ? old('deliver_name') : old('deliver_name', $data['detail'][0]->deliver_name) }}" type="text" name="deliver_name" id="deliver_name" class="form-control mb-2 @error('deliver_name') is-invalid @enderror" placeholder="nama penantar" autocomplete="off" readonly>
            <div class="input-group-append">
                <button class="btn btn-dark waves-effect waves-light mb-2" type="button" data-toggle="modal" onclick="ambil_user()"> Cari Karyawan</button>
            </div>
            <input value="{{ !isset($data['detail']) ? old('deliver_by') : old('deliver_by', $data['detail'][0]->deliver_by) }}" type="hidden" name="deliver_by" id="deliver_by" class="form-control mb-2 @error('deliver_by') is-invalid @enderror" placeholder="deliver_by" autocomplete="off"/>
        </div>
        @if($errors->has('deliver_name'))
            <div class="text-danger"> {{ $errors->first('deliver_name')}} </div>
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
                    url: "{!! route('expedisi-jadwal-antar.get-data-user') !!}",
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
            $("#deliver_by").val(data.id);
            $("#deliver_name").val(data.name);
            
            $('#modal-user').modal('hide');   
        });           
    }
</script>
@endpush
