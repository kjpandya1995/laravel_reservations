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
        abort_if(auth()->user()->role_id !== Role::GUIDE->value, Response::HTTP_FORBIDDEN);

        abort_if($activity->guide_id !== auth()->id(), Response::HTTP_FORBIDDEN);


        $data = $activity->load(['participants' => function($query) {
            $query->orderByPivot('created_at');
        }]);
 
        return Pdf::loadView('activities.pdf', ['data' => $data])->download("{$activity->name}.pdf");
    }
}
