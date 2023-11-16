<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = $this->userRepository->getUser()
                    ->where('user_type', 1)
                    ->where('services', 'like', '%' . $request->services . '%')
                    ->with('media')
                    ->with(['ratingsReceived' => function ($query) {
                        $query->with('user');
                    }])
                    ->get()->values();

        $employees->each(function ($employee) {
            $employee->media->each(function ($media) use ($employee) {
                $employee->url = $media->getFullUrl();
            });
        });

        $rating_average = 0;
        $employees->each(function ($employee) use (&$rating_average) {
            $totalRatings = count($employee->ratingsReceived);
            $sum = 0;
            $employee->ratingsReceived->each(function ($ratingsReceived) use ($employee, &$sum) {
                $sum = $sum + $ratingsReceived->value;
            });
            $employee->rating_average = $totalRatings > 0 ? $sum / $totalRatings : 0;
        });



        return response()->json([
            "employees" => $employees,
            "status" => "success"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = $this->userRepository->store($request->all());
        return $employee;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = $this->userRepository->getUser()->with('media')
        ->with(['ratingsReceived' => function ($query) {
            $query->with('user');
        }])->find($id);

        $totalRatings = count($employee->ratingsReceived);
        $sum = 0;
        $employee->ratingsReceived->each(function ($ratingsReceived) use ($employee, &$sum) {
            $sum = $sum + $ratingsReceived->value;
        });
        $employee->rating_average = $totalRatings > 0 ? $sum / $totalRatings : 0;
        
        return response()->json([
            "employee" => $employee,
            "status" => "success"
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $employee = $this->userRepository->update($request->all(), $id);
        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->userRepository->getUser()->find($id)->delete();
    }

    public function search(Request $request) {
        $employees = $this->userRepository->getUser()
                    ->where('user_type', 1)
                    ->where()
                    ->with('media')
                    ->get()->values();


        $employees->each(function ($employee) {
            $employee->media->each(function ($media) use ($employee) {
                $employee->url = $media->getFullUrl();
            });
        });
        return response()->json([
            "employees" => $employees,
            "status" => "success"
        ]);
    }
}
