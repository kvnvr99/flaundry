<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Harga;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HargaController extends Controller {

    protected $model, $role;

    public function __construct(Harga $harga) {
        $this->model = new BaseRepository($harga);
        $this->middleware('auth');
    }

    public function index() {
        return view('master-data.harga.index');
    }

    public function getData() {
        $data = Harga::all();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('harga.edit', $data->id),
                'url_detail' => route('harga.detail', $data->id),
                'url_destroy' => route('harga.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.harga.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('harga');
        }
    }

    public function store(Request $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);

            $harga = $this->model->store($data);
            // $harga->syncRoles($request->role);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('harga');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = $this->model->find($id);
            return view('master-data.harga.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('harga');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('master-data.harga.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('harga');
        }
    }

    public function update(Request $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);
            $user = $this->model->update($request->id, $data);
            // $user->syncRoles($request->role);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('harga');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('harga');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('harga');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('harga');
        }
    }
}
