<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\RatingRepository;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private $ratingService;
    private $ratingRepository;

    public function __construct(RatingService $ratingService, RatingRepository $ratingRepository)
    {
        $this->ratingService = $ratingService;
        $this->ratingRepository = $ratingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $service = $this->ratingService->store($request->all());
        return $service;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = $this->ratingService->update($id, $request->all());
        return response()->json([
            'status' => 'success',
            'service' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ratingExisted($employee_id) {
        $user = auth()->user();
        //dd($employee_id);
        $rating = $this->ratingRepository->getRating()
                ->where('user_id', $user->id)
                ->where('employee_id', $employee_id)
                ->first();
        
        return response()->json([
            "rating" => $rating,
            "status" => "success"
        ]);
        
    }
}
