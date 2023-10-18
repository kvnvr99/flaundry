<?php

namespace App\Http\Controllers\Infogram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Outlet;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;

class InfogramOutletController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Outlet $outlet) {
        $this->model = new BaseRepository($outlet);
        $this->middleware('auth');
    }

    public function index() {
        return view('infogram.outlet');
    }

    public function getData() {
        $today = date('Y-m-d');

        $data = DB::table('transaksis')
        ->select(DB::raw("xoutlets.nama, count(transaksis.id) as nota_masuk, 
            ( SELECT count( tr_qc.status ) FROM transaksis tr_qc WHERE tr_qc.status = 'qc' AND tr_qc.kasir_id = outlets.id AND left(transaksis.created_at,10) = '$today') AS total_qc, 
            ( SELECT count( tr_cuci.status ) FROM transaksis tr_cuci WHERE tr_cuci.status = 'cuci' AND tr_cuci.kasir_id = outlets.id AND left(transaksis.created_at,10) = '$today') AS total_cuci, 
            ( SELECT count( tr_pengeringan.status ) FROM transaksis tr_pengeringan WHERE tr_pengeringan.status = 'pengeringan' AND tr_pengeringan.kasir_id = outlets.id AND left(transaksis.created_at,10) = '$today') AS total_pengeringan,
            ( SELECT count( tr_setrika.status ) FROM transaksis tr_setrika WHERE tr_setrika.status = 'setrika' AND tr_setrika.kasir_id = outlets.id AND left(transaksis.created_at,10) = '$today') AS total_setrika,
            ( SELECT count( tr_kirim.status ) FROM transaksis tr_kirim WHERE tr_kirim.deliver_by IS NOT NULL AND tr_kirim.status='expedisi' AND tr_kirim.kasir_id = outlets.id AND left(transaksis.created_at,10) = '$today') AS total_kirim"))
        
        ->join('outlets', 'outlets.id', '=', 'transaksis.kasir_id', 'left')

        ->whereNull('transaksis.deleted_at')
        ->whereNotNull('transaksis.permintaan_laundry_id')
        ->whereRaw("left(transaksis.created_at,10) = '$today'")
        ->orderBy('transaksis.id', 'ASC')
        ->groupBy('outlets.nama','outlets.id','transaksis.created_at')

        ->get();

        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                // 'url_edit' => route('user-member.edit', $data->id),
                // 'url_detail' => route('user-member.detail', $data->id),
                // 'url_destroy' => route('user-member.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

}
