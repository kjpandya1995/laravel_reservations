<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\Role;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class GuideActivityController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // public function show()
    // {
    //     abort_if(auth()->user()->role_id !== Role::GUIDE->value, Response::HTTP_FORBIDDEN);
 
    //     $activities = Activity::where('guide_id', auth()->id())->orderBy('start_time')->get();
 
    //     return view('activities.guide-activities', compact('activities'));
    // }
    public function index()
    {
        abort_if(
            Auth::user()->role_id !== Role::GUIDE->value,
            Response::HTTP_FORBIDDEN
        );

        $guideId = Auth::id();

        $activities = Activity::where('guide_id', $guideId)
            ->orderBy('start_time')
            ->get();

        return view('activities.guide-activities', compact('activities'));
    }

     public function export(Activity $activity)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to export PDF');
        }
        
        // Check if user is guide and owns this activity
        abort_if($activity->guide_id !== auth()->id(), 403, 'You are not authorized to export this activity.');

        // Load activity with participants and their pivot data
        $data = $activity->load(['participants' => function($query) {
            $query->withPivot('created_at', 'updated_at');
        }]);
        
        // Force refresh the relationship
        $data->refresh();
        $data->load('participants');
        
        // Generate PDF
        $pdf = Pdf::loadView('activities.pdf', compact('data'));
        
        return $pdf->download("participants-{$activity->name}.pdf");    
    }
}
