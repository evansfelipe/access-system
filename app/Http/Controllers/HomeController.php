<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (Auth::user()->type) {
            case User::ADMINISTRATION:
                return view('administration');
                break;
            case User::SECURITY:
                return view('security');
            default:
                return "Error user type";
                break;
        }
    }
}
