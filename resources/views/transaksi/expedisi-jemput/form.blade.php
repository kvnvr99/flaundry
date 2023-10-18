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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', !isset($data['detail']) ? route('expedisi-jemput.store') : route('expedisi-jemput.store'))
    @isset ($data['detail'])
        @slot('method','POST')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')
    <h3>Informasi Penjemputan</h3>

    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->expedisi_jemput_id }}" name="id">
        <input type="hidden" value="{{ !isset($data['detail']) ? '' : $data['detail'][0]->id }}" name="permintaan_laundry_id">
        <input value="{{ !isset($data['detail']) ? old('nama') : old('nama', $data['detail'][0]->name) }}" type="text" name="nama" class="form-control mb-2 @error('nama') is-invalid @enderror" placeholder="nama" autocomplete="off" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Alamat</label>
        <input value="{{ !isset($data['detail']) ? old('alamat') : old('alamat', $data['detail'][0]->alamat) }}" type="text" name="alamat" class="form-control active mb-2 @error('alamat') is-invalid @enderror" placeholder="alamat" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Tanggal</label>
        <input value="{{ !isset($data['detail']) ? old('tanggal') : old('tanggal', $data['detail'][0]->tanggal) }}" type="text" name="tanggal" class="form-control active mb-2 @error('tanggal') is-invalid @enderror" placeholder="tanggal" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Waktu</label>
        <input value="{{ !isset($data['detail']) ? old('waktu') : old('waktu', $data['detail'][0]->waktu) }}" type="text" name="waktu" class="form-control active mb-2 @error('waktu') is-invalid @enderror" placeholder="waktu" readonly>
    </div>

    <h3>&nbsp;</h3>
    <h3>Catatan Expedisi</h3>
    <h3>&nbsp;</h3>

    <div class="form-group mb-3">
        <label class="required">Titip Saldo</label>
        <input value="{{ !isset($data['detail']) ? old('titip_saldo') : old('titip_saldo', $data['detail'][0]->titip_saldo) }}" type="text" name="titip_saldo" class="form-control active mb-2 @error('titip_saldo') is-invalid @enderror" placeholder="titip saldo">
        <input value="{{ !isset($data['detail']) ? old('image') : old('image', $data['detail'][0]->image) }}" type="hidden" name="image" class="form-control active mb-2 @error('image') is-invalid @enderror" placeholder="image">
    </div>
    
    <div class="form-group mb-3">
        <label class="required">Catatan</label>
        <textarea value="" type="text" name="catatan" class="form-control mb-2">
        {{ !isset($data['detail']) ? old('catatan') : old('catatan', $data['detail'][0]->catatan) }}
        </textarea>
        @if($errors->has('catatan'))
            <div class="text-danger"> {{ $errors->first('catatan')}} </div>
        @endif
    </div>

    <div class="form-group mb-3">
        <label>Gambar Cucian</label>
        <!-- <div class="col-9"> -->
            <div class="input-group">
                <div class="custom-file">
                    <input accept="image/png, image/gif, image/jpeg, image/jpg" multiple name="images[]" type="file" class="custom-file-input" id="images">
                    <label class="custom-file-label" for="images">Upload Beberapa Gambar(Max 10)</label>
                </div>
            </div>
            
        <!-- </div> -->
    </div>
    <div id="image-preview"></div><br><br><br>

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
    function ambil_member(){
        $('#modal-member').modal('show');
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
                    url: "{!! route('top-up.get-data-member') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address'}
                ]
            });

        var datatable = $('#state-saving-datatable').DataTable();

        $('#state-saving-datatable tbody').on('click', 'tr', function () {
            var data = datatable.row(this).data();
            $("#member_id").val(data.id);
            $("#nama").val(data.name);
            
            $('#modal-member').modal('hide');   
        });           
    }

    if (window.File && window.FileList && window.FileReader) {
        $("#images").on("change", function(e) {
            const files = e.target.files, filesLength = files.length;
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
            for (let i = 0; i < filesLength; i++) {
                let f = files[i]
                let fileReader = new FileReader();
                fileReader.onload = (function(e) {
                    let file = e.target;
                    $("<span class=\"pip\">" +
                        "<img class=\"thumbnail\" src=\"" + e.target.result + "\" title=\"" + files.name + "\"/>" +
                        "</span>").insertAfter("#image-preview");
                    $(".remove").click(function(){
                        $(this).parent(".pip").remove();
                    });
                });
                fileReader.readAsDataURL(f);
            }
        });
    } else {
        Swal.fire( 'Browser Tidak Support !', 'error' )
    }

</script>
@endpush
