@extends('layouts.main')
@section('content')
<meta http-equiv="refresh" content="60">
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            @include('component.breadcrumb')
            <!-- start page title -->
            <div class="row">
                <div class="col-md-12 col-xl-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <h2>Laporan</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                
                <a class="col-md-4 col-xl-3" href="{{ route('laporan-outlet') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/outlet.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">OUTLET</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                <a class="col-md-4 col-xl-3" href="{{ route('laporan-expedisi') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/expedisi.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">EXPEDISI</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                <a class="col-md-4 col-xl-3" href="{{ route('laporan-member') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/member.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">REKAP MEMBER</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                 <a class="col-md-4 col-xl-3" href="{{ route('laporan-frenchaise') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/frenchaise.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">REKAP FRENCHAISE</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                <a class="col-md-4 col-xl-3" href="{{ route('laporan-agen') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/agen.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">REKAP MITRA AGEN</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

                <a class="col-md-4 col-xl-3" href="{{ route('laporan-absen') }}">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <img src="../assets/images/laundry/absen.png" style="height:100px;" alt="user-img" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="text-muted mb-1 text-truncate">&nbsp;</p>
                                    <h4 class="mt-1">REKAP ABSEN</h4>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a> <!-- end col-->

            </div><!-- end row-->


        </div>

    </div>

</div>
@endsection
@push('script')
<!-- Dashboar 1 init js-->
<!-- <script src="{{ asset('assets/js/pages/dashboard-1.init.js')}}"></script> -->
@endpush
