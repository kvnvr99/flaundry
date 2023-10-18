@extends('layouts.main_user')
@section('content')
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <!-- <form class="form-inline">
                                <div class="form-group">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control border" id="dash-daterange">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-blue border-blue text-white">
                                                <i class="mdi mdi-calendar-range"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-2">
                                    <i class="mdi mdi-autorenew"></i>
                                </a>
                                <a href="javascript: void(0);" class="btn btn-blue btn-sm ml-1">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form> -->
                        </div>
                        <h4 class="page-title">Halaman Utama</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">

                @if(isset($data['transaksi_terakhir']->kepuasan_pelanggan))

                @if($data['transaksi_terakhir']->kepuasan_pelanggan =='netral' and isset($data['transaksi_terakhir']->deliver_at))

                <div class="col-md-12 col-xl-3">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/comment.png" style="height:150px;" alt="user-img" />
                                    <h4>Menyukai layanan dari kami?</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <a onClick="like({{ $data['transaksi_terakhir']->id }})" class="btn btn-lg btn-outline-success waves-effect waves-light" title="Like">
                                        <i class="fe-thumbs-up"></i>
                                    </a>
                                    <a onClick="dislike({{ $data['transaksi_terakhir']->id }})" class="btn btn-lg btn-outline-danger waves-effect waves-light" title="Dislike">
                                        <i class="fe-thumbs-down"></i>
                                    </a>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @endif

                @endif

                <a class="col-md-6 col-xl-2">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/money.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Saldo yang dimiliki</p>
                                    <h4 class="mt-1">Rp <span data-plugin="counterup"> {{ number_format($data['saldo'], 0) }}</span></h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and is_null($data['transaksi_terakhir']->qc_id))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/pickup.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses Jemput</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->qc_id > 0 and is_null($data['transaksi_terakhir']->cuci_id))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/qc.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses QC</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->cuci_id > 0 and is_null($data['transaksi_terakhir']->pengeringan_id))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/cuci.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses Cuci</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->pengeringan_id > 0 and is_null($data['transaksi_terakhir']->setrika_id))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/kering.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses Pengeringan</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->setrika_id > 0 and is_null($data['transaksi_terakhir']->deliver_by))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/setrika.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses Setrika</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->deliver_by > 0 and is_null($data['transaksi_terakhir']->deliver_at))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/antar.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Dalam Proses Antar</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                @if(isset($data['transaksi_terakhir']) and isset($data['transaksi_terakhir']->deliver_at))
                <div class="col-md-6 col-xl-3">
                @else
                <div class="col-md-6 col-xl-3"  style="display: none;">
                @endif
                    <div class="widget-rounded-circle card-box" data-toggle="modal" data-target="#right-modal">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/diterima.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-1 text-truncate">Status Transaksi</p>
                                    <h4 class="mt-1">Telah diterima</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <a class="col-md-6 col-xl-2" href="{{ route('permintaan-laundry.create') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/order.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-2 text-truncate"><u>&nbsp;</u></p>
                                    <h4 class="mt-2">Buat Permintaan Laundry</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                <a class="col-md-6 col-xl-2" href="{{ route('history-laundry') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-right">
                                    <img src="../assets/images/laundry/clock.png" style="height:150px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-left">
                                    <p class="text-muted mb-2 text-truncate"><u>&nbsp;</u></p>
                                    <h4 class="mt-2">History Pemesanan</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

            </div>
            <!-- end row-->
        </div>

    </div>

</div>

<div id="right-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <label class="mt-0">No Transaksi: </label><label class="mt-0">#@if(isset($data['transaksi_terakhir'])) {{ $data['transaksi_terakhir']->kode_transaksi }} @endif</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                    <!-- <div class="text-center"> -->
                        <div class="track-order-list">
                            <ul class="list-unstyled">
                                @if(isset($data['transaksi_terakhir']) and is_null($data['transaksi_terakhir']->qc_id))
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->qc_id > 0)
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1">Proses Penjemputan</h5>
                                    <!-- <p class="text-muted">28 Mei 2022 <small class="text-muted">07:22</small> </p> -->
                                </li>
                                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->qc_id > 0 and is_null($data['transaksi_terakhir']->cuci_id))
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->qc_id > 0 and $data['transaksi_terakhir']->cuci_id > 0)
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1">Proses QC</h5>
                                    <!-- <p class="text-muted">28 Mei 2022 <small class="text-muted">12:16</small></p> -->
                                </li>
                                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->cuci_id > 0 and is_null($data['transaksi_terakhir']->pengeringan_id))
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->cuci_id > 0 and $data['transaksi_terakhir']->pengeringan_id > 0)
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1">Proses Cuci</h5>
                                    <!-- <p class="text-muted">28 Mei 2022 <small class="text-muted">14:20</small></p> -->
                                </li>
                                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->pengeringan_id > 0 and is_null($data['transaksi_terakhir']->setrika_id))
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->pengeringan_id > 0 and $data['transaksi_terakhir']->setrika_id > 0)
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1">Proses Pengeringan</h5>
                                    <p class="text-muted"></p>
                                </li>
                                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->setrika_id > 0 and is_null($data['transaksi_terakhir']->deliver_by))
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->setrika_id > 0 and $data['transaksi_terakhir']->deliver_by > 0)
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1"> Proses Setrika</h5>
                                    <p class="text-muted"></p>
                                </li>
                                <!-- <li>
                                    <h5 class="mt-0 mb-1"> Proses Pack</h5>
                                    <p class="text-muted"></p>
                                </li> -->
                                @if(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->deliver_by > 0 and is_null($data['transaksi_terakhir']->deliver_at) )
                                <li>
                                    <span class="active-dot dot"></span>
                                @elseif(isset($data['transaksi_terakhir']) and $data['transaksi_terakhir']->deliver_by > 0 and isset($data['transaksi_terakhir']->deliver_at))
                                <li class="completed">
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1"> Proses Pengantaran</h5>
                                    <p class="text-muted"></p>
                                </li>
                                @if(isset($data['transaksi_terakhir']) and isset($data['transaksi_terakhir']->deliver_at))
                                <li>
                                    <span class="active-dot dot"></span>
                                @else
                                <li>
                                @endif
                                    <h5 class="mt-0 mb-1"> Selesai</h5>
                                    <p class="text-muted"></p>
                                </li>
                            </ul>

                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-danger" data-dismiss="modal" >Tutup</a>
                            </div>
                            <br>
                        </div>
                    <!-- </div> -->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@push('script')
<script type="text/javascript">
    function like(id){
        // alert(id);

        // let print_home          = `{{ url('request-laundry') }}`;
        let print_url           = `{{ url('like') }}`;
        let redirect_print_url  = print_url+'/'+id;
        // alert(redirect_print_url);

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
        // alert(id);

        // let print_home          = `{{ url('request-laundry') }}`;
        let print_url           = `{{ url('dislike') }}`;
        let redirect_print_url  = print_url+'/'+id;
        // alert(redirect_print_url);

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
</script>


@endpush
