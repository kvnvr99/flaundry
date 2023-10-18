<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ParfumeRequest;
use App\Http\Requests\ParfumeRequestUpdate;
use App\Models\Parfume;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ParfumeController extends Controller {

    protected $model, $role;

    public function __construct(Parfume $parfume) {
        $this->model = new BaseRepository($parfume);
        $this->middleware('auth');
    }

    public function index() {
        return view('master-data.parfume.index');
    }

    public function getData() {
        $data = Parfume::all();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('parfume.edit', $data->id),
                'url_detail' => route('parfume.detail', $data->id),
                'url_destroy' => route('parfume.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.parfume.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('parfume');
        }
    }

    public function store(ParfumeRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);

            $parfume = $this->model->store($data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('parfume');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = $this->model->find($id);
            return view('master-data.parfume.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('parfume');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);

            return view('master-data.parfume.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('parfume');
        }
    }

    public function update(ParfumeRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id']);
            $user = $this->model->update($request->id, $data);
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('parfume');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('parfume');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('parfume');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('parfume');
        }
    }
}
