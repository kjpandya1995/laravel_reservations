<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function show(Activity $activity)
    {
        return view('companies.activities.show', compact('activity'));
    }
}
