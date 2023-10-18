<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ExpedisiAntarRequest;
use App\Models\ExpedisiAntar;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

class ExpedisiAntarController extends Controller {

    protected $model, $role;

    public function __construct(ExpedisiAntar $ExpedisiAntar) {
        $this->model = new BaseRepository($ExpedisiAntar);
        $this->middleware('auth');
    }

    public function index() {
        $kurir = DB::table('users')
        ->select('users.*')
        ->where('role_id', '=', 6)
        ->get();

        return view('transaksi.expedisi-antar.index',['kurir'=>$kurir]);
    }

    public function getData() {
        //belum difilter untuk orang yg menantar
        $data = DB::table('transaksis')
        ->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name',DB::raw("(case when transaksis.deliver_at is null then '-' ELSE 'Selesai' END) as status"))
        ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
        ->whereNotNull('transaksis.qc_id')
        ->whereNotNull('transaksis.cuci_id')
        ->whereNotNull('transaksis.pengeringan_id')
        ->whereNotNull('transaksis.setrika_id')
        ->whereNull('transaksis.deleted_at') 
        // ->whereNull('transaksis.deliver_at') 
        ->orderBy('transaksis.id', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                // 'url_edit' => route('expedisi-antar.edit', $data->id),
                // 'url_detail' => route('expedisi-antar.detail', $data->id)
                'url_batal' => route('expedisi-antar.destroy', $data->id),
                'url_kurir'=> $data->id,
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    // public function create() {
    //     try {
    //         $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
    //         return view('transaksi.expedisi-antar.form', compact('roles'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function store(Request $request)
    {  

        $waktu = date('Y-m-d H:i:s');
        
        $transaksi = DB::update("update transaksis set deliver_at = '".$waktu. "' WHERE transaksis.id =".$request->transaksi_id);
                         
        return Response()->json($transaksi);
 
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('transaksis')
            ->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name')
            ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
            ->join('users', 'users.id', '=', 'members.user_id', 'left')
            ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
            ->where('transaksis.id',$id)
            ->get();

            return view('transaksi.expedisi-antar.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-antar');
        }
    }

    // public function detail($id) {
    //     try {
    //         $data['detail'] = $this->model->find($id);

    //         return view('transaksi.expedisi-antar.detail', compact('data'));
    //     } catch (\Throwable $e) {
    //         Alert::toast($e->getMessage(), 'error');
    //         return redirect()->route('top-up');
    //     }
    // }

    public function update(ExpedisiAntarRequest $request) {
        try {
            // $data = $request->except(['_token', '_method', 'id', 'nama', 'nominal_old']);
            // $user = $this->model->update($request->id, $data);
            
            // $selisih = $request->nominal-$request->nominal_old;
            // echo($selisih);
            // exit();

            DB::update('update transaksis set deliver_at = '.$request->deliver_by. ' WHERE transaksis.id ='.$request->id);

            Alert::toast('Berhasil Disimpan', 'success');
            return redirect()->route('expedisi-antart');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-antarx');
        }
    }

    public function destroy($id) {
        try {

            DB::update("update transaksis set deliver_at = null WHERE transaksis.id=".$id);
            
            Alert::toast(' Pengantaran Berhasil Dibatalkan', 'success');
            return redirect()->route('expedisi-antar');
        } catch (\Throwable $e) {
            echo 'gagal';
            exit();
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('expedisi-antar');
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

        $info  = DB::table('transaksis')->select('transaksis.*', 'users.name', 'users_deliver.name as deliver_name')
        ->join('members', 'members.id', '=', 'transaksis.member_id', 'left')
        ->join('users', 'users.id', '=', 'members.user_id', 'left')
        ->join('users as users_deliver', 'users_deliver.id', '=', 'transaksis.deliver_by', 'left')
        ->whereNull('transaksis.deleted_at') 
        ->where('transaksis.id',$request->id)
        ->orderBy('transaksis.id', 'ASC')
        ->first();
      
        return Response()->json($info);
        
    }
}
