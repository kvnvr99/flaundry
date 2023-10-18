<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PermintaanLaundryRequest;
use App\Http\Requests\PermintaanLaundryRequestUpdate;
use App\Models\PermintaanLaundry;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

use DB;
use Auth;

class PermintaanLaundryController extends Controller {

    protected $model, $role;

    public function __construct(PermintaanLaundry $PermintaanLaundry) {
        $this->model = new BaseRepository($PermintaanLaundry);
        $this->middleware('auth');
    }

    public function index() {
        return view('member.permintaan-laundry.index');
    }

    public function getData() {

        $member_id = DB::table('members')->where('user_id', Auth::user()->id)->first()->id;

        $data = PermintaanLaundry::where('member_id','=',$member_id);
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('permintaan-laundry.edit', $data->id),
                'url_detail' => route('permintaan-laundry.detail', $data->id),
                'url_destroy' => route('permintaan-laundry.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            
            $data['info'] = DB::table('members')
            ->select('members.*')
            ->where('members.user_id', Auth::user()->id)
            ->first();

            return view('member.permintaan-laundry.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('permintaan-laundry');
        }
    }

    public function store(PermintaanLaundryRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama_parfume', 'nama_layanan']);

            $PermintaanLaundry = $this->model->store($data);
            Alert::toast('Berhasil Disimpan', 'success');
            return redirect()->route('permintaan-laundry');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = DB::table('permintaan_laundries')
            ->select('permintaan_laundries.*', 'parfumes.nama as nama_parfume', 'parfumes.id as parfume_id', 'layanans.id as layanan_id', 'layanans.nama as nama_layanan')
            ->join('layanans', 'layanans.id', '=', 'permintaan_laundries.layanan_id','left')
            ->join('parfumes', 'parfumes.id', '=', 'permintaan_laundries.parfume_id','left')
            ->where('permintaan_laundries.id',$id)
            ->get();

            return view('member.permintaan-laundry.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('permintaan-laundry');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('member.permintaan-laundry.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('permintaan-laundry');
        }
    }

    public function update(PermintaanLaundryRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'nama_parfume', 'nama_layanan']);
            $user = $this->model->update($request->id, $data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('permintaan-laundry');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('permintaan-laundry');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('permintaan-laundry');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('permintaan-laundry');
        }
    }

    public function getDataLayanan() {
        $data = DB::table('layanans')
        ->select('layanans.*')
        ->whereNull('layanans.deleted_at')
        ->orderBy('layanans.nama', 'ASC')
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function getDataParfume() {
        $data = DB::table('parfumes')
        ->select('parfumes.*')
        ->whereNull('parfumes.deleted_at')
        ->orderBy('parfumes.nama', 'ASC')
        ->get();
        
        return DataTables::of($data)
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }
}
