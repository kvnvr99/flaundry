<?php

namespace App\Http\Controllers\Laporan;

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

class LaporanExpedisiController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Outlet $outlet) {
        $this->model = new BaseRepository($outlet);
        $this->middleware('auth');
    }

    public function index() {
        return view('laporan.laporan_expedisi.index');
    }

    public function getData() {
        $data = DB::table('users')
        ->select('users.*')
        ->whereNull('users.deleted_at')
        ->where('users.role_id','6')
        ->orderBy('users.id', 'ASC')
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
