@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            @include('component.breadcrumb')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Alamat</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <!-- <th>Catatan</th> -->
                                        <th>Penjemput</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="input-modal" class="modal fade show" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <label class="mt-0">Jadwal Jemput Laundry</label>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-header border-0">
                <div id="accordion" style="width: 100%;">
                    <div class="card mb-0">
                        <div class="card-header" id="headingOne">
                            <h5 class="m-0">
                                <a href="#collapseOne" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                                    Catatan Informasi Pelanggan
                                </a>
                            </h5>
                        </div>
            
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Nama Pelanggan</label>
                                            <input type="text" class="form-control nama_pelanggan" id="field-3" placeholder="Nama" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Tanggal</label>
                                            <input type="text" class="form-control tanggal" id="field-3" placeholder="Tanggal" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Waktu</label>
                                            <input type="text" class="form-control waktu" id="field-3" placeholder="Waktu" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Alamat Pelanggan</label>
                                            <input type="text" class="form-control alamat_pelanggan" id="field-3" placeholder="Alamat" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Catatan Pelanggan</label>
                                            <input type="text" class="form-control catatan" id="field-3" placeholder="Alamat" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-0">
                        <div class="card-header" id="headingTwo">
                            <h5 class="m-0">
                                <a href="#collapseTwo" class="text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseTwo">
                                    Penentuan Kurir Expedisi
                                </a>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion" style="">
                            <form action="javascript:void(0)" id="modalForm" name="modalForm" method="POST" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="field-3" class="control-label">Kurir yang Mengantar</label>
                                                <input type="hidden" class="form-control" id="permintaan_laundry_id" name="permintaan_laundry_id" placeholder="id transaksi" readonly>
                                                <select class="form-control" id="deliver_by" name="picked_by">
                                                    <!-- <option value="0" selected>---Pilih Kurir---</option> -->
                                                    @foreach ($kurir as $var)
                                                        <option value="{{$var->id}}">{{$var->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Simpan</button>
                                    <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@push('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script>
    $(document).ready( function () {
        let datatable = $('#state-saving-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            method: "POST",
            scrollX: true,
            ajax: {
                url: "{!! route('expedisi-jadwal-jemput.get-data') !!}",
                type: "POST",
                dataType: "JSON"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'alamat', name: 'alamat'},
                {data: 'name', name: 'name'},
                {data: 'tanggal', name: 'tanggal'},
                {data: 'waktu', name: 'waktu'},
                // {data: 'catatan', name: 'catatan'},
                {data: 'picked_name', name: 'picked_name'},
                {data: 'action', name: 'action'}
            ]
        });
    });
</script>

<script>
    function open_modal(id){
        $.ajax({
            type:"POST",
            url: "{!! route('expedisi-jadwal-jemput.get-data-info') !!}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#input-modal').modal('show');

                $('#permintaan_laundry_id').val(res.id);
                $('#picked_by').val(res.picked_by);

                // $('.kode_transaksi').text(res.kode_transaksi); 
                $('.nama_pelanggan').val(res.name); 
                $('.alamat_pelanggan').val(res.alamat);
                $('.waktu').val(res.waktu);
                $('.tanggal').val(res.tanggal);
                $('.catatan').val(res.catatan);
            }
        });
    } 

    $('#modalForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "{!! route('expedisi-jadwal-jemput.store') !!}",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {$("#input-modal").modal('hide');
                var oTable = $('#state-saving-datatable').dataTable();
                oTable.fnDraw(false);
            },
            error: function(data){
            console.log(data);
            }
        });
    });

</script>

@endpush
