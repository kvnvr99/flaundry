<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\TopupRequest;
use App\Http\Requests\TopupRequestUpdate;
use App\Models\Topup;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;

class TopupController extends Controller {

    protected $model, $role;

    public function __construct(Topup $topup) {
        $this->model = new BaseRepository($topup);
        $this->middleware('auth');
    }

    public function index() {
        return view('transaksi.topup.index');
    }

    public function getData() {
        $data = DB::table('topups')
        ->select('topups.*', 'users.name')
        ->join('members', 'members.id', '=', 'topups.member_id')
        ->join('users', 'users.id', '=', 'members.user_id')
        ->whereNull('topups.deleted_at')
        ->orderBy('users.name', 'ASC')
        ->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('top-up.edit', $data->id),
                'url_detail' => route('top-up.detail', $data->id),
                'url_destroy' => route('top-up.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('transaksi.topup.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('top-up');
        }
    }

    public function store(TopupRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id' ,'nama', 'nominal_old']);

            $topup = $this->model->store($data);

            DB::update('update members set balance = balance+'  .$request->nominal. ' where members.id= ' .$request->member_id);

            Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('top-up');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('topups')
            ->select('topups.*', 'users.name')
            ->join('members', 'members.id', '=', 'topups.member_id')
            ->join('users', 'users.id', '=', 'members.user_id')
            ->where('topups.id',$id)
            ->get();

            return view('transaksi.topup.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('top-up');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('transaksi.topup.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('top-up');
        }
    }

    public function update(TopupRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama', 'nominal_old']);
            $user = $this->model->update($request->id, $data);
            
            $selisih = $request->nominal-$request->nominal_old;
            // echo($selisih);
            // exit();

            DB::update('update members set balance = balance+'  .(float)$selisih. ' where members.id= ' .$request->member_id);

            Alert::toast('Top Up '.$request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('top-up');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('top-up');
        }
    }

    public function destroy($id) {
        try {

            $data = $this->model->find($id);
            DB::update('update members set balance = balance-'  .(float)$data->nominal. ' where members.id= ' .$data->member_id);

            $this->model->softDelete($id);
            
            Alert::toast('Topup Kode '.$data->kode.' Berhasil Dihapus', 'success');
            return redirect()->route('top-up');
        } catch (\Throwable $e) {
            echo 'gagal';
            exit();
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('top-up');
        }
    }

    public function getDataMember() {
        $data = DB::table('members')
        ->select('members.*', 'users.name')
        ->join('users', 'users.id', '=', 'members.user_id')
        ->whereNull('members.deleted_at')
        ->orderBy('users.name', 'ASC')
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }
}
