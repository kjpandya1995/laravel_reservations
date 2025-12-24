<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // return view('home');
        // $activities = Activity::where('start_time', '>', now())
        //     ->orderBy('start_time')
        //     ->paginate(9);
 
        $activities = Activity::orderBy('start_time')->paginate(9);
        
        return view('home', compact('activities'));
    }
}
