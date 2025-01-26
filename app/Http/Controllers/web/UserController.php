<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function create(){
        return view('welcome');
    }

    public function update(Request $request){
        //dd($request->files->get('thumb'));
        $employee = $this->userRepository->update($request->all(), 11);
        return $employee;
    }
}
