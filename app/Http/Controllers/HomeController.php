<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(User::select('*')->where('remark','=','1'))
                ->addColumn('action',  function ($row) {

                    $btn = '<a href="javascript:void(0)" onClick="editUser(' . $row->id . ')" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm">Edit</a>';

                    $btn = $btn . ' <a href="javascript:void(0)" onClick="deleteUser(' . $row->id . ')" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('home');
    }
    public function register_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'role_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $users = new User;

            $users->name = $request->input('name');
            $users->email = strtolower($request->input('email'));
            $users->password = Hash::make($request['password']);
            $users->remark = '1';
            $users->save();
            // $users->addRole($request->role_id);
            return response()->json([
                'success' => 'account added successfully'
            ]);
        }
    }
    public function edit_user(Request $request)
    {
        $where = array('users.id' => $request->id);
        $user  = User::where($where)->first();
        return Response()->json($user);
    }
    public function update_user(Request $request)
    {
        if (!empty($request->password)) {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $pass = Hash::make($request['password']);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $users = User::find($request->id);
            $users->name = $request->input('name');
            $users->email = $request->input('email');
            if (!empty($request->password)) {
                $users->password = $pass;
            }
            // $users->roles()->sync($request->role_id);
        }
        $users->save();
        return response()->json([
            'success' => 'account updated successfully'
        ]);
    }
    public function delete_user($id)
    {
        $users = User::find($id);
        $users->remark = "0";
        $users->update();
        return response()->json([
            'success' => 'account deleted successfully'
        ]);
    }
}
