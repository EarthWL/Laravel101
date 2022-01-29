<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/user');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'integer'],
            'password' => 'required|confirmed|min:8',
        ]);
        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $createUser = new User;
            $createUser->name = $request->name;
            $createUser->username = $request->username;
            $createUser->email = $request->email;
            $createUser->role = $request->role;
            $createUser->password = Hash::make($request->password);
            $query = $createUser->save();
            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'New User has been successfully saved']);
            }
        }
        
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $uid = Auth::user()->id;
        $users = User::all()->whereNotIn('id', $uid);
        return DataTables::of($users)
                        ->addIndexColumn()
                        ->addColumn('actions', function($row){
                            return '<div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" data-id="'.$row['id'].'" id="userEdit"><i class="bi bi-pencil-square"></i><button>
                                        <button class="btn btn-sm btn-outline-danger" data-id="'.$row['id'].'" id="userDel"><i class="bi bi-recycle"></i></i><button>
                                    </div>';
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }

    public function trash()
    {
        $users = User::onlyTrashed();
        return DataTables::of($users)
                        ->addIndexColumn()
                        ->addColumn('actions', function($row){
                            return '<div class="btn-group">
                                        <button class="btn btn-sm btn-outline-success" data-id="'.$row['id'].'" id="userRestore"><i class="bi bi-arrow-clockwise"></i><button>
                                        <button class="btn btn-sm btn-danger" data-id="'.$row['id'].'" id="userDestroy"><i class="bi bi-trash"></i><button>
                                    </div>';
                        })
                        ->rawColumns(['actions'])
                        ->make(true);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user_id = $request->user_id;
        $user_detail = User::find($user_id);
        return response()->json(['details'=>$user_detail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = $request->uid;

        if(!$request->password){

            $validator = \Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,'.$user_id,
                'email' => 'required|string|email|max:255',
                'role' => 'required|integer',
            ]);
    
            if(!$validator->passes()){
                return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
            }else{
    
                $updateUser = User::find($user_id);
    
                $updateUser->name = $request->name;
                $updateUser->username = $request->username;
                $updateUser->email = $request->email;
                $updateUser->role = $request->role;
                
                $query = $updateUser->save();
                if(!$query){
                    return response()->json(['code'=>0,'msg'=>'Something went wrong']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'User have been updated']);
                }
            }
        }else{
                $validator = \Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,'.$user_id,
                'email' => 'required|string|email|max:255',
                'role' => 'required|integer',
                'password' => 'sometimes|required|confirmed|min:8',
            ]);

            if(!$validator->passes()){
                return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
            }else{

                $updateUser = User::find($user_id);

                $updateUser->name = $request->name;
                $updateUser->username = $request->username;
                $updateUser->email = $request->email;
                $updateUser->role = $request->role;
                $updateUser->password = Hash::make($request->password);
                
                $query = $updateUser->save();
                if(!$query){
                    return response()->json(['code'=>0,'msg'=>'Something went wrong']);
                }else{
                    return response()->json(['code'=>1,'msg'=>'User have been updated']);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softdel(Request $request)
    {
        $user_id = $request->user_id;
        $query =User::find($user_id)->delete();;
        if(!$query){
            return response()->json(['code'=>0,'msg'=>'Something went wrong']);
        }else{
            return response()->json(['code'=>1,'msg'=>'User have been move to trash']);
        }
    }
    public function restore(Request $request)
    {
        $user_id = $request->user_id;
        $query =User::withTrashed()->find($user_id)->restore();
        if(!$query){
            return response()->json(['code'=>0,'msg'=>'Something went wrong']);
        }else{
            return response()->json(['code'=>1,'msg'=>'User have been restore']);
        }
    }
    public function destroy(Request $request)
    {
        $user_id = $request->user_id;
        $query =User::withTrashed()->find($user_id)->forceDelete();
        if(!$query){
            return response()->json(['code'=>0,'msg'=>'Something went wrong']);
        }else{
            return response()->json(['code'=>1,'msg'=>'User have been Permanent Delete']);
        }
    }
}
