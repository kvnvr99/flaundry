<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestUpdate;
use App\Models\User;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller {

    protected $model, $role;

    public function __construct(User $user) {
        $this->model = new BaseRepository($user);
        $this->middleware('auth');
    }

    public function index() {
        return view('master-data.users.index');
    }

    public function getData() {
        $data = User::where('name', '!=', 'Maintener')->where('is_member', '=', '0')->orderBy('id', 'DESC');
        return DataTables::of($data)
        ->addColumn('roles', function($data){
            $roles = $data->getRoleNames()->toArray();
            $badge = '';
            if($roles){
                $badge .= '<span class="badge badge-secondary m-1">'.implode(' , ', $roles).'</span>';
            }
            return $badge;
        })
        ->addColumn('action', function ($data) {
            if(implode(' , ', $data->getRoleNames()->toArray()) == 'Owner')
                return '';

            return view('component.action', [
                'model' => $data,
                'url_edit' => route('users.edit', $data->id),
                'url_detail' => route('users.detail', $data->id),
                'url_destroy' => route('users.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.users.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('users');
        }
    }

    public function store(UserRequest $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'password_confirm', 'role']);
            $data['password'] = Hash::make($request->password);
            $data['qr_code'] = Hash::make($request->password);
            $data['role_id'] = $request->role;
            $user = $this->model->store($data);
            $user->syncRoles($request->role);
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('users');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = $this->model->find($id);
            $user_role = $data['detail']->roles->first();
            $roles     = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.users.form', compact('data','user_role','roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('users');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] = $this->model->find($id);
            $user_role = $data['detail']->roles->first();
            $roles     = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.users.detail', compact('data','user_role','roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('users');
        }
    }

    public function update(UserRequestUpdate $request) {
        try {
            $data = $request->except(['_token', '_method', 'id', 'password']);
            if ($request['password'] != '') {
                $data['password'] = Hash::make($request->password);
                $data['qr_code'] = Hash::make($request->password);
            }
            $user = $this->model->update($request->id, $data);
            $user->syncRoles($request->role);
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('users');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('users');
        }
    }

    public function destroy($id) {
        try {
            $this->model->softDelete($id);
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('users');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('users');
        }
    }
}
