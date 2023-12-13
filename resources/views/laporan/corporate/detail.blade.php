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
                            <p><strong>{{$corporate->user->name . ' - ' . $corporate->address}}</strong></p>
                            <p><strong>PERIODE : {{date('M Y',strtotime(request()->input('startdate')))}} - {{date('M Y',strtotime(request()->input('enddate')))}}</strong></p>
                            <p><strong>MONTHLY LAUNDRY</strong></p>
                            <p><strong>FRUITS LAUNDRY</strong></p>
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
                                      <th width="" colspan="4" class="text-center">Bath Towel/Handuk Besar</th>
                                      <th width="" colspan="4" class="text-center">Hand Towel/Handuk Kecil</th>

                                      <th width="" colspan="3" class="text-center">Shower Curtain</th>
                                    </tr>
                                    <tr>
                                      <th width="">Send</th>
                                      <th width="">Return</th>
                                      <th width="">Rewash</th>
                                      <th width="">Remark</th>
                                      <th width="">Send</th>
                                      <th width="">Return</th>
                                      <th width="">Rewash</th>
                                      <th width="">Remark</th>
                                      
                                      <th width="">Send</th>
                                      <th width="">Return</th>
                                      <th width="">Remark</th>
                                    </tr>
                                  </thead>
                                <tbody>
                                    @foreach ($data as $key => $col)
                                    @foreach ($col->TransaksiDetail as $index => $item)
                                    @if ($item->kode_layanan == 'handuk_besar' || $item->kode_layanan == 'handuk_kecil' || $item->kode_layanan == 'shower_curtain')
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
                                            
                                            <td class="text-right totalTowel-1-item" id="totalTowel-{{ $item->kode_layanan == 'handuk_besar' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_besar' ? ($item->jumlah ?? 0) : 0 }}</td>
                                            <td class="text-right totalReturn-1-item" id="totalReturn-{{ $item->kode_layanan == 'handuk_besar' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_besar' ? ($item->qty_special_treatment ?? 0) : 0 }}</td>
                                            <td class="text-right totalRewash-1-item" id="totalRewash-{{ $item->kode_layanan == 'handuk_besar' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_besar' ? ($item->qty_rewash ?? 0) : 0 }}</td>
                                            <td class="text-right totalRemark-1-item" id="totalRemark-{{ $item->kode_layanan == 'handuk_besar' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_besar' ? ($item->qty_remark ?? 0) : 0 }}</td>
                                            
                                            <td class="text-right totalTowel-2-item" id="totalTowel-{{ $item->kode_layanan == 'handuk_kecil' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_kecil' ? ($item->jumlah ?? 0) : 0 }}</td>
                                            <td class="text-right totalReturn-2-item" id="totalReturn-{{ $item->kode_layanan == 'handuk_kecil' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_kecil' ? ($item->qty_special_treatment ?? 0) : 0 }}</td>
                                            <td class="text-right totalRewash-2-item" id="totalRewash-{{ $item->kode_layanan == 'handuk_kecil' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_kecil' ? ($item->qty_rewash ?? 0) : 0 }}</td>
                                            <td class="text-right totalRemark-2-item" id="totalRemark-{{ $item->kode_layanan == 'handuk_kecil' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'handuk_kecil' ? ($item->qty_remark ?? 0) : 0 }}</td>

                                            <td class="text-right totalTowel-3-item" id="totalTowel-{{ $item->kode_layanan == 'shower_curtain' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'shower_curtain' ? ($item->jumlah ?? 0) : 0 }}</td>
                                            <td class="text-right totalReturn-3-item" id="totalReturn-{{ $item->kode_layanan == 'shower_curtain' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'shower_curtain' ? ($item->qty_special_treatment ?? 0) : 0 }}</td>
                                            <td class="text-right totalRemark-3-item" id="totalRemark-{{ $item->kode_layanan == 'shower_curtain' ? $item->kode_layanan : '' }}">{{ $item->kode_layanan == 'shower_curtain' ? ($item->qty_remark ?? 0) : 0 }}</td>
                                        </tr>
                                        @endif
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

                                        @for ($i = 1; $i < 4; $i++)
                                        <th class="text-right" id="totalTowel-{{$i}}"></th>
                                        <th class="text-right" id="totalReturn-{{$i}}"></th>
                                        @if ($i < 3)
                                        <th class="text-right" id="totalRewash-{{$i}}"></th>
                                        @endif
                                        <th class="text-right" id="totalRemark-{{$i}}"></th>
                                        @endfor


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
                                    <tr>
                                        <td>Bath Towel</td>
                                        <td class="text-right" id="payTotalTowel-1">0</td>
                                        <td class="text-right">Rp 1.600</td>
                                        <td class="text-right" id="ammount-1">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Hand Towel</td>
                                        <td class="text-right" id="payTotalTowel-2">300</td>
                                        <td class="text-right">Rp 700</td>
                                        <td class="text-right" id="ammount-2">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Hammock</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">######</td>
                                        <td class="text-right">-</td>
                                    </tr>
                                    <tr>
                                        <td>Shower Curtain</td>
                                        <td class="text-right" id="payTotalTowel-3">-</td>
                                        <td class="text-right">Rp 3.500</td>
                                        <td class="text-right" id="ammount-3">-</td>
                                    </tr>
                                    <tr>
                                        <td>Matras</td>
                                        <td class="text-right">-</td>
                                        <td class="text-right">Rp 8.000</td>
                                        <td class="text-right">-</td>
                                    </tr>
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
    // Function to calculate and update totals
    var totalAmmount = 0;
    for (let index = 1; index < 4; index++) {
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

        // Update the payment amount based on the totalTowel and the price in the payment table
        var price = 0;
        switch (index) {
            case 1:
                price = 1600;
                break;
            case 2:
                price = 700;
                break;
            case 3:
                price = 3500;
                break;
            // Add more cases if needed for other indexes
        }

        $('#payTotalTowel-' + index).text(totalTowel);
        var amount = totalTowel * price;
        $('#ammount-' + index).text(formatCurrency(amount));
        totalAmmount += amount;
        }

        // Call the function to update totals initially
        updateTotals();
    }
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
