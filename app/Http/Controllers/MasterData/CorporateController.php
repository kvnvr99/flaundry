<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use App\Models\User;
use App\Models\Corporate;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules\Password;

class CorporateController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Corporate $corporate, User $user) {
        $this->model = new BaseRepository($corporate);
        $this->user = new BaseRepository($user);
        $this->middleware('auth');
    }

    public function index(){
        return view('master-data.corporate.index');
    }

    public function getData(Request $request){
        $data = Corporate::query()->with('user')->get();

        // if ($request->eskul) {
        //     $data = $data->where(function ($query) use ($request) {
        //         if ($request->eskul != "") {
        //             $query->where("eskul_id", $request->eskul);
        //         }
        //     });
        // }

        // dd($data);

        return DataTables::of($data)
        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('user_corporate.edit', $data->id),
                'url_detail' => route('user_corporate.detail', $data->id),
                'url_destroy' => route('user_corporate.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make([true]);
    }

    
    public function add(){
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.corporate.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user_corporate');
        }
    }
    
    public function save(Request $request){
        
        try {
            $request->validate([
                'name'                  => 'required|max:50',
                'email'                 => 'sometimes|required|unique:users|max:50|email',
                'password_confirmation' => 'required',
                'password'              => ['required', 'confirmed', Password::min(8)],
                'phone'                 => 'required|numeric|digits_between:8,14',
                'address'               => 'required|max:500'
            ]);
            
            $roles = Role::where('name', 'Corporate')->first();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'qr_code' => Hash::make($request->password),
                'is_corporate' => 1,
                'role_id' => $roles->id,
                'created_by' => Auth::user()->id,
            ]);
            $user->syncRoles($roles->id);
            $data = Corporate::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'balance' => 0,
                'created_by' => Auth::user()->id,
            ]);
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('user_corporate');
        } catch (Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }
    
    
    public function edit($id) {
        try {
            $data['detail'] = Corporate::with('user')->find($id);
            if(empty($data['detail'])){
                Alert::toast('User Tidak Ditemukan', 'error');
                return redirect()->route('user_corporate');
            }
            return view('master-data.corporate.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user_corporate');
        }
    }
    
    public function update(Request $request)
    {
        try{
            $data = Corporate::find($request->corporate_id);
            $rules = [
                'name'                  => 'required|max:50',
                'email'                 => 'sometimes|required|max:50|email|unique:users,email,'.$data->user_id,
                'phone'                 => 'required|numeric|digits_between:8,14',
                'address'               => 'required|max:500'
            ];

            if ($request->password) {
                $rules['password_confirmation'] = 'required';
                $rules['password'] = ['required', 'confirmed', Password::min(8)];
            }
            $request->validate($rules);
            
            $update_corporate = [
                'user_id' => $data->user_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'balance' => 0,
                'created_by' => Auth::user()->id,
            ];

            $data->update($update_corporate);

            
            $roles = Role::where('name', 'Corporate')->first();
            $user = User::find($data->user_id);
            // dd($user);
            $update_user = [
                'name' => $request->name,
                'email' => $request->email,
                'is_corporate' => 1,
                'role_id' => $roles->id,
                'created_by' => Auth::user()->id,
            ];
            $user->syncRoles($roles->id);

            if ($request->password) {
                $update_user['password'] = Hash::make($request->password);
                $update_user['qr_code'] = Hash::make($request->password);
            }

            $user->update($update_user);
            Alert::toast($request->name.' Berhasil Diperbarui', 'success');
            return redirect()->route('user_corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function detail($id) {
        try {
            $data['detail'] =  Corporate::with('user')->find($id);
            if(empty($data['detail'])){
                Alert::toast('User Tidak Ditemukan', 'error');
                return redirect()->route('user_corporate');
            }
            return view('master-data.member.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user_corporate');
        }
    }

    public function destroy(Request $request,$id)
    {
        try {
            $data = Corporate::find($id);
            $user = User::where('id',$data->user_id)->first();

            $data->delete();
            $user->delete();
            Alert::toast($request->name.' Berhasil Dihapus', 'success');
            return redirect()->route('user_corporate');
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user_corporate');
        }
    }

}
