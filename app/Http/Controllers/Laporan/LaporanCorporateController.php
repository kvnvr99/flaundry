<?php

namespace App\Http\Controllers\Laporan;

use DataTables;
use Carbon\Carbon;
use App\Models\Corporate;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanCorporateController extends Controller
{
    public function index(){
        return view('laporan.corporate.index');
    }

    public function getData(Request $request) {
        $data = Corporate::select('corporate.*', 'users.name')
            ->leftJoin('transaksis', 'transaksis.corporate_id', '=', 'corporate.id')
            ->leftJoin('users', 'users.id', '=', 'corporate.user_id')
            ->groupBy('corporate.id')
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
        $data = Transaksi::with('TransaksiDetail')
            ->where('transaksis.corporate_id', $id)
            ->groupBy('transaksis.id');

        // Check if startdate and enddate are provided in the request
        if ($request->filled('startdate') && $request->filled('enddate')) {
            $startDate = Carbon::createFromFormat('M-Y', $request->startdate)->startOfMonth();
            $endDate = Carbon::createFromFormat('M-Y', $request->enddate)->endOfMonth();

            $data->whereBetween('transaksis.created_at', [$startDate, $endDate]);
        }else{
            return redirect(route('laporan.corporate'));
        }

        $data = $data->get();

        $corporate = Corporate::with('user')->find($id);

        return view('laporan.corporate.detail', compact('data', 'corporate'));
    }

    
}
