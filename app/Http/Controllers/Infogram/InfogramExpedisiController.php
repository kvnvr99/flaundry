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

class InfogramExpedisiController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Outlet $outlet) {
        $this->model = new BaseRepository($outlet);
        $this->middleware('auth');
    }

    public function index() {
        return view('infogram.expedisi');
    }

    public function getData() {
        $today = date('Y-m-d');

        $data = DB::table('transaksis')
        ->select(DB::raw("transaksis.*, users.name, permintaan_laundries.alamat, permintaan_laundries.picked_at, (SELECT users.name FROM users WHERE users.id = permintaan_laundries.picked_by) picked_by, (SELECT users.name FROM users WHERE users.id = transaksis.deliver_by) deliver_by"))
        // ->select('transaksis.*', 'users.name', 'permintaan_laundries.alamat', '')
        ->join('permintaan_laundries', 'permintaan_laundries.id', '=', 'transaksis.permintaan_laundry_id', 'left')
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        // ->join('users as kurir', 'kurir.id', '=', 'permintaan_laundries.picked_by', 'left')
        ->whereNull('transaksis.deleted_at')
        ->whereNotNull('transaksis.permintaan_laundry_id')
        ->whereRaw("left(transaksis.created_at,10) = '$today'")
        ->orderBy('transaksis.id', 'ASC')
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
