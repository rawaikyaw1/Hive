<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Validator;
use DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), 
        array(
            'name'  =>      'required',
            'price'  =>      'required',
            
        ));
        
        if($validator->fails()){
            
            return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()->toArray()
             ]);

        }

        DB::beginTransaction();

        try {

            $user = new Service;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return response()->json([
             'success' => true,
             'data' => $service
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        DB::beginTransaction();
        try {
            
            $service->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated'
            ]);


        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                 'success' => false,
                 'error' => $e
             ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        DB::beginTransaction();
        try {

            $service->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                 'success' => false,
                 'error' => $e
             ]);
        }
    }
}
