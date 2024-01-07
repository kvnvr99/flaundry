@extends('layouts.main')
@push('style')
    <style>
        /* Multiple Upload Images */
        .thumbnail {
            /* max-height: 75px; */
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
            width: 164px;
            height: 164px;
            border-radius: 5px;
            object-position: center;
            object-fit: cover;
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
@endpush
@section('content')
    @component('component.form')
        @slot('action', !isset($data['detail']) ? route('expedisi-jemput.store') : route('expedisi-jemput.store'))
        @isset($data['detail'])
            @slot('method', 'POST')
        @else
            @slot('method', 'POST')
        @endisset
        @slot('content')
            <h3>Informasi Penjemputan</h3>

            <div class="form-group mb-3">
                <label class="required">Nama</label>
                <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->expedisi_jemput_id }}"
                    name="id">
                <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->id }}"
                    name="permintaan_laundry_id">
                <input value="{{ !isset($data['detail']) ? old('nama') : old('nama', $data['detail'][0]->name) }}" type="text"
                    name="nama" class="form-control mb-2 @error('nama') is-invalid @enderror" placeholder="nama"
                    autocomplete="off" readonly>
            </div>

            <div class="form-group mb-3">
                <label class="required">Alamat</label>
                <input value="{{ !isset($data['detail']) ? old('alamat') : old('alamat', $data['detail'][0]->alamat) }}"
                    type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror"
                    placeholder="alamat" readonly>
            </div>

            <div class="form-group mb-3">
                <label class="required">Tanggal</label>
                <input value="{{ !isset($data['detail']) ? old('tanggal') : old('tanggal', $data['detail'][0]->tanggal) }}"
                    type="text" name="tanggal" class="form-control active mb-2 @error('tanggal') is-invalid @enderror"
                    placeholder="tanggal" readonly>
            </div>

            <div class="form-group mb-3">
                <label class="required">Waktu</label>
                <input value="{{ !isset($data['detail']) ? old('waktu') : old('waktu', $data['detail'][0]->waktu) }}" type="text"
                    name="waktu" class="form-control active mb-2 @error('waktu') is-invalid @enderror" placeholder="waktu"
                    readonly>
            </div>

            <h3>&nbsp;</h3>
            <h3>Catatan Expedisi</h3>
            <h3>&nbsp;</h3>

            <div class="form-group mb-3">
                <label class="required">Titip Saldo</label>
                <input
                    value="{{ !isset($data['detail']) ? old('titip_saldo') : 'Rp ' . number_format($data['detail'][0]->titip_saldo, 0, ',', '.') }}"
                    type="text" name="titip_saldo" class="form-control active mb-2 @error('titip_saldo') is-invalid @enderror" autocomplete="off"
                    placeholder="titip saldo" id="titip_saldo">
                <input value="{{ !isset($data['detail']) ? old('image') : old('image', $data['detail'][0]->image) }}" type="hidden"
                    name="image" class="form-control active mb-2 @error('image') is-invalid @enderror" placeholder="image">
            </div>

            <div class="form-group mb-3">
                <label class="required">Catatan</label>
                <textarea value="" type="text" name="catatan" class="form-control mb-2">
        {{ !isset($data['detail']) ? old('catatan') : old('catatan', $data['detail'][0]->catatan) }}
        </textarea>
                @if ($errors->has('catatan'))
                    <div class="text-danger"> {{ $errors->first('catatan') }} </div>
                @endif
            </div>

            <div class="form-group mb-3">
                <label>Gambar Cucian</label>
                <!-- <div class="col-9"> -->
                <div class="input-group">
                    <div class="custom-file">
                        <input accept="image/png, image/gif, image/jpeg, image/jpg" multiple name="images[]" type="file"
                            class="custom-file-input" id="images">
                        <label class="custom-file-label" for="images">Upload Beberapa Gambar(Max 10)</label>
                    </div>
                </div>

                <!-- </div> -->
            </div>
            <div id="image-preview"></div>
            @foreach ($images as $val)
                <span class="pip"><img class="thumbnail" src="{{ asset('storage/') . '/' . $val->image }}"><br /><span
                        class="remove deleteImg" data-id="{{ $val->id }}">Remove</span></span>
            @endforeach
            <div id="loader"></div>
            <br><br><br>

            <div class="modal fade" id="modal-member" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Data Member</h4>
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
                                        <th>Alamat</th>
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
        function ambil_member() {
            $('#modal-member').modal('show');
            $("#state-saving-datatable").DataTable().destroy();
            $('#state-saving-datatable tbody').remove();
            $('#state-saving-datatable').DataTable({
                responsive: true,
                processing: true,
                "lengthMenu": [
                    [5, 10],
                    [5, 10]
                ],
                "language": {
                    "lengthMenu": "_MENU_"
                },
                serverSide: true,
                method: "POST",
                scrollX: true,
                ajax: {
                    url: "{!! route('top-up.get-data-member') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    }
                ]
            });

            var datatable = $('#state-saving-datatable').DataTable();

            $('#state-saving-datatable tbody').on('click', 'tr', function() {
                var data = datatable.row(this).data();
                $("#member_id").val(data.id);
                $("#nama").val(data.name);

                $('#modal-member').modal('hide');
            });
        }

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

        $(".deleteImg").click(function(e) {
            let another = this;
            e.preventDefault();
            Swal.fire({
                title: 'Hapus image ini ?',
                text: "Anda tidak akan dapat memulihkan image ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('expedisi-jemput.edit.deleteImg', $data['detail'][0]->id) }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "GET",
                            "id": $(this).data('id'),
                        },
                        success: function() {
                            $(another).parent(".pip").remove();
                            Swal.fire("Berhasil!",
                                "Berhasil dihapus.",
                                "success");
                        }
                    });
                }
            });
        });

        $('#titip_saldo').on('input', function() {
            if (this.value !== '') {
                let formattedValue = formatRupiah(parseRupiah(this.value));
                this.value = formattedValue;
            }
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
    </script>
@endpush
