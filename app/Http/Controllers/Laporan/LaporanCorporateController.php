<?php

namespace App\Http\Controllers\Laporan;

use PDF;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Harga;
// use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Corporate;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanCorporateController extends Controller
{
    public function index(){
        return view('laporan.corporate.index');
    }

    public function getData(Request $request) {
        $data = Corporate::select('corporate.*', 'users.name')
            // ->leftJoin('transaksis', 'transaksis.corporate_id', '=', 'corporate.id')
            ->leftJoin('users', 'users.id', '=', 'corporate.user_id')
            // ->groupBy('corporate.id','corporate.user_id')
            ->get();
    
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="btn btn-secondary btn-sm triggerModalRedirectDetail" data-url="' . route('laporan.corporate.detail', ['id' => $row->id]) . '">
                            Detail
                        </a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detail($id, Request $request)
    {
        $id = $id;
        $data = Transaksi::with('transaksiDetail')
        ->where('transaksis.corporate_id', $id);

        // Check if startdate is provided in the request
        if ($request->filled('startdate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            // Filter data based on the startdate only
            $data->whereMonth('transaksis.created_at', $startDate->month)
                ->whereYear('transaksis.created_at', $startDate->year);
        } else {
            return redirect(route('laporan.corporate'));
        }

        $data = $data->get();

        $corporate = Corporate::with('user')->find($id);

        // Retrieve the unique harga_id values from transaksi_detail
        $hargaIds = $data->pluck('transaksiDetail.*.harga_id')->flatten()->unique()->toArray();

        // Fetch the corresponding harga_layanan records
        $harga_layanan = Harga::whereIn('id', $hargaIds)->get();

        if ($request->has('getDataHargaLayanan')) {
            return response()->json([
                'data' => $harga_layanan
            ]);
        }

        return view('laporan.corporate.detail', compact('id','data', 'corporate', 'harga_layanan'));
    }

    public function exportExcel(Request $request){
        $id = $request->id;

        $data = Transaksi::with('transaksiDetail')
        ->where('transaksis.corporate_id', $id);

        // Check if startdate is provided in the request
        if ($request->filled('startdate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            // Filter data based on the startdate only
            $data->whereMonth('transaksis.created_at', $startDate->month)
                ->whereYear('transaksis.created_at', $startDate->year);
        } else {
            return redirect(route('laporan.corporate'));
        }

        $data = $data->get();

        $corporate = Corporate::with('user')->find($id);

        // Retrieve the unique harga_id values from transaksi_detail
        $hargaIds = $data->pluck('transaksiDetail.*.harga_id')->flatten()->unique()->toArray();

        // Fetch the corresponding harga_layanan records
        $harga_layanan = Harga::whereIn('id', $hargaIds)->get();

        if (!$data) {
            return abort(404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->getStyle('G')->getNumberFormat()
        ->setFormatCode('#,##0');

        $textCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        
        $textLeft = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        
        $textRight = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        // foreach ($harga_layanan as $row) {
        //     $columnIndex = $sheet->getHighestColumn(); // Get the current highest column (e.g., 'G', 'H', etc.)
        //     $nextColumn = ++$columnIndex; // Get the next column
        
        //     // Merge cells horizontally
        //     $sheet->mergeCells("{$nextColumn}5:{$nextColumn}6");
        
        //     // Set the cell value and styling
        //     $sheet->setCellValue("{$nextColumn}5", $row->nama)->getStyle("{$nextColumn}5")->applyFromArray($textCenter);
        
        //     // Repeat the same process for the four sub-columns
        //     for ($i = 0; $i < 4; $i++) {
        //         $nextColumn = ++$columnIndex;
        //         $sheet->setCellValue("{$nextColumn}6", $i == 0 ? 'Send' : ($i == 1 ? 'Return' : ($i == 2 ? 'Rewash' : 'Remark')))->getStyle("{$nextColumn}6")->applyFromArray($textCenter);
        //     }
        // }

        
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $textTopLeft = [
            'alignment' => array(
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, 
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
			),
        ];

        $textCenter = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, 
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->mergeCells('A1:G1')->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A1:G1')->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A2:G2')->getStyle('A2:G2')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A3:G3')->getStyle('A3:G3')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A4:G4')->getStyle('A4:G4')->getFont()->setBold(true)->setSize(12);
        $sheet->setCellValue('A1', 'Nama : ' . $corporate->user->name . ' - ' . $corporate->address);
        $sheet->setCellValue('A2', 'Periode : '.($request->startdate ? (date('M Y', strtotime($request->startdate)) ) : '-'))->getStyle('A2')->applyFromArray($textLeft);
        $sheet->setCellValue('A3', 'MONTHLY LAUNDRY')->getStyle('A3')->applyFromArray($textLeft);
        $sheet->setCellValue('A4', 'FRUITS LAUNDRY ')->getStyle('A4')->applyFromArray($textLeft);
        $sheet->mergeCells('A6:A7')->getStyle('A6:A7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('A6', 'No')->getStyle('A6')->applyFromArray($textCenter);
        $sheet->mergeCells('B6:B7')->getStyle('B6:B7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('B6', 'Date')->getStyle('B6')->applyFromArray($textCenter);
        $sheet->mergeCells('C6:C7')->getStyle('C6:C7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('C6', 'Time')->getStyle('C6')->applyFromArray($textCenter);

        $columnIndex = 3; // Kolom dimulai dari D

        foreach ($harga_layanan as $key => $value) {
            $startColumnSend = chr(65 + $columnIndex); // D
            $startColumnReturn = chr(65 + $columnIndex + 1); // E
            $startColumnRewash = chr(65 + $columnIndex + 2); // F
            $startColumnRemark = chr(65 + $columnIndex + 3); // G

            $sheet->mergeCells("{$startColumnSend}6:{$startColumnRemark}6")->getStyle("{$startColumnSend}6:{$startColumnRemark}6")->getFont()->setBold(true)->setSize(11);
            $sheet->setCellValue("{$startColumnSend}6", $value->nama)->getStyle("{$startColumnSend}6")->applyFromArray($textCenter);

            $sheet->setCellValue("{$startColumnSend}7", 'Send')->getStyle("{$startColumnSend}7")->applyFromArray($textCenter)->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnReturn}7", 'Return')->getStyle("{$startColumnReturn}7")->applyFromArray($textCenter)->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnRewash}7", 'Rewash')->getStyle("{$startColumnRewash}7")->applyFromArray($textCenter)->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnRemark}7", 'Remark')->getStyle("{$startColumnRemark}7")->applyFromArray($textCenter)->getFont()->setBold(true);

            $columnIndex += 4; // Karena Anda menggabungkan 4 kolom untuk setiap data layanan
        }
        
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);

        $rows = 8; // Adjusted starting row
        $no = 1;

        $adjustedIndex = 0; // Ensure the adjusted index is not negative
        foreach ($data as $key => $col) {
            foreach ($col->TransaksiDetail as $index => $item) {
                $adjustedIndex += $index; // Ensure the adjusted index is not negative
                
                $sheet->setCellValue('A' . $rows, $index == 0 ? ($key + 1) : '')->getStyle("A{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('B' . $rows, $index == 0 ? date('d-m-Y', strtotime($col->created_at)) : '')->getStyle("B{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('C' . $rows, $index == 0 ? date('H:i:s', strtotime($col->created_at)) : '')->getStyle("C{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $columnIndex = 3; // Kolom dimulai dari D

                foreach ($harga_layanan as $key => $value) {
                    $startColumnSend = chr(65 + $columnIndex); // D
                    $startColumnReturn = chr(65 + $columnIndex + 1); // E
                    $startColumnRewash = chr(65 + $columnIndex + 2); // F
                    $startColumnRemark = chr(65 + $columnIndex + 3); // G
                    $sheet->setCellValue("{$startColumnSend}" . $rows,  ($item->kode_layanan == $value->kode ? ($item->jumlah ?? 0) : '' ))->getStyle("{$startColumnSend}{$rows}")->applyFromArray($textRight);
                    $sheet->setCellValue("{$startColumnReturn}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_special_treatment ?? 0) : '' ))->getStyle("{$startColumnReturn}{$rows}")->applyFromArray($textRight);
                    $sheet->setCellValue("{$startColumnRewash}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_rewash ?? 0) : '' ))->getStyle("{$startColumnRewash}{$rows}")->applyFromArray($textRight);
                    $sheet->setCellValue("{$startColumnRemark}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_remark ?? 0) : '' ))->getStyle("{$startColumnRemark}{$rows}")->applyFromArray($textRight);
                    $columnIndex += 4; // Karena Anda menggabungkan 4 kolom untuk setiap data layanan
                }
            $rows++;
            $no++;
            }
        }

        $lastColumn = chr(65 + $columnIndex - 1); // Menghasilkan huruf kolom terakhir
        $styleRange = "A6:{$lastColumn}" . (count($data) + 8 + $adjustedIndex);
        $sheet->getStyle($styleRange)->applyFromArray($styleArray);

        $json = [];
        $paymentDescription = [];

        foreach ($data as $col) {
            foreach ($col->TransaksiDetail as $item) {
                $kode_layanan = $item->kode_layanan;

                foreach ($harga_layanan as $key => $row) {
                    $kode = $row->kode;

                    if (!isset($json[$kode])) {
                        $json[$kode] = [
                            'jumlah' => 0,
                            'qty_special_treatment' => 0,
                            'qty_rewash' => 0,
                            'qty_remark' => 0,
                        ];
                    }

                    if (!isset($paymentDescription[$kode])) {
                        $paymentDescription[$kode] = [
                            'towel' => 0,
                            'price' => $row->harga,
                            'description' => $row->nama,
                        ];
                    }

                    // Update the counts based on the current item
                }

                // Check if $kode_layanan is set before accessing the arrays
                if (isset($json[$kode_layanan])) {
                    $json[$kode_layanan]['jumlah'] += $item->jumlah ?? 0;
                    $json[$kode_layanan]['qty_special_treatment'] += $item->qty_special_treatment ?? 0;
                    $json[$kode_layanan]['qty_rewash'] += $item->qty_rewash ?? 0;
                    $json[$kode_layanan]['qty_remark'] += $item->qty_remark ?? 0;
                }

                if (isset($paymentDescription[$kode_layanan])) {
                    $paymentDescription[$kode_layanan]['towel'] += $item->jumlah ?? 0;
                }
            }
        }

        
        $json = json_encode($json);
        $jsonDecode = json_decode($json, true);

        $sheet->mergeCells("A{$rows}:C{$rows}")->getStyle("A{$rows}:C{$rows}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$rows}", 'TOTAL TOWEL')->getStyle("A{$rows}")->applyFromArray($textCenter);
        $columnIndex = 3; // Kolom dimulai dari D
        foreach ($jsonDecode as $key => $value) {
            $startColumnSend = chr(65 + $columnIndex); // D
            $startColumnReturn = chr(65 + $columnIndex + 1); // E
            $startColumnRewash = chr(65 + $columnIndex + 2); // F
            $startColumnRemark = chr(65 + $columnIndex + 3); // G
            $sheet->setCellValue("{$startColumnSend}" . $rows,  ($value['jumlah'] ?? 0))->getStyle("{$startColumnSend}{$rows}")->applyFromArray($textRight);
            $sheet->setCellValue("{$startColumnReturn}" . $rows, ($value['qty_special_treatment'] ?? 0))->getStyle("{$startColumnReturn}{$rows}")->applyFromArray($textRight);
            $sheet->setCellValue("{$startColumnRewash}" . $rows, ($value['qty_rewash'] ?? 0))->getStyle("{$startColumnRewash}{$rows}")->applyFromArray($textRight);
            $sheet->setCellValue("{$startColumnRemark}" . $rows, ($value['qty_remark'] ?? 0))->getStyle("{$startColumnRemark}{$rows}")->applyFromArray($textRight);
            $columnIndex += 4; // Karena Anda menggabungkan 4 kolom untuk setiap data layanan
        }

        $row = ($rows + 2);

        $sheet->getStyle("A{$row}:E" . (count($harga_layanan) + $row + 4))->applyFromArray($styleArray);

        $sheet->mergeCells("A{$row}:E{$row}")->getStyle("A{$row}:E{$row}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$row}", 'PAYMENT DESCRIPTION')->getStyle("A{$row}")->applyFromArray($textCenter);
        $callrow = ($row + 1);
        $sheet->mergeCells("A{$callrow}:B{$callrow}")->getStyle("A{$callrow}:B{$callrow}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$callrow}", 'Description')->getStyle("A{$callrow}")->applyFromArray($textCenter);
        $sheet->setCellValue("C{$callrow}", 'Towel')->getStyle("C{$callrow}")->applyFromArray($textCenter)->getFont()->setBold(true);
        $sheet->setCellValue("D{$callrow}", 'Price')->getStyle("D{$callrow}")->applyFromArray($textCenter)->getFont()->setBold(true);
        $sheet->setCellValue("E{$callrow}", 'Amount')->getStyle("E{$callrow}")->applyFromArray($textCenter)->getFont()->setBold(true);


        $paymentDescription = json_encode($paymentDescription);

        // Assuming $json is a JSON string, decode it into an array
        $jsonArray = json_decode($paymentDescription, true);

        $totalAmount = 0;
        $cell = ($callrow + 1);
        
        foreach ($jsonArray as $key => $col) {
            $towel = $col['towel'];
            $price = $col['price'];
            $amount = $towel * $price;
            $totalAmount += $amount;

            $sheet->mergeCells("A{$cell}:B{$cell}")->getStyle("A{$cell}:B{$cell}");
            $sheet->setCellValue("A{$cell}", $col['description'])->getStyle("A{$cell}")->applyFromArray($textCenter);
            $sheet->setCellValue("C{$cell}", $towel)->getStyle("C{$cell}")->applyFromArray($textRight);
            $sheet->setCellValue("D{$cell}", $price)->getStyle("D{$cell}")->applyFromArray($textRight);
            $sheet->setCellValue("E{$cell}", $amount)->getStyle("E{$cell}")->applyFromArray($textRight);
            $cell++;
        }

        $sheet->mergeCells("A{$cell}:D{$cell}")->getStyle("A{$cell}:D{$cell}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$cell}", 'Total Ammount')->getStyle("A{$cell}")->applyFromArray($textCenter);
        $sheet->setCellValue("E{$cell}", $totalAmount)->getStyle("E{$cell}")->applyFromArray($textRight)->getFont()->setBold(true);
        $sheetcell = ($cell + 1);
        $sheet->mergeCells("A{$sheetcell}:D{$sheetcell}")->getStyle("A{$sheetcell}:D{$sheetcell}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$sheetcell}", 'PPN 10%')->getStyle("A{$sheetcell}")->applyFromArray($textCenter);
        $sheet->setCellValue("E{$sheetcell}", '-')->getStyle("E{$sheetcell}")->applyFromArray($textRight)->getFont()->setBold(true);
        $sheetcell = ($sheetcell + 1);
        $sheet->mergeCells("A{$sheetcell}:D{$sheetcell}")->getStyle("A{$sheetcell}:D{$sheetcell}")->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue("A{$sheetcell}", 'Total PAY')->getStyle("A{$sheetcell}")->applyFromArray($textCenter);
        $sheet->setCellValue("E{$sheetcell}", 0)->getStyle("E{$sheetcell}")->applyFromArray($textRight)->getFont()->setBold(true);

        

        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan " . $corporate->user->name . " Periode "  . ($request->startdate ? (date('M Y', strtotime($request->startdate)) ) : '-') .  ".xlsx";

        // Save the file
        $writer->save($filename);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output file to the browser
        $writer->save('php://output');
    }

    public function exportPdf(Request $request){
        $id = $request->id;

        $data = Transaksi::with('transaksiDetail')
        ->where('transaksis.corporate_id', $id);

        // Check if startdate is provided in the request
        if ($request->filled('startdate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            // Filter data based on the startdate only
            $data->whereMonth('transaksis.created_at', $startDate->month)
                ->whereYear('transaksis.created_at', $startDate->year);
        } else {
            return redirect(route('laporan.corporate'));
        }


        $data = $data->get();

        $corporate = Corporate::with('user')->find($id);

        // Retrieve the unique harga_id values from transaksi_detail
        $hargaIds = $data->pluck('transaksiDetail.*.harga_id')->flatten()->unique()->toArray();

        // Fetch the corresponding harga_layanan records
        $harga_layanan = Harga::whereIn('id', $hargaIds)->get();

        if (!$data) {
            return abort(404);
        }

        $json = [];
        $paymentDescription = [];

        foreach ($data as $col) {
            foreach ($col->TransaksiDetail as $item) {
                $kode_layanan = $item->kode_layanan;

                foreach ($harga_layanan as $key => $row) {
                    $kode = $row->kode;

                    if (!isset($json[$kode])) {
                        $json[$kode] = [
                            'jumlah' => 0,
                            'qty_special_treatment' => 0,
                            'qty_rewash' => 0,
                            'qty_remark' => 0,
                        ];
                    }

                    if (!isset($paymentDescription[$kode])) {
                        $paymentDescription[$kode] = [
                            'towel' => 0,
                            'price' => $row->harga,
                            'description' => $row->nama,
                        ];
                    }

                    // Update the counts based on the current item
                }

                // Check if $kode_layanan is set before accessing the arrays
                if (isset($json[$kode_layanan])) {
                    $json[$kode_layanan]['jumlah'] += $item->jumlah ?? 0;
                    $json[$kode_layanan]['qty_special_treatment'] += $item->qty_special_treatment ?? 0;
                    $json[$kode_layanan]['qty_rewash'] += $item->qty_rewash ?? 0;
                    $json[$kode_layanan]['qty_remark'] += $item->qty_remark ?? 0;
                }

                if (isset($paymentDescription[$kode_layanan])) {
                    $paymentDescription[$kode_layanan]['towel'] += $item->jumlah ?? 0;
                }
            }
        }


        $json = json_encode($json);
        $paymentDescription = json_encode($paymentDescription);

        // dd($paymentDescription);
        // return view('laporan.corporate.detail', compact('id','data', 'corporate', 'harga_layanan'));

        $pdf = PDF::loadView("laporan.corporate.pdf", compact('id','data', 'corporate', 'harga_layanan', 'json', 'paymentDescription'));
        return $pdf->stream('Detail Laporan - ' . $corporate->user->name . '.pdf');
    }
    
}
