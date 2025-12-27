<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RegisteredToActivityNotification;

class ActivityController extends Controller
{
    public function show(Activity $activity)
    {
        return view('companies.activities.show', compact('activity'));
    }
    public function register(Activity $activity)
    {
        // ✅ GUEST USER
        if (!Auth::check()) {
            session(['activity' => $activity->id]);

            return redirect()->route('register', [
                'activity' => $activity->id
            ]);
        }
        

        // ✅ AUTHENTICATED USER
        $user = Auth::user();

        // ❌ Already registered
        if ($user->activities()->where('activity_id', $activity->id)->exists()) {
            return response()->json([
                'message' => 'Already registered'
            ], 409);
        }

        // ✅ Register user
        $user->activities()->attach($activity);

        // ✅ Send notification
        $user->notify(new RegisteredToActivityNotification($activity));

        return redirect()->route('my-activity.show');
    }
}
