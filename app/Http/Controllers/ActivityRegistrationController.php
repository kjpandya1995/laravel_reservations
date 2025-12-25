<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityRegistrationController extends Controller
{
    //
public function store(Activity $activity)
    {
        // registration logic
        return back()->with('success', 'Registered successfully');
    }

}
