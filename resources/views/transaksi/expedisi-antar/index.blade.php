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
                                        <th>Kode Transaksi</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
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
                <label class="mt-0">Kode Transaksi&nbsp;:&nbsp;</label><label class="mt-0 kode_transaksi"></label>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Alamat Pelanggan</label>
                                            <input type="text" class="form-control alamat_pelanggan" id="field-3" placeholder="Alamat" readonly>
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
                                                <label for="field-3" class="control-label">Catatan Kurir</label>
                                                <input type="hidden" class="form-control" id="transaksi_id" name="transaksi_id" placeholder="id transaksi" readonly>
                                                <input type="text" class="form-control" id="catatan_kurir" name="catatan_kurir" placeholder="catatan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Transaksi Selesai</button>
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
                url: "{!! route('expedisi-antar.get-data') !!}",
                type: "POST",
                dataType: "JSON"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'kode_transaksi', name: 'kode_transaksi'},
                {data: 'nama', name: 'nama'},
                {data: 'alamat', name: 'alamat'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ]
        });
    });
</script>

<script>
    function open_modal(id){
        $.ajax({
            type:"POST",
            url: "{!! route('expedisi-antar.get-data-info') !!}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#input-modal').modal('show');

                $('#transaksi_id').val(res.id);
                // $('#deliver_at').val(res.deliver_by);

                $('.kode_transaksi').text(res.kode_transaksi); 
                $('.nama_pelanggan').val(res.nama); 
                $('.alamat_pelanggan').val(res.alamat);
                $('.catatan_kurir').val(res.catatan_kurir);
            }
        });
    } 

    $('#modalForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "{!! route('expedisi-antar.store') !!}",
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
