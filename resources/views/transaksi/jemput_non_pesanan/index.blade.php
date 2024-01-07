@extends('layouts.main')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
    <style>
        /* Multiple Upload Images */
        .thumbnail {
            /* max-height: 75px; */
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
            width: 164px;
            height: auto;
            border-radius: 5px;
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
                                <div class="row mb-3 p-1">
                                    <div class="col-6">
                                        <h4 class="header-title">Jemput Non Pesanan</h4>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a href="{{ route('jemput_non_pesanan.history') }}"
                                            class="btn btn-primary">History</a>
                                    </div>
                                </div>
                                <div id="basicwizard">
                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#barang_masuk" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2"
                                                style="border: 1px solid #c9c1c1;">
                                                <i class="fas fa-sign-in-alt"></i>
                                                <span class="d-none d-sm-inline">Barang Masuk</span>
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item">
                                        <a href="#barang_keluar" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2" style="border: 1px solid #c9c1c1;">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span class="d-none d-sm-inline">Barang Keluar</span>
                                        </a>
                                    </li> --}}
                                    </ul>
                                    <div class="tab-content b-0 mb-0 pt-0">
                                        <div class="tab-pane" id="barang_masuk">
                                            <form method="POST" id="form-transaksi" enctype="multipart/form-data"
                                                action="{{ route('jemput_non_pesanan.store') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row mb-3 pilih_member">
                                                            <label class="col-md-3 col-form-label"
                                                                for="triggerCorporate">Pilih
                                                                Corporate</label>
                                                            <div class="col-md-4">
                                                                <button type="button" id="triggerCorporate"
                                                                    class="btn btn-secondary" data-toggle="modal"
                                                                    data-target="#modal-corporate"><i
                                                                        class="fas fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="nama">Nama</label>
                                                            <div class="col-md-9">
                                                                <input readonly type="text"
                                                                    class="form-control data-pelanggan first-outlet"
                                                                    id="nama" name="nama" readonly>
                                                                <input type="hidden" class="form-control" id="corporate_id"
                                                                    name="corporate_id">
                                                                <input type="hidden" class="form-control"
                                                                    id="permintaan_laundry_id" name="permintaan_laundry_id">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-3 col-form-label"
                                                                for="alamat">Alamat</label>
                                                            <div class="col-md-9">
                                                                <input readonly type="text"
                                                                    class="form-control data-pelanggan first-outlet"
                                                                    id="alamat" name="alamat">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-3 col-form-label" for="no_handphone">No
                                                                Handphone</label>
                                                            <div class="col-md-9">
                                                                <input readonly type="text"
                                                                    class="form-control data-pelanggan first-outlet"
                                                                    id="no_handphone" name="no_handphone">
                                                            </div>
                                                        </div>
                                                        {{-- <div class="form-group row mb-3">
                                                        <label class="col-md-3 col-form-label" for="parfume">Parfume</label>
                                                        <div class="col-md-9">
                                                            <input readonly type="text" class="form-control first-outlet" id="parfume" name="parfume">
                                                        </div>
                                                    </div> --}}
                                                        <div class="form-group row mb-3">
                                                            <label class="col-md-3 col-form-label">Parfume</label>
                                                            <div class="col-md-9">
                                                                <div class="selectize-control multi">
                                                                    <div style="height: auto;"
                                                                        class="selectize-input items not-full has-options has-items">
                                                                        @foreach ($parfumes as $parfume)
                                                                            <div class="select-input item-parfume"
                                                                                data-value="{{ $parfume->id }}">
                                                                                {{ $parfume->nama }}</div>
                                                                        @endforeach
                                                                        <input type="text" autocomplete="off"
                                                                            tabindex="" id="selectize-parfume-selectized"
                                                                            style="width: 4px; opacity: 0; position: absolute; left: -10000px;">
                                                                    </div>
                                                                    <div class="selectize-dropdown multi"
                                                                        style="display: none;">
                                                                        <div class="selectize-dropdown-content"></div>
                                                                    </div>
                                                                </div>
                                                                <input type="text" name="parfume" id="selectize-parfume"
                                                                    tabindex="-1" class="selectized"
                                                                    style="display: none;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="example-textarea">Catatan</label>
                                                            <textarea class="form-control" name="note" id="example-textarea" rows="5"></textarea>
                                                        </div>
                                                        <div class="form-group row mb-3">
                                                            <label class="col-3 col-form-label">Gambar Cucian</label>
                                                            <div class="col-9">
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input
                                                                            accept="image/png, image/gif, image/jpeg, image/jpg"
                                                                            multiple name="images[]" type="file"
                                                                            class="custom-file-input" id="images">
                                                                        <label class="custom-file-label"
                                                                            for="images">Upload Beberapa Gambar(Max
                                                                            10)</label>
                                                                    </div>
                                                                </div>
                                                                <div id="image-preview"></div>
                                                                <div id="loader"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive-md">
                                                            <table
                                                                class="table activate-select dt-responsive nowrap w-100 table-bordered"
                                                                id="table-data-layanan">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center" width="15%">Nama
                                                                            Layanan</th>
                                                                        <th class="text-center" width="15%">Harga
                                                                            Satuan</th>
                                                                        <th class="text-center">Quantity</th>
                                                                        <th class="text-center">Quantity Return
                                                                        </th>
                                                                        <th class="text-center" width="15%">Total</th>
                                                                        <th class="text-center action-buton"
                                                                            width="4%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                            <table class="table activate-select dt-responsive nowrap w-100"
                                                                id="table-data-layanan-subtotal">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="text-right" width="15%">Sub Total
                                                                        </th>
                                                                        <th class="text-right" width="15%">Rp. <span
                                                                                class="sub_layanan_harga_satuan">0</span>
                                                                        </th>
                                                                        <th class="text-right"><span
                                                                                class="sub_layanan_qty_satuan">0</span>
                                                                        </th>
                                                                        <th class="text-right"></th>
                                                                        <th class="text-right"><span
                                                                                class="sub_special_teatment_qty_satuan">0</span>
                                                                        </th>
                                                                        <th class="text-right" width="15%">Rp. <span
                                                                                class="sub_all_qty_harga">0</span></th>
                                                                        <th class="text-right action-buton"
                                                                            width="4%"></th>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="float: right;" class="row">
                                                    <div class="col-md-6">
                                                        <button type="button"
                                                            class="btn btn-dark btn-square triggerLayanan">Tambah
                                                            Layanan</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>
                                                <br>
                                                <div style="float: right;" class="row mt-5">
                                                    <ul class="list-inline wizard mb-0">
                                                        <li class="next list-inline-item float-right">
                                                            <button type="submit" class="btn btn-secondary">Cetak
                                                                Struk</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </form>
                                        </div>
                                        {{-- <div class="tab-pane" id="barang_keluar">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-3 col-form-label" for="name"> First name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="name" name="name" class="form-control" value="Francis">
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-3 col-form-label" for="surname"> Last name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="surname" name="surname" class="form-control" value="Brinkman">
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-3 col-form-label" for="email">Email</label>
                                                    <div class="col-md-9">
                                                        <input type="email" id="email" name="email" class="form-control" value="cory1979@hotmail.com">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="template-layanan-selected" style="display: none;">
        <tr class="template-layanan-selected-list" childidx="0" style="position: relative;">
            <td class="text-center no-padding" width="15%">
                <span class="show-layanan layanan_nama_label" style="cursor: pointer;">
                    Pilih Layanan
                    <i class="fas fa-search"></i>
                </span>
                <input data-toggle="modal" require readonly class="form-control text-center show-layanan layanan_nama"
                    type="hidden" name="layanan[0][nama]" autocomplete="off" />
                <input data-toggle="modal" readonly required class="form-control text-center show-layanan layanan_id"
                    type="hidden" name="layanan[0][id]" autocomplete="off" />
            </td>
            <td class="text-right no-padding" width="15%">
                <span class="layanan_harga_label">
                </span>
                <input data-toggle="modal" readonly class="form-control text-right show-layanan layanan_harga"
                    type="hidden" name="layanan[0][harga]" autocomplete="off" />
                <input data-toggle="modal" readonly class="form-control text-right show-layanan kode_layanan"
                    type="hidden" name="layanan[0][kode_layanan]" autocomplete="off" />
            </td>
            <td class="text-right no-padding">
                <input class="form-control text-right no-padding layanan_qty_satuan" required readonly
                    style="background-color: #f5f5f5;" maxlength="9" type="text" name="layanan[0][qty_satuan]"
                    autocomplete="off" onkeypress="return isNumber(event)" />
            </td>
            <td class="text-right no-padding">
                <input class="form-control text-right no-padding layanan_qty_special_treatment" readonly
                    style="background-color: #f5f5f5;" type="text" maxlength="9"
                    name="layanan[0][qty_special_treatment]" autocomplete="off" onkeypress="return isNumber(event)" />
            </td>
            <input class="form-control text-right no-padding layanan_harga_special_treatment" readonly
                style="background-color: #f5f5f5;" value="25000" type="hidden" maxlength="9"
                name="layanan[0][harga_special_treatment]" autocomplete="off" onkeypress="return isNumber(event)" />
            <td class="text-right" width="15%">
                <span class="layanan_total_label">
                </span>
                <input data-toggle="modal" readonly class="form-control text-right show-layanan layanan_total"
                    type="hidden" name="layanan[0][total]" autocomplete="off" />
            </td>
            <td class="action-buton" width="4%">
                <a class='btn btn-sm btn-danger removelayanan' type='button'><i class="fas fa-trash-alt"></i></a>
                <input class="data-id" type="hidden" autocomplete="off" />
            </td>
        </tr>
    </table>
    <div id="modal-corporate" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">List Corporate</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id="state-saving-datatable" style="cursor:pointer"
                                        class="table activate-select dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="45%">Nama</th>
                                                <th width="44%">No Telepon</th>
                                                <th width="5%">Pilih</th>
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
    <div id="modal-layanan" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabelLayanan" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabelLayanan">List Layanan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="index_row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id="state-saving-datatable-layanan" style="cursor:pointer"
                                        class="table activate-select dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="44%">Kode</th>
                                                <th width="45%">Nama</th>
                                                <th width="45%">Harga</th>
                                                <th width="5%">Pilih</th>
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

        $(document).ready(function() {

            $('.item').click(function(e) {
                e.preventDefault();
                let value = $(this).data('value');
                $('.item').not(this).each(function() {
                    $(this).css("background-color", "#edeff1");
                });
                $(this).css("background-color", "#6c757d");
                $('#selectize-tags').val(value);
            });

            $('.item-parfume').click(function(e) {
                e.preventDefault();
                let value = $(this).data('value');
                $('.item-parfume').not(this).each(function() {
                    $(this).css("background-color", "#edeff1");
                });
                $(this).css("background-color", "#6c757d");
                $('#selectize-parfume').val(value);
                // $(".first-parfume").prop('readonly', false);
            });

            $('.item-pembayaran').click(function(e) {
                e.preventDefault();
                let value = $(this).data('value');
                $('.item-pembayaran').not(this).each(function() {
                    $(this).css("background-color", "#edeff1");
                });
                $(this).css("background-color", "#6c757d");
                $('#selectize-pembayaran').val(value);
                if (value == 'member') {
                    $('.pilih_member').css("display", "flex");
                    $(".data-pembayaran").prop('readonly', true);
                    $(".data-pembayaran").css('background-color', '#cfcece');
                    $(".data-pembayaran").css('cursor', 'no-drop');
                } else {
                    $('.pilih_member').css("display", "none");
                    $(".data-pembayaran").prop('readonly', false);
                    $(".data-pembayaran").css('background-color', '#fff');
                    $(".data-pembayaran").css('cursor', 'auto');
                }
            });

            $('#triggerCorporate').click(function(e) {
                e.preventDefault();
                $('#state-saving-datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    method: "Get",
                    scrollX: true,
                    bDestroy: true,
                    ajax: {
                        url: "{!! route('user_corporate.getData') !!}",
                        type: "Get",
                        dataType: "JSON"
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            mRender: function(data, type, row, meta) {
                                if (row.phone == '' || row.phone == 0) {
                                    return '-';
                                } else {
                                    let phone = '0' + row.phone;
                                    return row.phone;
                                }
                            }
                        },
                        {
                            mRender: function(data, type, row, meta) {
                                let action_button = '<div>';
                                action_button +=
                                    '<input type="radio" name="radio" class="radio1" value="' +
                                    row.user.name + '" data-id="' + row.id +
                                    '" data-phone="' + row.phone + '" data-address="' + row
                                    .address + '">';
                                action_button += ' </div>';
                                return action_button;
                            }
                        },
                    ]
                });
            });

            var member_table = $('#state-saving-datatable').DataTable();

            $('#state-saving-datatable tbody').on('click', 'tr', function() {
                var row = $(this);
                row.find('.input[type="radio"]').attr('checked', 'checked');
                let id = row.find('input[type="radio"]').data('id');
                let name = row.find('input[type="radio"]').val();
                let phone = row.find('input[type="radio"]').data('phone');
                let address = row.find('input[type="radio"]').data('address');
                $('#nama').val(name)
                $('#no_handphone').val(phone)
                $('#alamat').val(address)
                $('#corporate_id').val(id)
                $('#modal-corporate').modal('hide');
            });

            function handleFileInputChange() {
                const newInput = this; // 'this' mengacu pada elemen file input yang dipicu oleh perubahan

                // Mendapatkan file yang baru dipilih
                const newFiles = newInput.files;

                // Lakukan sesuatu dengan file yang baru dipilih
                for (let i = 0; i < newFiles.length; i++) {
                    const newFile = newFiles[i];

                    // Lakukan sesuatu dengan setiap file, misalnya, tampilkan informasi di konsol
                    console.log(`File Baru: ${newFile.name}, Tipe: ${newFile.type}, Ukuran: ${newFile.size} bytes`);
                }

                // Anda dapat menambahkan logika lain sesuai kebutuhan Anda di sini
            }

            if (window.File && window.FileList && window.FileReader) {
                let filesArray = [];
                const images = document.getElementById("images");

                images.addEventListener("change", function() {
                    const files = this.files;
                    const filesLength = files.length;
                    if (filesLength > 10) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'error',
                            title: 'Maksimal 10 Photo'
                        });
                        $('#images').val('');
                        return false;
                    }

                    // Loop through all selected files
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const imageType = /^image\//;

                        if (!imageType.test(file.type)) {
                            continue;
                        }
                        let currentInput = $('#images')[0];

                        // Show loader here
                        showLoader();

                        let fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            let loadedFile = e.target; // Use a different variable name here
                            let preview = $("<span class=\"pip\">" +
                                "<img class=\"thumbnail\" src=\"" + e.target.result +
                                "\" title=\"" + files[i].name + "\"/>" +
                                "<br/><span class=\"remove\">Remove</span>" +
                                "</span>").insertAfter("#image-preview");

                            console.log(filesArray);
                            $(".remove", preview).click(function() {
                                Swal.fire({
                                    title: 'Hapus image ini ?',
                                    text: "Image ini akan dihapus!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Hapus!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $(this).parent(".pip").remove();
                                        // Remove the image when the "Remove" button is clicked
                                        const fileIndex = filesArray.indexOf(file);
                                        if (fileIndex !== -1) {
                                            filesArray.splice(fileIndex, 1);
                                            handleChange();
                                        }
                                    }
                                });
                            });

                            // Hide loader after successful loading
                            hideLoader();
                        });
                        fileReader.readAsDataURL(file);
                        filesArray.push(file);
                        handleChange();
                    }
                });

                function showLoader() {
                    // Add code to show your loader (e.g., display a loading spinner)
                    $("#loader").html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                }

                function hideLoader() {
                    // Add code to hide your loader
                    $("#loader").empty();
                }

                function handleChange() {
                    // Create a new DataTransfer object
                    const newFilesList = new DataTransfer();

                    // Add files to the DataTransfer object
                    filesArray.forEach(file => newFilesList.items.add(file));

                    // Set the new value for the file input
                    images.files = newFilesList.files;

                    // Add event listener to the new file input
                    images.addEventListener("change", handleFileInputChange);
                }
            } else {
                Swal.fire('Browser Tidak Support !', 'error');
            }

            $(document).on('click', '.show-layanan', function(e) {
                e.preventDefault();
                let kategori_layanan = $('#selectize-tags').val();
                let pelanggan = $('#selectize-pelanggan').val();
                let index_row = $(this).parent().parent().attr('childidx');
                let table = $('#state-saving-datatable-layanan').DataTable();
                table.destroy();
                $('#modal-layanan').modal('show');
                $('.index_row').val(index_row)
                $('#state-saving-datatable-layanan').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    method: "POST",
                    scrollX: true,
                    // bDestroy: true,
                    ajax: {
                        url: "{!! route('jemput_non_pesanan.get-data-layanan') !!}",
                        type: "POST",
                        dataType: "JSON",
                        data: ({
                            kategori: kategori_layanan,
                            member: pelanggan
                        })
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            mRender: function(data, type, row, meta) {
                                if (row.harga == '' || row.harga == 0) {
                                    return '-';
                                } else {
                                    let harga = 'Rp. ' + row.harga;
                                    return harga;
                                }
                            }
                        },
                        {
                            mRender: function(data, type, row, meta) {
                                let action_button = '<div>';
                                action_button +=
                                    '<input type="radio" name="radio" class="radio1" value="' +
                                    row.kode + '" data-id="' + row.id + '" data-nama="' +
                                    row.nama + '" data-harga="' + row.harga +
                                    '"  data-harga_member="' + row.harga_member +
                                    '" data-jenis_item="' + row.jenis_item + '">';
                                action_button += ' </div>';
                                return action_button;
                            }
                        },
                    ]
                });

            });

            $('#state-saving-datatable-layanan tbody').on('click', 'tr', function() {
                var row = $(this);
                row.find('.input[type="radio"]').attr('checked', 'checked');
                let id = row.find('input[type="radio"]').data('id');
                let kode = row.find('input[type="radio"]').val();
                let nama = row.find('input[type="radio"]').data('nama');
                let harga = row.find('input[type="radio"]').data('harga');
                let jenis_item = row.find('input[type="radio"]').data('jenis_item');
                let index_row = $('.index_row').val();
                let this_row = $(document).find(`.layanan-selected-list[childidx=${index_row}]`);
                this_row.find(".form-control").prop('readonly', false);
                this_row.find(".form-control").css('background-color', '#FFF');
                this_row.find('.layanan_nama_label').html(nama);
                let harga_label = numberFormater(parseInt(harga));
                this_row.find('.layanan_harga_label').html('Rp. ' + harga_label);
                this_row.find('.layanan_id').val(id);
                this_row.find('.layanan_nama').val(nama);
                this_row.find('.layanan_harga').val(harga);
                this_row.find('.kode_layanan').val(kode);

                let sum = 0;
                $(document).find(".layanan_harga").each(function() {
                    sum += +$(this).val();
                });
                sum = numberFormater(sum);
                $(".sub_layanan_harga_satuan").html(sum);

                let sum_total = 0;
                $(document).find(".layanan_total").each(function() {
                    sum_total += +$(this).val();
                });
                sum_total = numberFormater(sum_total);
                $(".sub_all_qty_harga").html(sum_total);

                let table = $('#state-saving-datatable-layanan').DataTable();
                table.destroy();
                $('#modal-layanan').modal('hide');
            });

            $(document).on('keyup', '.layanan_qty_kg', function(e) {

                let sum = 0.0;
                $(document).find(".layanan_qty_kg").each(function() {
                    sum += +$(this).val();
                });
                $(".sub_layanan_qty_kg").html(sum);
                console.log(sum);
            });

            // $(document).on('keyup', '.layanan_qty_satuan, .layanan_qty_special_treatment', function(e) {
            //     let formattedValue = formatNumber(parseNumber(this.value));
            //     this.value = formattedValue;

            //     if (/^0/.test(this.value)) {
            //         this.value = this.value.replace(/^0/, "");
            //     }

            //     let this_row = $(this).closest('tr');
            //     let harga = this_row.find('.layanan_harga').val() || 0;
            //     let quantity_satuan = parseNumber(this_row.find('.layanan_qty_satuan').val()) || 0;
            //     let quantity_special_treatment = parseNumber(this_row.find('.layanan_qty_special_treatment')
            //         .val()) || 0;

            //     let harga_satuan = parseInt(harga) * quantity_satuan;
            //     let total_special_treatment = parseInt(quantity_special_treatment) * parseInt(this_row.find(
            //         '.layanan_harga_special_treatment').val()) || 0;

            //     let sub_total_harga_this_row = harga_satuan + total_special_treatment;
            //     let sub_total_harga_this_row_label = numberFormater(sub_total_harga_this_row);

            //     this_row.find('.layanan_total_label').html('Rp. ' + sub_total_harga_this_row_label);
            //     this_row.find('.layanan_total').val(sub_total_harga_this_row);

            //     let sum_qty_satuan = 0;
            //     let sum_qty_special_treatment = 0;
            //     let sum_total = 0;

            //     $('#table-data-layanan').find(".layanan_qty_satuan").each(function() {
            //         if ($(this).val() !== '') {
            //             sum_qty_satuan += +parseNumber($(this).val());
            //         }
            //     });

            //     $('#table-data-layanan').find(".layanan_qty_special_treatment").each(function() {
            //         if ($(this).val() !== '') {
            //             sum_qty_special_treatment += +parseNumber($(this).val());
            //         }
            //     });

            //     $(".sub_layanan_qty_satuan").html(formatNumber(sum_qty_satuan));
            //     $(".sub_special_teatment_qty_satuan").html(formatNumber(sum_qty_special_treatment));

            //     $('#table-data-layanan').find(".layanan_total").each(function() {
            //         if ($(this).val() !== '') {
            //             sum_total += +parseNumber($(this).val());
            //         }
            //     });

            //     sum_total = numberFormater(sum_total);
            //     $(".sub_all_qty_harga").html(sum_total);
            // });

            $(document).on('keyup', '.layanan_qty_satuan', function(e) {
                let formattedValue = formatNumber(parseNumber(this
                    .value)); // Format and then parse the value
                this.value = formattedValue; // Update the input value

                if (/^0/.test(this.value)) {
                    this.value = this.value.replace(/^0/, "");
                }

                let this_row = $(this).parent().parent();
                let harga = this_row.find('.layanan_harga').val() || 0;
                let layanan_qty_special_treatment = this_row.find('.layanan_qty_special_treatment').val() ||
                    0;
                let layanan_harga_special_treatment = this_row.find('.layanan_harga_special_treatment')
                    .val() || 0;
                let total_special_treatment = parseInt(layanan_qty_special_treatment) * parseInt(
                    layanan_harga_special_treatment) || 0;

                let quantity = parseNumber(formattedValue); // Use the parsed value for calculations

                if (layanan_harga_special_treatment >= 0 && quantity >= 0 &&
                    layanan_qty_special_treatment >= 0) {
                    let total_harga = parseInt(harga) * quantity;
                    let sub_total_harga_this_row = total_harga + parseInt(total_special_treatment) || 0;

                    let sub_total_harga_this_row_label = numberFormater(parseInt(sub_total_harga_this_row));
                    this_row.find('.layanan_total_label').html('Rp. ' + sub_total_harga_this_row_label);
                    this_row.find('.layanan_total').val(sub_total_harga_this_row);

                    let sum = 0;
                    $('#table-data-layanan').find(".layanan_qty_satuan").each(function() {
                        if ($(this).val() !== '') {
                            sum += +parseNumber($(this).val());
                        }
                    });
                    $(".sub_layanan_qty_satuan").html(formatNumber(sum));

                    let sum_total = 0;
                    $('#table-data-layanan').find(".layanan_total").each(function() {
                        if ($(this).val() !== '') {
                            sum_total += +parseNumber($(this).val());
                        }
                    });
                    sum_total = numberFormater(sum_total);
                    $(".sub_all_qty_harga").html(sum_total);
                }
            });


            $(document).on('keyup', '.layanan_qty_special_treatment', function(e) {
                let formattedValue = formatNumber(parseNumber(this
                    .value)); // Format and then parse the value
                this.value = formattedValue; // Update the input value
                if (/^0/.test(this.value)) {
                    this.value = this.value.replace(/^0/, "")
                }

                let sum = 0;
                $('#table-data-layanan').find(".layanan_qty_special_treatment").each(function() {
                    if ($(this).val() !== '') {
                        sum += +parseNumber($(this).val());
                    }
                });
                $(".sub_special_teatment_qty_satuan").html(formatNumber(sum));
            });


            // $(document).on('keyup', '.layanan_harga_special_treatment', function (e) {
            //     if (/^0/.test(this.value)) {
            //         this.value = this.value.replace(/^0/, "")
            //     }
            //     let this_row = $(this).parent().parent();

            //     let harga = this_row.find('.layanan_harga').val() || 0;
            //     let quantity = this_row.find('.layanan_qty_satuan').val() || 0;

            //     let layanan_qty_special_treatment = this_row.find('.layanan_qty_special_treatment').val() || 0;
            //     let layanan_harga_special_treatment = this_row.find('.layanan_harga_special_treatment').val() || 0;
            //     let total_special_treatment = parseInt(layanan_qty_special_treatment) * parseInt(layanan_harga_special_treatment) || 0;

            //     if (layanan_harga_special_treatment >= 0 && quantity >= 0 && layanan_qty_special_treatment >= 0) {
            //         let total_harga = parseInt(harga) * parseInt(quantity);
            //         let sub_total_harga_this_row = parseInt(total_harga) + parseInt(total_special_treatment) || 0;

            //         let sub_total_harga_this_row_label = numberFormater(parseInt(sub_total_harga_this_row));
            //         this_row.find('.layanan_total_label').html('Rp. '+sub_total_harga_this_row_label);
            //         this_row.find('.layanan_total').val(sub_total_harga_this_row);

            //         let sum = 0;
            //         $(document).find(".layanan_harga_special_treatment").each(function(){
            //             sum += +$(this).val();
            //         });

            //         let sum_total = 0;
            //         $(document).find(".layanan_total").each(function(){
            //             sum_total += +$(this).val();
            //         });
            //         sum_total = numberFormater(sum_total);
            //         $(".sub_all_qty_harga").html(sum_total);

            //         sum = numberFormater(sum);
            //         $(".sub_special_teatment_qty_harga").html(sum);
            //     }
            // });

            $('.triggerLayanan').on('click', function() {
                getLayanan();
            });

            function getLayanan() {
                var tr_clone = $(".template-layanan-selected-list").clone();
                tr_clone.removeClass('template-layanan-selected-list');
                tr_clone.addClass('layanan-selected-list');
                $("#table-data-layanan").append(tr_clone);
                renderDataLayanan();
            }

            function renderDataLayanan() {
                var index = 0;
                $(".layanan-selected-list").each(function() {
                    var another = this;
                    search_index = $(this).attr("childidx");
                    $(this).find('input,select').each(function() {
                        this.name = this.name.replace('[' + search_index + ']', '[' + index + ']');
                        $(another).attr("childidx", index);
                    });
                    $(this).find('span').each(function() {
                        this.id = this.id.replace('[' + search_index + ']', '[' + index + ']');
                        $(another).attr("childidx", index);
                    });
                    index++;
                });
            }

            $(document).on('click', '.removelayanan', function(e) {
                e.preventDefault();
                let this_row = $(this).parent().parent();
                Swal.fire({
                    title: 'Hapus data ini ?',
                    text: "Anda tidak akan dapat memulihkan data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this_row.remove();
                        Swal.fire("Berhasil!", "Berhasil dihapus.", "success");
                        renderDataLayanan();
                    }
                })
            });

            $("#form-transaksi").validate({
                rules: {
                    outlet: {
                        required: true
                    },
                    kategori: {
                        required: true
                    },
                    pelanggan: {
                        required: true
                    },
                    pembayaran: {
                        required: true
                    },
                    bayar: {
                        required: true
                    },
                    nama: {
                        required: true
                    },
                    alamat: {
                        required: true
                    }
                },
                messages: {
                    outlet: {
                        required: "Pilih outlet"
                    },
                    kategori: {
                        required: "Pilih kategori"
                    },
                    pelanggan: {
                        required: "Pilih kategori pelanggan"
                    },
                    pembayaran: {
                        required: "Pilih metode pembayaran"
                    },
                    bayar: {
                        required: "Masukan jumlah pembayaran"
                    },
                    nama: {
                        required: "Masukan nama atau pilih member"
                    },
                    alamat: {
                        required: "Masukan alamat atau pilih member"
                    }
                },
                ignore: "",
                submitHandler: function() {
                    var formData = new FormData($('#form-transaksi')[0]);
                    $('#loading').css("display", "block");
                    $.ajax({
                        type: "POST",
                        url: `{{ route('jemput_non_pesanan.store') }}`,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status == true) {
                                let print_url = `{{ url('jemput-non-pesanan/print') }}`;
                                let redirect_print_url = print_url + '/' + response
                                    .kode_transaksi;
                                // window.open(print_url,'nama window','width=459,height=1000,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=no,copyhistory=no');
                                window.open(redirect_print_url, '_blank');
                                setTimeout(function() {
                                    window.location.reload(true);
                                }, 500);
                            } else {
                                if (response.err == 'empty_layanan') {
                                    let params = {
                                        icon: 'warning',
                                        title: response.msg
                                    }
                                    showAlaret(params);
                                } else {
                                    let params = {
                                        icon: 'error',
                                        title: 'Terjadi kesalahan atau koneksi terputus !'
                                    }
                                    showAlaret(params);
                                }
                            }
                            $('#loading').css("display", "none");
                        },
                        error: function(request, status, error) {
                            let params = {
                                icon: 'error',
                                title: 'Terjadi kesalahan atau koneksi terputus !'
                            }
                            $('#loading').css("display", "none");
                            showAlaret(params);
                        }
                    });
                    return false;
                }
            });

            $('#bayar').on('keyup', function() {
                let formattedValue = formatRupiah(parseRupiah($(this).val()));
                this.value = formattedValue;
            });

            function formatRupiah(amount) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }

            function parseRupiah(rupiahString) {
                // Remove currency symbol, separators, and parse as integer
                const parsedValue = parseInt(rupiahString.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }

            function formatNumber(number) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return Number(number).toLocaleString('id-ID');
            }

            function parseNumber(number) {
                // Remove currency symbol, separators, and parse as integer
                // Replace dot only if it exists in the number
                const parsedValue = parseInt(number.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }
        });
    </script>
@endpush
