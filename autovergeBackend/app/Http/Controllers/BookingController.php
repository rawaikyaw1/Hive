<?php

namespace App\Http\Controllers;

use App\Booking;
use App\User;
use App\Service;
use App\BookingService;
use Illuminate\Http\Request;
use Validator;
use DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::with('customer')->with('bookService')->get();

        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $bookings,
            'services' => $services
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'date' => 'required',
                'customer_id' => 'required',
                'service_id' => 'required',
                'duration' => 'required',
                'note' => 'required',
                'amount' => 'required'
            ));
        
        if($validator->fails()){

            return response()->json([
             'success' => false,
             'errors' => $validator->errors()->toArray()
         ]);

        }

        DB::beginTransaction();

        try {

            $booking = Booking::create($request->all());

            
            foreach ($request->service_id as $key => $value) {
                $booking_service = BookingService::create(['booking_id'=> $booking->id, 'service_id'=> $value]);
            }

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
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $booking = Booking::with('customer')->with('bookService')
            ->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
        } catch (Exception $e) {

            return response()->json([
                 'success' => false,
                 'error' => $e
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {

        $validator = Validator::make(request()->all(), 
            array(
                'date' => 'required',
                'customer_id' => 'required',
                'service_id' => 'required',
                'duration' => 'required',
                'note' => 'required',
                'amount' => 'required'
            ));
        
        if($validator->fails()){

            return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()->toArray()
             ]);

        }

        DB::beginTransaction();

        try {

            $booking->update($request->all());

            $booked_service = BookingService::where('booking_id', $booking->id)->delete();

            foreach ($request->service_id as $key => $value) {
                $booking_service = BookingService::create(['booking_id'=> $booking->id, 'service_id'=> $value]);
            }

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
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        try {

            $booking_service = BookingService::where('booking_id', $booking->id)->delete();
            
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ]);


        } catch (Exception $e) {
            
        }
    }

    public function customers()
    {
        $customers = User::where('is_admin', false)->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);

    }

    public function services()
    {
        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);

    }
}
