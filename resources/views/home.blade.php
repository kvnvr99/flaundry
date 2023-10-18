@extends('layouts.main')
@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="form-inline">
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
                            </form>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['infogram']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('infogram') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">INFOGRAM</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['registrasi']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('registrasi') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">REGISTRASI</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['quality-control']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('qc') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">QC</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['cuci']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('cuci') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">CUCI</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['pengeringan']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('pengeringan') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">PENGERINGAN</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['setrika']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('setrika') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">SETRIKA</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['jadwal-jemput']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('expedisi-jadwal-jemput') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">JADWAL JEMPUT</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['jadwal-antar']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('expedisi-jadwal-antar') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">JADWAL ANTAR</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['jemput-barang']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('expedisi-jemput') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">JEMPUT BARANG</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @if( Auth::user()->getRoleNames()[0] == 'Developer' || Auth::user()->hasAnyPermission(['antar-barang']))
                <div class="col-md-6 col-xl-3">
                    <a href="{{ route('expedisi-antar') }}">
                        <div class="widget-rounded-circle card-box">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-book-open font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-right">
                                        <h3 class="mt-1">ANTAR BARANG</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<!-- Dashboar 1 init js-->
{{-- <script src="{{ asset('assets/js/pages/dashboard-1.init.js')}}"></script> --}}
@endpush
