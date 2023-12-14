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
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>{{$corporate->user->name . ' - ' . $corporate->address}}</strong></p>
                                    <p><strong>PERIODE : {{date('M Y',strtotime(request()->input('startdate')))}} - {{date('M Y',strtotime(request()->input('enddate')))}}</strong></p>
                                    <p><strong>MONTHLY LAUNDRY</strong></p>
                                    <p><strong>FRUITS LAUNDRY</strong></p>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#" class="btn btn-success">Export Excel</a>
                                    <a href="#" class="btn btn-danger">Export Pdf</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                      <th width="25px" rowspan="2" class="text-center align-middle">No</th>
                                      <th width="" rowspan="2" class="text-center align-middle">Date</th>
                                      <th width="" rowspan="2" class="text-center align-middle">Time</th>
                                      @foreach ($harga_layanan as $row)
                                      <th width="" colspan="4" class="text-center">{{$row->nama}}</th>
                                      @endforeach

                                    </tr>
                                    <tr>
                                      @foreach ($harga_layanan as $row)
                                      <th width="">Send</th>
                                      <th width="">Return</th>
                                      <th width="">Rewash</th>
                                      <th width="">Remark</th>
                                      @endforeach
                                    </tr>
                                  </thead>
                                <tbody>
                                    @foreach ($data as $key => $col)
                                    @foreach ($col->TransaksiDetail as $index => $item)
                                    <tr>
                                            <td>
                                                {{$index == 0 ? ($key + 1) : ''}}
                                            </td>
                                            <td>
                                                {{$index == 0 ? date('d-m-Y', strtotime($col->created_at)) : ''}}
                                            </td>
                                            <td>
                                                {{$index == 0 ? date('H:i:s', strtotime($col->created_at)) : ''}}
                                            </td>
                                            
                                            @foreach ($harga_layanan as $count => $row)
                                            <td class="text-right totalTowel-{{$count}}-item" id="totalTowel-{{$row->kode}}">{{ $item->kode_layanan == $row->kode ? ($item->jumlah ?? 0) : 0 }}</td>
                                            <td class="text-right totalReturn-{{$count}}-item" id="totalReturn-{{$row->kode}}">{{ $item->kode_layanan == $row->kode ? ($item->qty_special_treatment ?? 0) : 0 }}</td>
                                            <td class="text-right totalRewash-{{$count}}-item" id="totalRewash-{{$row->kode}}">{{ $item->kode_layanan == $row->kode ? ($item->qty_rewash ?? 0) : 0 }}</td>
                                            <td class="text-right totalRemark-{{$count}}-item" id="totalRemark-{{$row->kode}}">{{ $item->kode_layanan == $row->kode ? ($item->qty_remark ?? 0) : 0 }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                        @endforeach
                                        @if (!$data)
                                            <tr>
                                                <td colspan="14" class="text-center align-middle">No Data Available</td>
                                            </tr>
                                        @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-center align-middle">TOTAL TOWEL</th>

                                        @foreach ($harga_layanan as $key => $row)
                                        <th class="text-right" id="totalTowel-{{$key}}"></th>
                                        <th class="text-right" id="totalReturn-{{$key}}"></th>
                                        <th class="text-right" id="totalRewash-{{$key}}"></th>
                                        <th class="text-right" id="totalRemark-{{$key}}"></th>
                                        @endforeach


                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                      <th width="25px" colspan="4" class="text-center align-middle">PAYMENT DESCRIPTION</th>
                                    </tr>
                                    <tr>
                                      <th width="">Description</th>
                                      <th width="">Towel</th>
                                      <th width="">Price</th>
                                      <th width="">Ammount</th>
                                    </tr>
                                  </thead>
                                <tbody>
                                    @foreach ($harga_layanan as $key => $row)
                                    <tr>
                                        <td>{{$row->nama}}</td>
                                        <td class="text-right towel-{{$key}}-item" id="payTotalTowel-1">0</td>
                                        <td class="text-right price-{{$key}}-item">Rp {{number_format($row->harga, 0, ',', '.')}}</td>
                                        <td class="text-right amount-{{$key}}-item" id="ammount-1">Rp 0</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-center align-middle">TOTAL AMMOUNT  </th>
                                        <th class="text-center align-middle" id="totalAmmount">0</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-center align-middle">PPN 10%  </th>
                                        <th class="text-center align-middle">-</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-center align-middle">TOTAL PAY  </th>
                                        <th class="text-center align-middle">0</th>
                                    </tr>
                                </tfoot>
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
    $(document).ready(function () {

        $.ajax({
            type: "get",
            url: `{{ route('laporan.corporate.detail', $id) }}?startdate={{ request()->input('startdate') }}&enddate={{ request()->input('enddate') }}&getDataHargaLayanan=true`,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                let datas = response.data;
                var totalAmmount = 0;
                for (let index = 0; index < datas.length; index++) {
                    const data = datas[index];
                    
                    // Function to calculate and update totals
                    function updateTotals() {
                        var totalTowel = 0;
                        var totalReturn = 0;
                        var totalRewash = 0;
                        var totalRemark = 0;

                        // Iterate through each row in the tbody
                        $('tbody tr').each(function () {
                            var $row = $(this);

                            // Extract values from the row and update totals
                            totalTowel += parseFloat($row.find('.totalTowel-' + index + '-item').text()) || 0;
                            totalReturn += parseFloat($row.find('.totalReturn-' + index + '-item').text()) || 0;
                            totalRewash += parseFloat($row.find('.totalRewash-' + index + '-item').text()) || 0;
                            totalRemark += parseFloat($row.find('.totalRemark-' + index + '-item').text()) || 0;
                        });

                        // Update the totals in the tfoot
                        $('#totalTowel-' + index).text(totalTowel);
                        $('#totalReturn-' + index).text(totalReturn);
                        $('#totalRewash-' + index).text(totalRewash);
                        $('#totalRemark-' + index).text(totalRemark);

                        $('.towel-' + index + '-item').text(totalTowel);
                        var amount = parseInt(totalTowel) * data.harga;
                        console.log(amount);
                        $('.amount-' + index + '-item').text(formatCurrency(amount));
                        totalAmmount += amount;
                    }

                    // Call the function to update totals initially
                    updateTotals();
                }

                $('#totalAmmount').text(formatCurrency(totalAmmount));
            },
            error: function (request, status, error) {
                
            }
        });
    
    $('#totalAmmount').text(formatCurrency(totalAmmount));
    // Function to format number as Rupiah currency
    function formatCurrency(number) {
        var formatted = 'Rp ' + number.toLocaleString('id-ID', { maximumFractionDigits: 2, minimumFractionDigits: 0 });
        
        // Remove trailing '.00'
        formatted = formatted.replace(/\.00$/, '');

        return formatted;
    }
});

</script>

@endpush
