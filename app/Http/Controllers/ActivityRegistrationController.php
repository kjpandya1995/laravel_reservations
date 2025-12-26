<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Notifications\RegisteredToActivityNotification;
class ActivityRegistrationController extends Controller
{
    //
public function store(Activity $activity)
    {
    //     // registration logic
    //    $user = auth()->user();

    //     // ✅ already registered check
    //     if ($user->activities()->where('activity_id', $activity->id)->exists()) {
    //         return back()->with('info', 'Already registered');
    //     }

    //     // ✅ register user to activity (pivot table)
    //     $user->activities()->attach($activity->id);

    //     auth()->user()->notify(new RegisteredToActivityNotification($activity)); 

    //     return back()->with('success', 'Successfully registered!');
     if (! auth()->check()) {
            return to_route('register', ['activity' => $activity->id]);
        }
 
        abort_if(auth()->user()->activities()->where('id', $activity->id)->exists(), Response::HTTP_CONFLICT);
 
        auth()->user()->activities()->attach($activity->id);
 
        auth()->user()->notify(new RegisteredToActivityNotification($activity)); 
 
        return to_route('my-activity.show')->with('success', 'You have successfully registered.');
    }

}
