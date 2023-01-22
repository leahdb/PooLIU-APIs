<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Rider_Requests;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripCollection;
use App\Http\Requests\StoreTripRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Schema;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'per_page' => ['nullable', 'integer'],
                'location' => 'required',
                'is_going' => 'required',
                'campus' => 'required|numeric',
                'date' => 'required|date|after_or_equal:today',
                'order_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
                'order_by' => ['nullable', 'string', Rule::in(Schema::getColumnListing(app(Trip::class)->getTable()))],
            ]);

            if ($validator->fails()) {
                return response([
                    'message' => $validator->errors()->all()
                ], 400); 
            }

            $query = Trip::query();

            if ($request->filled('location')) {
                $query->where('location', $request->location);
            }

            if ($request->filled('is_going')) {
                $query->where('is_going', $request->is_going);
            }

            if ($request->filled('campus')) {
                $query->where('campus', $request->campus);
            }

            if ($request->filled('date')) {
                $query->whereDate('date', '=', Carbon::parse($request->date)->toDateString());
            }

            $rides = (new TripCollection(
                $query->with('driver')->orderBy(
                    request('order_by', 'id'),
                    request('order_dir', 'desc')
                )->paginate(
                    request('per_page', 5)
                )
                ))->response()->getData();

            return response([
                'message' => "rides retrieved successfully",
                'rides' => $rides
            ],200);

        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        //return TripResource::collection(Trip::all());
    }

    public function allTrips(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response([
                    'message' => $validator->errors()->all()
                ], 400); 
            }

            $query = Trip::query()->get();

            if ($request->filled('user_id')) {
                $query->where('driver_id', $request->user_id);
            }

            $trips = (new TripCollection($query))->response()->getData();

            return response([
                'message' => "my trips retrieved successfully",
                'trips' => $trips
            ],200);

        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        //return TripResource::collection(Trip::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTripRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTripRequest $request)
    {
       try{

            $this->validate($request, [
                'driver_id' => 'required',
                'location' => 'required',
                'is_going' => 'required',
                'campus' => 'required|numeric',
                'date' => 'required|date',
                'time' => 'required',
                'ride_type' => 'required',
                'seats' => 'required|numeric',
            ]);

            $trip = new Trip([
                'driver_id' => $request->get('driver_id'),
                'location' => $request->get('location'),
                'is_going' => $request->get('is_going'),
                'campus' => $request->get('campus'),
                'date' => $request->get('date'),
                'time' => $request->get('time'),
                'ride_type' => $request->get('ride_type'),
                'seats' => $request->get('seats'),

            ]);
            $trip->save();

            return new TripResource($trip);

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        
    }


    public function requestTrip(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rider_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->all()
            ], 400); 
        }

        $trip = Trip::find($id);

        // Check if the trip exists
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        // Create a new passenger request
        $riderRequest = new Rider_Requests([
            'rider_id' => $request->rider_id,
            'trip_id' => $trip->id,
            'status' => 'pending'
        ]);

        // Save the request
        $riderRequest->save();

        return response([
            'message' => "rides retrieved successfully",
            'request' => $riderRequest
        ],200);
    }


    public function removeRequest(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:rider_requests,id'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->all()
            ], 400); 
        }

        $rider_request = Rider_Requests::find(8);

        // Check if the trip exists
        if (!$rider_request) {
            return response()->json([
                'message' => 'rider request not found'
            ], 404);
        }

        $rider_request->delete();
        
        return response([
            'message' => "request deleted successfully",
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        return new TripResource($trip);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTripRequest  $request
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return response("trip deleted successfully", 204);
    }
}
