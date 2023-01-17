<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripCollection;
use App\Http\Requests\StoreTripRequest;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $validator = Validator::make($request->all(), [
                'per_page' => ['nullable', 'integer'],
                'location' => 'required',
                'campus' => 'required|numeric',
                'date' => 'required|date|after_or_equal:today',
                'order_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
                'order_by' => ['nullable', 'string', Rule::in(Schema::getColumnListing(app(Trip::class)->getTable()))],
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors()->all(), 400);
            }

            $query = Trip::query();

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            return $this->sendResponse(
                (new TripCollection(
                    $query->orderBy(
                        request('order_by', 'id'),
                        request('order_dir', 'desc')
                    )->paginate(
                        request('per_page', 5)
                    )
                ))->response()->getData(),
                'Rides list retrieved successfully'
            );
        } catch (Exception $ex) {
            report($ex);

            return $this->sendError('Retrival Failed', ['Something went wrong'], 500);
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
                'user_id' => 'required',
                'location' => 'required',
                'campus' => 'required|numeric',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
            ]);

            $trip = new Trip([
                'user_id' => $request->get('user_id'),
                'location' => $request->get('location'),
                'campus' => $request->get('campus'),
                'date' => $request->get('date'),
                'time' => $request->get('time'),

            ]);
            $trip->save();

            return new TripResource($trip);

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
        
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
