@extends('layouts.main_user')
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
                    <a href="{{ route('history-laundry-corporate.create') }}" class="btn btn-primary waves-effect waves-light mb-3">Add</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Tanggal Penjemputan</th>
                                        <th>Waktu Penjemputan</th>
                                        <th>Alamat Penjemputan</th>
                                        <th>Parfume</th>
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
                <label class="mt-0">&nbsp;</label>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-header border-0">
                <div style="width: 100%;">
                    
                    <div aria-labelledby="headingTwo" data-parent="#accordion" style="">
                        <form action="javascript:void(0)" id="modalForm" name="modalForm" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="field-3" class="control-label">Komplain </label>
                                            <input type="hidden" class="form-control" id="transaksi_id" name="transaksi_id" placeholder="id transaksi" readonly>
                                            <textarea value="" type="text" name="catatan_pelanggan" id="catatan_pelanggan" class="form-control mb-2">
                                            </textarea>
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
                url: "{!! route('history-laundry-corporate.get-data') !!}",
                type: "POST",
                dataType: "JSON"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'tanggal', name: 'tanggal'},
                {data: 'waktu', name: 'waktu'},
                {data: 'alamat', name: 'alamat'},
                {data: 'nama_parfume', name: 'nama_parfume'},
                {data: 'action', name: 'action'}
            ]
        });
    });
</script>

<script>
    function like(id){
     
        let print_url           = `{{ url('like') }}`;
        let redirect_print_url  = print_url+'/'+id;
        
        $.ajax({
            type:'POST',
            url: redirect_print_url,
            // data: { id: id },
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {
                window.location.reload(true);
            },
            error: function(data){
            console.log(data);
            }
        });
    }

    function dislike(id){
     
        let print_url           = `{{ url('dislike') }}`;
        let redirect_print_url  = print_url+'/'+id;
     
        $.ajax({
            type:'POST',
            url: redirect_print_url,
            // data: { id: id },
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {
                window.location.reload(true);
            },
            error: function(data){
            console.log(data);
            }
        });
    }

    function open_modal(id){
        $.ajax({
            type:"POST",
            url: "{!! route('history-laundry-corporate.get-data-info') !!}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#input-modal').modal('show');

                $('#transaksi_id').val(res.id);
                $('#catatan_pelanggan').text(res.catatan_pelanggan);
                $('#catatan_pelanggan').focus();

            }
        });
    } 

    $('#modalForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "{!! route('history-laundry-corporate.store') !!}",
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
