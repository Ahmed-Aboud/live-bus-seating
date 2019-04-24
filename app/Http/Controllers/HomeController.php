<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Seat;
use App\Events\SeatStatusUpdated;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $seats = Seat::all();

        return view('home',compact('seats'));
    }
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
        $user_id = Auth::id();
        $seat = Seat::where('id',$id)->where(function ($query) use ($user_id) {
        $query->where('user_id', null)->orWhere('user_id', $user_id);})->first();
        
        $seat->user_id ? $seat->user_id = null : $seat->user_id = $user_id ;
        $seat->save();
        SeatStatusUpdated::dispatch($seat->id);
        return response()->json(['success'=>'Got Simple Ajax Request.'],200);
        }
    }
}