<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <form id="form" class="form d-flex flex-column flex-lg-row" action="" method="POST" enctype="multipart/form-data">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header"style="clear:both; position:relative;">
                        <div class="col-md-3" style="position:absolute; left:25pt; width:292pt;">
                            <img src="" width="25%" height="10%">
                        </div>
                        <div class="card-title" style="margin-left:150pt; margin-bottom:50pt;">
                            <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Detail Rekap Corporate</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <div class="row" style="margin-bottom: 30px !important">
                            <table class="table" style="width: 100%; !important" id="putih">
                                <tbody>
                                    <tr style="vertical-align: top;">
                                        <td>
                                            <div class="form-permohonan">
                                                <span>NAMA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$corporate->user->name . ' - ' . $corporate->address}}</span>
                                            </div>
                                            <div class="form-permohonan">
                                                <span>PERIODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{date('M Y',strtotime(request()->input('startdate')))}}</span>
                                            </div>
                                            <div class="form-permohonan">
                                                <span>MONTHLY LAUNDRY</span>
                                            </div>
                                            <div class="form-permohonan">
                                                <span>FRUITS LAUNDRY</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-10 fv-row" style="margin-bottom: 30px !important">
                            <!--begin::Label-->
                            {{-- <label class="form-label">Detail Item Variants</label><br/><br/> --}}
                            <!--end::Label-->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table detail gy-3 fs-7">
                                        <thead style="vertical-align: top;">
                                            <tr class="fw-bolder bg-light detail">
                                                    <th width="min-w-25" rowspan="2" class="text-center align-middle">No</th>
                                                    <th width="min-w-150" rowspan="2" class="text-center align-middle">Date</th>
                                                    <th width="min-w-100" rowspan="2" class="text-center align-middle">Time</th>
                                                    @foreach ($harga_layanan as $row)
                                                    <th width="" colspan="4" class="text-center">{{$row->nama}}</th>
                                                    @endforeach
              
                                                  </tr>
                                                  <tr class="fw-bolder bg-light detail">
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
                                                        <td>{{ $index == 0 ? ($key + 1) : '' }}</td>
                                                        <td>{{ $index == 0 ? date('d-m-Y', strtotime($col->created_at)) : '' }}</td>
                                                        <td>{{ $index == 0 ? date('H:i:s', strtotime($col->created_at)) : '' }}</td>
                                                        @foreach ($harga_layanan as $count => $row)
                                                            <td class="text-end totalTowel-{{ $count }}-item"
                                                                id="totalTowel-{{ $row->kode }}">{{ $item->kode_layanan == $row->kode ? ($item->jumlah ?? 0) : ($item->kode_layanan == $row->kode ? 0 : '') }}</td>
                                                            <td class="text-end totalReturn-{{ $count }}-item"
                                                                id="totalReturn-{{ $row->kode }}">{{ $item->kode_layanan == $row->kode ? ($item->qty_special_treatment ?? 0) : ($item->kode_layanan == $row->kode ? 0 : '') }}</td>
                                                            <td class="text-end totalRewash-{{ $count }}-item"
                                                                id="totalRewash-{{ $row->kode }}">{{ $item->kode_layanan == $row->kode ? ($item->qty_rewash ?? 0) : ($item->kode_layanan == $row->kode ? 0 : '') }}</td>
                                                            <td class="text-end totalRemark-{{ $count }}-item"
                                                                id="totalRemark-{{ $row->kode }}">{{ $item->kode_layanan == $row->kode ? ($item->qty_remark ?? 0) : ($item->kode_layanan == $row->kode ? 0 : '') }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                            @if (empty($data))
                                                <tr>
                                                    <td colspan="14" class="text-center align-middle">No Data Available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-center align-middle">TOTAL TOWEL</th>
                                                @php
                                                    // Assuming $json is a JSON string, decode it into an array
                                                    $jsonArray = json_decode($json, true);
                                                @endphp
                                                @foreach ($jsonArray as $layanan => $detail)
                                                    @php
                                                        $jumlah = $detail['jumlah'];
                                                        $qty_special_treatment = $detail['qty_special_treatment'];
                                                        $qty_rewash = $detail['qty_rewash'];
                                                        $qty_remark = $detail['qty_remark'];
                                                    @endphp
                                                    <th class="text-end" id="totalTowel-{{ $layanan }}">{{ $jumlah }}</th>
                                                    <th class="text-end" id="totalReturn-{{ $layanan }}">{{ $qty_special_treatment }}</th>
                                                    <th class="text-end" id="totalRewash-{{ $layanan }}">{{ $qty_rewash }}</th>
                                                    <th class="text-end" id="totalRemark-{{ $layanan }}">{{ $qty_remark }}</th>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!--end::Input-->
                        </div>
                        <div class="row" style="clear:both; position:relative;margin-top:7pt;">
                            <div class="col-sm-3 fs-6" style="position:absolute; left:0pt; width:292pt;">
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
                                        @php
                                            // Assuming $json is a JSON string, decode it into an array
                                            $jsonArray = json_decode($paymentDescription, true);

                                            $totalAmount = 0;
                                        @endphp
                                        @foreach ($jsonArray as $layanan => $detail)
                                            @php
                                                $towel = $detail['towel'];
                                                $price = $detail['price'];
                                                $amount = $towel * $price;
                                                $totalAmount += $amount;
                                            @endphp
                                        <tr>
                                            <td>{{$row->nama}}</td>
                                            <td class="text-end towel-{{$key}}-item" id="payTotalTowel-1">{{$towel}}</td>
                                            <td class="text-end price-{{$key}}-item">Rp {{number_format($price, 0, ',', '.')}}</td>
                                            <td class="text-end amount-{{$key}}-item" id="ammount-1">Rp {{number_format($amount, 0, ',', '.')}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-center align-middle">TOTAL AMMOUNT  </th>
                                            <th class="text-end" id="totalAmmount">Rp {{number_format($totalAmount, 0, ',', '.')}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-center align-middle">PPN 10%  </th>
                                            <th class="text-end">-</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-center align-middle">TOTAL PAY  </th>
                                            <th class="text-end">0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>  
                            <div class="col-sm-3" style="margin-left:350pt; width:100px;">
                                <table class="table detail gy-3 fs-7">
                                    <thead style="vertical-align: top;">
                                        <td class="fs-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-center">Operasional Manager&nbsp;</span>
                                            </div>
                                        </td>
                                        <td class="fs-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-center">Customer Service&nbsp;</span>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                    <tbody style="border: 1px solid;">
                                        <tr class="detail">
                                            <td class="p-2 detail" style="height: 75px !important; width: 100px !important; margin-left: 10px !important"></td>
                                            <td class="p-2 detail" style="height: 75px !important; width: 100px !important; margin-left: 10px !important"></td>
                                        </tr>
                                        <tr class="detail">
                                            <td class="p-2 detail" style="height: 10px !important; margin-left: 10px !important"></td>
                                            <td class="p-2 detail" style="height: 10px !important; margin-left: 10px !important"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::General options-->
            </div>
            <!--end::Main column-->
        </form>
    </div>
    <!--end::Container-->
</div>
<style>
    html,
    @page { margin: 0px; }
    body {
        margin: 10px;
        padding: 10px;
        font-family: sans-serif;
    }
    h1,h2,h3,h4,h5,h6,p,span,label {
        font-family: sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0px !important;
    }
    table#putih >td{
        border: 1px solid white !important;
    }
    table thead th {
        height: 10px;
        text-align: left;
        font-size: 12px;
        font-family: sans-serif;
    }
    table, th, td {
        border: 1px solid black;
        padding: 8px;
        font-size: 10px;
    }

    .heading {
        font-size: 24px;
        margin-top: 12px;
        margin-bottom: 12px;
        font-family: sans-serif;
    }
    .small-heading {
        font-size: 16px;
        font-family: sans-serif;
    }
    .form-label {
        font-size: 14px;
        font-family: sans-serif;
        font-weight: bold;
    }
    .form-rekening {
        font-size: 11px;
        font-family: sans-serif;
        margin-top: 8px;
    }
    .form-permohonan {
        font-size: 12px;
        font-family: sans-serif;
        margin-top: 9px;
    }
    .form-check-input{
        font-family: sans-serif;
        margin-top: 10px;
    }
    .total-heading {
        font-size: 18px;
        font-weight: 700;
        font-family: sans-serif;
    }
    .responsive {
        width: 100%;
        height: auto;
    }

    .text-start {
        text-align: left;
    }
    .text-end {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .company-data span {
        margin-bottom: 4px;
        display: inline-block;
        font-family: sans-serif;
        font-size: 14px;
        font-weight: 400;
    }
    .no-border {
        border: 1px solid #fff !important;
    }
    .no-left-border{
        border-left: 1px solid #fff !important;
    }
    .no-bottom-border{
        border-bottom:  1px solid #fff !important;
    }
    .bg-blue {
        background-color:darkgray;
        color: black;
    }
    .form-check-input[type="checkbox"] {
        width: 16px; /* Set the desired width */
        height: 16px; /* Set the desired height */
    }
</style>
