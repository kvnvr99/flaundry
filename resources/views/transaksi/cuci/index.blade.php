@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<style>
    /* Multiple Upload Images */
    .thumbnail {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
    }
    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
    }
    .remove {
        display: block;
        /* background: #444;
        border: 1px solid black;
        color: white; */
        text-align: center;
        cursor: pointer;
        /* border-radius: 5px; */
    }
    .remove:hover {
        background: rgb(234, 1, 1);
        color: black;
    }
    /* Multiple Upload Images */

    /* Form Error */
    .error {
        color: #990707;
        font-size: 0.8rem;
    }
    /* Form Error */
    .no-padding {
        padding: 0 !important;
    }
</style>
@endpush
@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            @include('component.breadcrumb')
            <div class="row">
                <div class="col-12">
                    {{-- <a href="{{ route('harga.create') }}" class="btn btn-primary waves-effect waves-light mb-3">Add</a> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3"> Cuci</h4>
                            <div id="basicwizard">
                                <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4">
                                    <li class="nav-item">
                                        <a href="#barang_masuk" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 barang_masuk" style="border: 1px solid #c9c1c1;">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span class="d-none d-sm-inline">Barang Masuk</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#barang_keluar" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 barang_keluar" style="border: 1px solid #c9c1c1;">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span class="d-none d-sm-inline">Barang Proses</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content b-0 mb-0 pt-0">
                                    <div class="tab-pane" id="barang_masuk">
                                        <div class="row">
                                            <div class="col-12 col-md-6 text-center">
                                                <div class="form-group row mb-3">
                                                    <div class="col-md-12">
                                                        <div style="width: 500px; display: none;" id="reader"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <div class="col-md-12">
                                                        <span>ATAU</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control kode_transaksi" placeholder="Kode Transaksi" id="kode_transaksi" name="kode_transaksi">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="barang_keluar">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
<script src="{{ asset('assets/js/scan-qr-code.js') }}"></script>
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

    let kodeTransaksi = document.getElementById('kode_transaksi');

    kodeTransaksi.onkeyup = function(){
        this.value = this.value.toUpperCase();
        let this_val = this.value;
        console.log(this_val);
        if (this_val.length == 11) {
            getRequest(this_val)
        }
    }

    $(document).ready(function () {

        $('#reader').css("display", "block");
        $('#reader').css("width", "auto");
        $('#reader').css("border", "none");
        $('#reader__scan_region').html('<br> <i class="fas fa-qrcode fa-10x"></i>');
        $('#reader__dashboard_section_swaplink').css("display", "none");

        let datatable = $('#state-saving-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            method: "POST",
            scrollX: true,
            ajax: {
                url: "{!! route('cuci.get-data') !!}",
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
                url: `{{ route('cuci.store') }}`,
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

    function getRequest(kode_transaksi) {
        $('#loading').css('display', 'block');
        $.ajax({
            type: "POST",
            url: `{!! route('cuci.request') !!}`,
            data: ({
                kode_transaksi: kode_transaksi
            }),
            dataType: "JSON",
            success: function (response) {
                if (response.status == true) {
                    $('#loading').css('display', 'none');
                    let params = {icon: 'success', title: response.data}
                    showAlaret(params);
                    $('.barang_keluar').click();
                    $('#state-saving-datatable').DataTable().ajax.reload();
                } else {
                    $('#loading').css('display', 'none');
                    let params = {icon: 'warning', title: response.msg}
                    showAlaret(params);
                }
            },
            error: function (response, request, status, error) {
                let params = {icon: 'error', title: 'Terjadi kesalahan atau koneksi terputus !'}
                $('#loading').css("display", "none");
                showAlaret(params);
            }
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        getRequest(decodedText);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 500 }
    );
    html5QrcodeScanner.render(onScanSuccess);

</script>

@endpush
