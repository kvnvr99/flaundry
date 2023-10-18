<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExpedisiJadwalJemputRequest;
use App\Models\ExpedisiJadwalJemput;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

class ExpedisiJadwalJemputController extends Controller {

    protected $model, $role;

    public function __construct(ExpedisiJadwalJemput $ExpedisiJadwalJemput) {
        $this->model = new BaseRepository($ExpedisiJadwalJemput);
        $this->middleware('auth');
    }

    public function index() {
        $kurir = DB::table('users')
        ->select('users.*')
        ->where('role_id', '=', 6)
        ->get();

        return view('transaksi.expedisi-jadwal-jemput.index',['kurir'=>$kurir]);
    }

    public function getData() {
        //belum difilter untuk orang yg menjemput
        $data = DB::table('permintaan_laundries')
        ->select('permintaan_laundries.*', 'users.name', 'users_picked.name as picked_name')
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id')
        ->join('users', 'users.id', '=', 'members.user_id')
        ->join('users as users_picked', 'users_picked.id', '=', 'permintaan_laundries.picked_by', 'left')
        ->whereNull('permintaan_laundries.deleted_at')
        ->orderBy('permintaan_laundries.tanggal', 'ASC')
        ->orderBy('permintaan_laundries.waktu', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                // 'url_edit' => route('expedisi-jadwal-jemput.edit', $data->id),
                // 'url_detail' => route('expedisi-jadwal-jemput.detail', $data->id)

                'url_pilih_kurir'=> $data->id,
                'url_batal' => route('expedisi-jadwal-jemput.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    // public function create() {
    //     try {
    //         $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
    //         return view('transaksi.expedisi-jemput.form', compact('roles'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function store(Request $request)
    {  
        
        $permintaan = DB::update('update permintaan_laundries set picked_by = '.$request->picked_by. ' WHERE permintaan_laundries.id ='.$request->permintaan_laundry_id);
                         
        return Response()->json($permintaan);
 
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('permintaan_laundries')
            ->select('permintaan_laundries.*', 'users.name', 'users_picked.name as picked_name')
            ->join('members', 'members.id', '=', 'permintaan_laundries.member_id')
            ->join('users', 'users.id', '=', 'members.user_id')
            ->join('users as users_picked', 'users_picked.id', '=', 'permintaan_laundries.picked_by', 'left')
            ->where('permintaan_laundries.id',$id)
            ->get();

            return view('transaksi.expedisi-jadwal-jemput.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-jemput');
        }
    }

    // public function detail($id) {
    //     try {
    //         $data['detail'] = $this->model->find($id);

    //         return view('transaksi.expedisi-jemput.detail', compact('data'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function update(ExpedisiJadwalJemputRequest $request) {
        try {
            // $data = $request->except(['_token', '_method', 'id', 'nama', 'nominal_old']);
            // $user = $this->model->update($request->id, $data);
            
            // $selisih = $request->nominal-$request->nominal_old;
            // echo($selisih);
            // exit();

            DB::update('update permintaan_laundries set picked_by = '.$request->picked_by. ' WHERE permintaan_laundries.id ='.$request->id);

            Alert::toast('Berhasil Disimpan', 'success');
            return redirect()->route('expedisi-jadwal-jemput');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-jemput');
        }
    }

    public function destroy($id) {
        try {

            DB::update("update permintaan_laundries set picked_by = null WHERE permintaan_laundries.id=".$id);
            
            Alert::toast('Jadwal Penjemputan Berhasil Dibatalkan', 'success');
            return redirect()->route('expedisi-jadwal-jemput');
        } catch (\Throwable $e) {
            echo 'gagal';
            exit();
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-jadwal-jemput');
        }
    }

    public function getDataUser() {
        $data = DB::table('users')
        ->select('users.*')
        ->where('name', '!=', 'Maintener')
        ->where('role_id', '=', 6)
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function getDataInfo(Request $request) {

        $info  = DB::table('permintaan_laundries')->select('permintaan_laundries.*', 'users.name', 'users_deliver.name as deliver_name')
        ->join('members', 'members.id', '=', 'permintaan_laundries.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('users as users_deliver', 'users_deliver.id', '=', 'permintaan_laundries.picked_by', 'left')
        ->whereNull('permintaan_laundries.deleted_at') 
        ->where('permintaan_laundries.id',$request->id)
        ->orderBy('permintaan_laundries.id', 'ASC')
        ->first();
      
        return Response()->json($info);
        
    }
}
