<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        try{
            return view('master-data.roles.index');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function getData () {
        $data = Role::where('name', '!=', 'Maintener')->latest();
        return DataTables::of($data)
        ->addColumn('permissions', function($data){
            $roles = $data->permissions()->get();
            $badges = '';
            if($data->name == 'Owner'){
                $badges .= '<span class="badge badge-secondary m-1">'.'All Permissions'.'</span>';
            } else {
                foreach ($roles as $key => $role) {
                    $badges .= '<span class="badge badge-secondary m-1">'.$role->name.'</span>';
                }
            }
            return $badges;
        })
        ->addColumn('action', function ($data) {
            if ($data->name == 'Owner')
                return '';

            return view('component.action', [
                'model' => $data,
                'url_edit' => route('roles.edit', $data->id),
                'url_destroy' => route('roles.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'permissions'])
        ->make(true);
    }

    public function create() {
        try{
            $data['permissions'] = Permission::select('name','id')->orderBy('name','asc')->get();
            return view('master-data.roles.form', compact('data'));
        }catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('roles');
        }
    }

    public function store(Request $request) {
        try{
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
            $role->syncPermissions($request->permissions);
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('roles');
        }catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function show($id) {

    }

    public function edit($id) {
        try{
            $data['detail']  = Role::where('id',$id)->first();
            if(!$data['detail'])
                return redirect()->route('roles')->with('error', 'Role tidak ditemukan!');

            $data['role_permission'] = $data['detail']->permissions()->pluck('id')->toArray();
            $data['permissions'] = Permission::select('name','id')->orderBy('name','asc')->get();
            return view('master-data.roles.form', compact('data'));
        }catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('roles');
        }
    }

    public function update(Request $request) {
        try{
            $role = Role::find($request->id);
            if(!$role) {
                Alert::toast('Role tidak ditemukan!', 'error');
                return redirect()->back();
            }
            $update = $role->update([ 'name' => $request->name, 'guard_name' => 'web' ]);
            $role->syncPermissions($request->permissions);
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('roles');
        }catch (\Exception $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy($id) {
        $role = Role::find($id);
        $users = User::role($role->name)->get();
        if($role){
            if (count($users) <= 0) {
                $delete = $role->delete();
                $perm   = $role->permissions()->delete();
                return response()->json($delete, 200);
            }
            return 'false';
        }
        return 'false';
    }
}
