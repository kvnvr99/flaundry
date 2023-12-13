@extends('layouts.main')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
@endpush
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                @include('component.breadcrumb')
                <div class="row">
                    <!-- <div class="col-12">
                                                <a href="{{ route('user_corporate.add') }}" class="btn btn-primary waves-effect waves-light mb-3">Add</a>
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
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th width="10%">Aksi</th>
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
    <div id="modal_redirect_detail" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabelLayanan" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabelLayanan">Periode</h4>
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
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label for="start-date">Start Date</label>
                                            <input type="text" class="form-control" id="start-date" name="" data-date="">
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label for="end-date">End Date</label>
                                            <input type="text" class="form-control" id="end-date" name="" data-date="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="triggerModalRedirect">Cetak Detail</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
@endpush
@push('script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
    <script src="https://jsuites.net/v4/jsuites.js"></script>
    <script>
        $(document).ready(function() {
            let datatable = $('#state-saving-datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                method: "POST",
                scrollX: true,
                ajax: {
                    url: "{!! route('laporan.corporate.getData') !!}",
                    type: "POST",
                    dataType: "JSON"
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            
        });

        $(document).on('click', '.triggerModalRedirectDetail', function(e) {
            e.preventDefault();
            $('#modal_redirect_detail').modal('show');
            var url = $(this).data('url');
            $('#triggerModalRedirect').on('click', function() {
                let params = [];
    
                if ($('#start-date').val() != '') {
                    params.push('startdate=' + $('#start-date').val());
                }
    
                if ($('#end-date').val() != '') {
                    params.push('enddate=' + $('#end-date').val());
                }
    
                window.location.href = url + "?" + params.join('&');
            });
        });

        jSuites.calendar(document.getElementById('start-date'), {
            type: 'year-month-picker',
            format: 'MMM-YYYY',
            validRange: ['2020-01-01', '{{today()}}']
        });
        jSuites.calendar(document.getElementById('end-date'), {
            type: 'year-month-picker',
            format: 'MMM-YYYY',
            validRange: ['2020-01-01', '{{today()}}']
        });
    </script>
@endpush
