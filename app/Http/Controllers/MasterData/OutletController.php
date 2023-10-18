<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\OutleteRequest;
use App\Http\Requests\OutleteRequestUpdate;
use App\Models\Outlet;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class OutletController extends Controller {

    protected $model, $role;

    public function __construct(Outlet $outlet) {
        $this->model = new BaseRepository($outlet);
        $this->middleware('auth');
    }

    public function index() {
        return view('master-data.outlet.index');
    }

    public function getData() {
        $data = Outlet::all();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('outlet.edit', $data->id),
                'url_detail' => route('outlet.detail', $data->id),
                'url_destroy' => route('outlet.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.outlet.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('outlet');
        }
    }

    public function store(OutleteRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);

            $outlet = $this->model->store($data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('outlet');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = $this->model->find($id);
            return view('master-data.outlet.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('outlet');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('master-data.outlet.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('outlet');
        }
    }

    public function update(OutleteRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);
            $user = $this->model->update($request->id, $data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('outlet');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('outlet');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('outlet');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('outlet');
        }
    }
}
