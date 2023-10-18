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
                                        <th width="1%">No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama</th>
                                        <th>Outlet</th>
                                        <th>Items</th>
                                        <th>Harga</th>
                                        <th>Quantity (Pcs)</th>
                                        <th>Quantity (KG)</th>
                                        <th width="5%">Aksi</th>
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
@endsection
@push('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
<script>
    const isNumber = (evt) => {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    const numberFormater = (params) => {
        let number_value = Number(params).toLocaleString('en', {
            maximumFractionDigits: 2,
            minimumFractionDigits: 2,
            currency: 'INR'
        });
        return number_value;
    }
    $(document).ready( function () {
        let datatable = $('#state-saving-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            method: "POST",
            scrollX: true,
            ajax: {
                url: "{!! route('qc.get-data') !!}",
                type: "POST",
                dataType: "JSON"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'kode_transaksi', name: 'kode_transaksi'},
                {data: 'nama', name: 'nama'},
                {data: 'outlet.nama', name: 'outlet.nama'},
                {data: 'items', name: 'items'},
                {data: 'total', name: 'total'},
                {data: 'quantity_satuan', name: 'quantity_satuan'},
                {data: 'quantity_kg', name: 'quantity_kg'},
                {data: 'action', name: 'action'},
            ]
        });

        $(document).on('click', '.valid', function (e) {
            e.preventDefault();
            $('#loading').css("display", "block")
            let id = $(this).data('id');
            let this_row = $(this).parent().parent().parent();
            let quantity_satuan = this_row.find('.get-action .quantity_satuan').val();
            let quantity_kg = this_row.find('.get-action .quantity_kg').val();
            if (quantity_satuan == '') {
                let params = {icon: 'warning', title: 'Masukan quantity satuan !'}
                $('#loading').css("display", "none");
                showAlaret(params);
                return false;
            }
            if (quantity_kg == '') {
                let params = {icon: 'warning', title: 'Masukan quantity berat !'}
                $('#loading').css("display", "none");
                showAlaret(params);
                return false;
            }
            $.ajax({
                type: "POST",
                url: `{{ route('qc.store') }}`,
                data: ({
                    id: id,
                    quantity_satuan: quantity_satuan,
                    quantity_kg: quantity_kg
                }),
                dataType: "JSON",
                success: function (response) {
                    let params = {icon: 'success', title: 'Berhasil di update !'}
                    $('#loading').css("display", "none");
                    showAlaret(params);
                    $('#state-saving-datatable').DataTable().ajax.reload();
                },
                error: function (request, status, error) {
                    let params = {icon: 'error', title: 'Terjadi kesalahan atau koneksi terputus !'}
                    $('#loading').css("display", "none");
                    showAlaret(params);
                }
            });
        });

        $(document).on('click', '.invalid', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
        })

    });
</script>
@endpush
