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
                <!-- <div class="col-12">
                    <a href="{{ route('user-member.create') }}" class="btn btn-primary waves-effect waves-light mb-3">Add</a>
                </div> -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Outlet </th>
                                        <th>Nota<br>Masuk</th>
                                        <th>QC</th>
                                        <th>Cuci</th>
                                        <th>Pengeringan</th>
                                        <th>Setrika</th>
                                        <th>Nota <br>Keluar</th> 
                                        <!-- <th>Registrasi</th>
                                        <th width="10%">Aksi</th> -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                url: "{!! route('infogram-outlet.get-data') !!}",
                type: "POST",
                dataType: "JSON"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'nama', name: 'nama'},
                {data: 'nota_masuk', name: 'nota_masuk'},
                {data: 'total_qc', name: 'total_qc'},
                {data: 'total_cuci', name: 'total_cuci'},
                {data: 'total_pengeringan', name: 'total_pengeringan'},
                {data: 'total_setrika', name: 'total_setrika'},
                {data: 'total_kirim', name: 'total_kirim'},
                // {data: 'created_at', name: 'created_at'},
                // {data: 'picked_by', name: 'picked_by'},
                // {data: 'picked_at', name: 'picked_at'},
                // {data: 'deliver_by', name: 'deliver_by'},
                // {data: 'deliver_at', name: 'deliver_at'},
            ]
        });
    });
</script>
@endpush
