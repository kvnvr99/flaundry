<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\MemberRequest;
use App\Http\Requests\MemberRequestUpdate;
use App\Models\User;
use App\Models\Member;
use App\Repositories\BaseRepository;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    protected $model, $user, $role;

    public function __construct(Member $member, User $user) {
        $this->model = new BaseRepository($member);
        $this->user = new BaseRepository($user);
        $this->middleware('auth');
    }

    public function index() {
        return view('master-data.member.index');
    }

    public function getData() {
        $data = Member::with('user')->get();
        return DataTables::of($data)

        ->addColumn('action', function ($data) {
            return view('component.action', [
                'model' => $data,
                'url_edit' => route('user-member.edit', $data->id),
                'url_detail' => route('user-member.detail', $data->id),
                'url_destroy' => route('user-member.destroy', $data->id)
            ]);
        })
        ->addIndexColumn()
        ->rawColumns(['action', 'roles'])
        ->make(true);
    }

    public function create() {
        try {
            $roles = Role::where('name', '!=', 'Maintener')->pluck('name','id');
            return view('master-data.member.form', compact('roles'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user-member');
        }
    }

    public function store(MemberRequest $request) {
        DB::beginTransaction();
        try {
            $user = $request->except(['_token', '_method', 'id', 'phone', 'address', 'balance']);
            $user['password'] = Hash::make($request->password);
            $user['qr_code'] = Hash::make($request->password);
            $user['is_member'] = '1';
            $user_id = $this->user->store($user);

            $member = $request->except(['_token', '_method', 'id', 'email', 'password', 'name']);
            $member['created_by'] = Auth::user()->name;
            $member['user_id'] = $user_id->id;
            $member['balance'] = 0;
            $this->model->store($member);

            DB::commit();
            Alert::toast($request->name.' Berhasil Disimpan', 'success');
            return redirect()->route('user-member');
        } catch (\Throwable $e) {
            DB::rollback();
            Alert::toast($e->getMessage(), 'error');
            return back();
        }
    }

    public function edit($id) {
        try {
            $data['detail'] = Member::with('user')->find($id);
            if(empty($data['detail'])){
                Alert::toast('User Tidak Ditemukan', 'error');
                return redirect()->route('user-member');
            }
            return view('master-data.member.form', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user-member');
        }
    }

    public function detail($id) {
        try {
            $data['detail'] =  Member::with('user')->find($id);
            if(empty($data['detail'])){
                Alert::toast('User Tidak Ditemukan', 'error');
                return redirect()->route('user-member');
            }
            return view('master-data.member.detail', compact('data'));
        } catch (\Throwable $e) {
            Alert::toast($e->getMessage(), 'error');
            return redirect()->route('user-member');
        }
    }

    public function update(MemberRequestUpdate $request) {
        DB::beginTransaction();
        try {
            $user = $request->except(['_token', '_method', 'id', 'password']);
            if ($request['password'] != '') {
                $user['password'] = Hash::make($request->password);
                $user['qr_code'] = Hash::make($request->password);
            }
            $user['is_member'] = '1';
            $this->user->update($request->id, $user);

            $member = $request->except(['_token', '_method', 'id', 'email', 'password', 'name']);
            $member['created_by'] = Auth::user()->name;
            // $member['user_id'] = $user_id->id;
            $this->model->update($request->member_id, $member);

            DB::commit();
            Alert::toast($request->nama.' Berhasil Disimpan', 'success');
            return redirect()->route('user-member');
        } catch (\Throwable $e) {
            DB::rollback();
            Alert::toast($e->getMessage(), 'error');
            return back();
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
