<?php

namespace App\Http\Controllers\Laporan;

use PDF;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Harga;
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

        // Check if startdate and enddate are provided in the request
        if ($request->filled('startdate') && $request->filled('enddate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
                $endDate = Carbon::createFromFormat('M-Y', $request->enddate)->endOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            $data->whereBetween('transaksis.created_at', [$startDate, $endDate]);
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

        // Check if startdate and enddate are provided in the request
        if ($request->filled('startdate') && $request->filled('enddate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
                $endDate = Carbon::createFromFormat('M-Y', $request->enddate)->endOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            $data->whereBetween('transaksis.created_at', [$startDate, $endDate]);
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
        $sheet->setCellValue('A2', 'Periode : '.($request->startdate || $request->enddate ? (date('M Y', strtotime($request->startdate)) . ' - ' . date('M Y', strtotime($request->enddate))) : '-'))->getStyle('A2')->applyFromArray($textLeft);
        $sheet->setCellValue('A3', 'MONTHLY LAUNDRY')->getStyle('A3')->applyFromArray($textLeft);
        $sheet->setCellValue('A4', 'FRUITS LAUNDRY ')->getStyle('A4')->applyFromArray($textLeft);
        $sheet->mergeCells('A6:A7')->getStyle('A6:A7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('A6', 'No')->getStyle('A6')->getFont()->setBold(true)->applyFromArray($textCenter);
        $sheet->mergeCells('B6:B7')->getStyle('B6:B7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('B6', 'Date')->getStyle('B6')->getFont()->setBold(true)->applyFromArray($textCenter);
        $sheet->mergeCells('C6:C7')->getStyle('C6:C7')->getFont()->setBold(true)->setSize(11);
        $sheet->setCellValue('C6', 'Time')->getStyle('C6')->getFont()->setBold(true)->applyFromArray($textCenter);

        $columnIndex = 3; // Kolom dimulai dari D

        foreach ($harga_layanan as $key => $value) {
            $startColumnSend = chr(65 + $columnIndex); // D
            $startColumnReturn = chr(65 + $columnIndex + 1); // E
            $startColumnRewash = chr(65 + $columnIndex + 2); // F
            $startColumnRemark = chr(65 + $columnIndex + 3); // G

            $sheet->mergeCells("{$startColumnSend}6:{$startColumnRemark}6")->getStyle("{$startColumnSend}6:{$startColumnRemark}6")->getFont()->setBold(true)->setSize(11);
            $sheet->setCellValue("{$startColumnSend}6", $value->nama)->getStyle("{$startColumnSend}6")->getFont()->setBold(true)->applyFromArray($textCenter);

            $sheet->setCellValue("{$startColumnSend}7", 'Send')->getStyle("{$startColumnSend}7")->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnReturn}7", 'Return')->getStyle("{$startColumnReturn}7")->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnRewash}7", 'Rewash')->getStyle("{$startColumnRewash}7")->getFont()->setBold(true);
            $sheet->setCellValue("{$startColumnRemark}7", 'Remark')->getStyle("{$startColumnRemark}7")->getFont()->setBold(true);

            $columnIndex += 4; // Karena Anda menggabungkan 4 kolom untuk setiap data layanan
        }

        $lastColumn = chr(65 + $columnIndex - 1); // Menghasilkan huruf kolom terakhir
        $styleRange = "A6:{$lastColumn}" . (count($data) + 7);

        $sheet->getStyle($styleRange)->applyFromArray($styleArray);
        
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);

        $rows = 8; // Adjusted starting row
        $no = 1;

        foreach ($data as $key => $col) {
            foreach ($col->TransaksiDetail as $index => $item) {
                $sheet->setCellValue('A' . $rows, $index == 0 ? ($key + 1) : '')->getStyle("A{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('B' . $rows, $index == 0 ? date('d-m-Y', strtotime($col->created_at)) : '')->getStyle("B{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('C' . $rows, $index == 0 ? date('H:i:s', strtotime($col->created_at)) : '')->getStyle("C{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $columnIndex = 3; // Kolom dimulai dari D

                foreach ($harga_layanan as $key => $value) {
                    $startColumnSend = chr(65 + $columnIndex); // D
                    $startColumnReturn = chr(65 + $columnIndex + 1); // E
                    $startColumnRewash = chr(65 + $columnIndex + 2); // F
                    $startColumnRemark = chr(65 + $columnIndex + 3); // G
                    $sheet->setCellValue("{$startColumnSend}" . $rows,  ($item->kode_layanan == $value->kode ? ($item->jumlah ?? 0) : 0 ))->getStyle("{$startColumnSend}{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue("{$startColumnReturn}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_special_treatment ?? 0) : 0 ))->getStyle("{$startColumnReturn}{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue("{$startColumnRewash}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_rewash ?? 0) : 0 ))->getStyle("{$startColumnRewash}{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue("{$startColumnRemark}" . $rows, ($item->kode_layanan == $value->kode ? ($item->qty_remark ?? 0) : 0 ))->getStyle("{$startColumnRemark}{$rows}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $columnIndex += 4; // Karena Anda menggabungkan 4 kolom untuk setiap data layanan
                }
            $rows++;
            $no++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "detail.xlsx";

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

        // Check if startdate and enddate are provided in the request
        if ($request->filled('startdate') && $request->filled('enddate')) {
            try {
                $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
                $endDate = Carbon::createFromFormat('M-Y', $request->enddate)->endOfMonth();
            } catch (\Exception $e) {
                // Handle the case where parsing the date fails
                return redirect(route('laporan.corporate'));
            }

            $data->whereBetween('transaksis.created_at', [$startDate, $endDate]);
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
                $json[$kode_layanan]['jumlah'] += $item->jumlah ?? 0;
                $json[$kode_layanan]['qty_special_treatment'] += $item->qty_special_treatment ?? 0;
                $json[$kode_layanan]['qty_rewash'] += $item->qty_rewash ?? 0;
                $json[$kode_layanan]['qty_remark'] += $item->qty_remark ?? 0;

                $paymentDescription[$kode_layanan]['towel'] += $item->jumlah ?? 0;

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
