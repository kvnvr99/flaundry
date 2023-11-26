<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table, th {
            border: 1px solid #666;
            font-family: monospace;
            font-size: 8pt;
            margin-top: 8px;
        }
        span {
            border-bottom: 1px solid #666;
            font-family: monospace;
            font-size: 8pt;
        }
        .item {
            text-align: right;
        }
        @page { margin: 0 !important; }
    </style>
</head>
<body onload="javascript:window.print()">
    <hr><hr>
    <span>
        <div class="member-info"><div class="member-info">No Transaksi : {{ $data->kode_transaksi }}</div>
    </span>
    <span>
        <div class="member-info"><div class="member-info">Nama : {{ $data->nama }}</div>
    </span>
    <hr>
    <span>
        <div class="member-info">Tanggal Masuk : {{ date_format($data->created_at, "d-m-Y H:i:s") }}</div>
    </span>
    <hr>
    <span>
        <div class="member-info">Alamat : {{ $data->alamat }}</div>
    </span>
    <hr>
    <span>
        <div class="member-info">No HP : {{ $data->no_handphone }}</div>
    </span>
    <hr>
    <span>
        <div class="member-info">Tanggal Keluar : -</div>
    </span>
    <hr>
    <span>
        <div class="member-info">Asal Outlet : {{ $data->outlet->nama }}</div>
    </span>
    <hr>
    <br>
    <div style="text-align: center;">
        {!! QrCode::size(150)->generate($data->kode_transaksi); !!}
    </div>
    <br><br>
    <table width="100%">
        <tr>
            <th>ITEM</th>
            <th>QTY SATUAN / KG</th>
            <th>HARGA</th>
        </tr>
        @foreach ($data->TransaksiDetail as $item)
        <tr>
            <td>{{ $item->harga->nama }}</td>
            <td class="item">{{ $item->jumlah }}</td>
            <td class="item">{{ number_format($item->harga_jumlah, 2, ",", ".") }}</td>
        </tr>
        @endforeach
        @php
            $sum_quantity = array_sum(array_column($data->TransaksiDetail->toArray(), 'jumlah'));
            $sum_harga = array_sum(array_column($data->TransaksiDetail->toArray(), 'harga_jumlah'));
            $sum_quantity_st = array_sum(array_column($data->TransaksiDetail->toArray(), 'qty_special_treatment'));
            $sum_harga_st = array_sum(array_column($data->TransaksiDetail->toArray(), 'harga_jumlah_special_treatment'));
        @endphp
        <tr>
            <th>Total Item</th>
            <th class="item">{{ $sum_quantity }}</th>
            <th class="item">{{ number_format($sum_harga, 2, ",", ".") }}</th>
        </tr>
        <tr>
            <th>Special Treatment</th>
            <th class="item">{{ $sum_quantity_st }}</th>
            <th class="item">{{ number_format($sum_harga_st, 2, ",", ".") }}</th>
        </tr>
        <tr>
            <th>Sub Total</th>
            <th class="item">{{ $sum_quantity+$sum_quantity_st }}</th>
            <th class="item">{{ number_format($sum_harga+$sum_harga_st, 2, ",", ".") }}</th>
        </tr>
    </table>
    <br><br><br><br>
    <span>Printed At : {{ date("d-m-Y H:i:s") }}</span>
    <br><br><br><br>
    <hr><hr>
</body>
</html>
