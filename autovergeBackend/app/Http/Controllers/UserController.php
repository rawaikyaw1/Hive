<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;

class UserController extends Controller
{
    public function index()
    {
    	$users = User::all();


    	return response()->json([
             'success' => true,
             'users' => $users
         ]);
    }

    public function create(Request $request)
    {
    	$validator = Validator::make(request()->all(), 
        array(
            'name'  =>      'required',
            'email'  =>      'required|email|unique:users',
            'password' =>    'required|min:6',
            'card_no' =>    'required'
        ));
        
        if($validator->fails()){
            
            return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()->toArray()
             ]);

        }

    	DB::beginTransaction();

        try {

            $user = new User;
            $request->password = Hash::make($request->password);
            $request->is_admin = 0;
            $user->create($request->all());

            DB::commit();

            return response()->json([
                 'success' => true,
                 'message' => 'Successfully created'
             ]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json([
                 'success' => false,
                 'error' => $e
             ]);
        }
    }

    public function edit($id)
    {
        
        try {
            $user = User::find($id);

            return response()->json([
                 'success' => true,
                 'user' => $user
             ]);

        } catch (Exception $e) {
            return response()->json([
                 'success' => false,
                 'error' => $e
             ]);
        }
    }

    public function update(Request $request, $id)
    {
     
     
        $validator = Validator::make(request()->all(), 
        array(
            'name'  =>      'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'card_no' =>    'required'
        ));
        
        if($validator->fails()){
            
            return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()->toArray()
             ]);

        }


        DB::beginTransaction();

        try {
            
            $user = User::find($id);

            $update_arr= [];

            $hashPassword = null;
            
            if (isset($request->change_password)) {

                $update_arr['password'] = Hash::make($request->change_password);

            }

            $update = array_merge($request->all(), $update_arr);

            $user->update($update);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully Updted'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
               'success' => false,
               'errors' => $e
           ]);
        }
    }

    public function delete($id)
    {

        
        DB::beginTransaction();

        try {
            
            $user = User::find($id);

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
               'success' => false,
               'errors' => $e
           ]);
        }

    }
    
}
