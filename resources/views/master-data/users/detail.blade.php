@extends('layouts.main')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@endpush
@section('content')
@component('component.form')
    @slot('action', '#')
    @isset ($data['detail'])
        @slot('method','PUT')
    @else
        @slot('method','POST')
    @endisset
    @slot('content')
    <div class="form-group mb-3">
        <label class="required">Nama</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('name') : old('name', $data['detail']->name) }}" type="text"  class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">Email</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($data['detail']) ? old('email') : old('email', $data['detail']->email) }}" type="text" class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">Roles</label>
        <input disabled style="background-color: #e5e5e5;" value="{{ !isset($user_role) ? '' : $user_role->name }}" type="text" class="form-control mb-2" />
    </div>
    <div class="form-group mb-3">
        <label class="required">QR Login</label>
        @php $accounts = json_encode([ 'email' => $data['detail']->email, 'password' => $data['detail']->qr_code ]); @endphp
        <div class="row mb-2">
            <div class="col-12" id="image-qr">
                {!! QrCode::size(300)->generate($accounts); !!}
            </div>
            <div class="col mt-3">
                <button type="button" style="width: 300px;" id="downloadPNG" class="btn btn-block btn-sm btn-success waves-effect waves-light">
                    <i class="fas fa-cloud-download-alt"></i>
                    Download QR Code
                </button>
            </div>
            <canvas style="display: none;" id="canvas-image-qr"></canvas>
        </div>
    </div>
    @endslot
@endcomponent
@endsection
@push('script')
<script>

function downloadSVGAsPNG(e) {

    let name = `{{ $data['detail']->name }}`;

    const canvas = document.createElement("canvas");
    const svg = document.querySelector('svg');
    const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
    const w = parseInt(svg.getAttribute('width'));
    const h = parseInt(svg.getAttribute('height'));
    const img_to_download = document.createElement('img');
    img_to_download.src = 'data:image/svg+xml;base64,' + base64doc;
    console.log(w, h);
    img_to_download.onload = function () {
        console.log('img loaded');
        canvas.setAttribute('width', w);
        canvas.setAttribute('height', h);
        const context = canvas.getContext("2d");
        //context.clearRect(0, 0, w, h);
        context.drawImage(img_to_download,0,0,w,h);
        const dataURL = canvas.toDataURL('image/png');
        if (window.navigator.msSaveBlob) {
            window.navigator.msSaveBlob(canvas.msToBlob(), "download.png");
            e.preventDefault();
        } else {
            const a = document.createElement('a');
            const my_evt = new MouseEvent('click');
            a.download = name+'.png';
            a.href = dataURL;
            a.dispatchEvent(my_evt);
        }
        //canvas.parentNode.removeChild(canvas);
    }  
}

const downloadPNG = document.querySelector('#downloadPNG');
downloadPNG.addEventListener('click', downloadSVGAsPNG);

</script>
@endpush
