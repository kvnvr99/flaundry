<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\MemberRequest;
use App\Http\Requests\MemberRequestUpdate;
use App\Models\User;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;

class LaporanAbsenController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Member $member, User $user) {
        $this->model = new BaseRepository($member);
        $this->user = new BaseRepository($user);
        $this->middleware('auth');
    }

    public function index() {
        return view('laporan.laporan_absen.index');
    }

    public function getData() {
        $data = DB::table('users')
        ->select(DB::raw("users.*, ifnull(( SELECT log_activities.created_at FROM log_activities WHERE log_activities.user_id = users.id order by log_activities.id desc limit 1),'-') last_login, ifnull(( SELECT log_activities.created_at FROM log_activities WHERE log_activities.user_id = users.id order by log_activities.id desc limit 1),'-') last_logout"))
        ->whereNull('users.deleted_at')
        ->whereRaw("users.id NOT in (SELECT user_id FROM members)")
        ->whereRaw("users.name != 'maintener'")
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
