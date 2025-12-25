<?php

namespace App\Http\Controllers;


use App\Enums\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Activity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Intervention\Image\Laravel\Facades\Image;
class CompanyActivityController extends Controller
{
    public function index(Company $company)
    {
        // Gate::authorize('view', $company); 
        Gate::authorize('viewAny', [Activity::class, $company]);

        $company->load('activities');
 
        return view('companies.activities.index', compact('company'));
    }
 
    public function create(Company $company)
    {
        // Gate::authorize('create', $company); 
        Gate::authorize('create', [Activity::class, $company]);

        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');
 
        return view('companies.activities.create', compact('company', 'guides'));
    }
 
//         public function store(StoreActivityRequest $request, Company $company)
//     {
//         // Gate::authorize('create', $company);

//         Gate::authorize('create', [Activity::class, $company]);


//         // if ($request->hasFile('image')) {
//         //     $path = $request->file('image')->store('activities', 'public');
//         // }
//         $filename = $this->uploadImage($request);
 
//     $data = $request->validated();
//     $data['company_id'] = $company->id;
//     $data['thumbnail'] = $filename;

//     if ($request->filled('guide_id')) {
//         $data['guide_id'] = $request->guide_id;
//     }

//     if  ($request->filled('guides')) {
//         $data['guide_id'] = $request->guides[0];
//     }

//         // $activity = Activity::create($request->validated() + [
//         //     'company_id' => $company->id,
//         //     // 'photo' => $path ?? null,
//         //     'thumbnail' => $filename, 
//         // ]);

//             $activity = Activity::create($data);


// //  if ($request->filled('guides')) {
// //         $activity->participants()->sync($request->input('guides'));
// //     }

//         // $activity->participants()->sync($request->input('guides'));
//  if ($request->filled('guides')) {
//         $activity->participants()->sync($request->guides);
//     }
        
//         return to_route('companies.activities.index', $company);
//     }


public function store(StoreActivityRequest $request, Company $company)
    {
        Gate::authorize('create', [Activity::class, $company]);

        // ✅ image upload
        $filename = $this->uploadImage($request);

        // ✅ clean & safe data
        $data = $request->validated();
        $data['company_id'] = $company->id;
        $data['thumbnail'] = $filename;

        // ✅ guide assign (tests ke liye important)
        if ($request->filled('guide_id')) {
            $data['guide_id'] = $request->guide_id;
        }
        
        $activity = Activity::create($data);

        // ✅ participants sync
        // if ($request->filled('guides')) {
        //     $activity->participants()->sync($request->guides);
        // }

        return to_route('companies.activities.index', $company);
    }
 
    public function edit(Company $company, Activity $activity)
    {
        // Gate::authorize('update', $company);

        Gate::authorize('update', $activity);

 
        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');
 
        return view('companies.activities.edit', compact('guides', 'activity', 'company'));
    }
 
    public function update(UpdateActivityRequest $request, Company $company, Activity $activity)
    {
        // Gate::authorize('update', $company); 

        Gate::authorize('update', $activity);

        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('activities', 'public');
        //     if ($activity->photo) {
        //         Storage::disk('public')->delete($activity->photo);
        //     }
        // }
 
        $filename = $this->uploadImage($request); 

        if ($filename && $activity->thumbnail) {
            // Delete old original and thumbnail if new image uploaded
            // Storage::disk('public')->delete('activities/' . $activity->thumbnail);
            // Storage::disk('public')->delete('activities/thumbs/' . $activity->thumbnail);
            Storage::disk('public')->delete($activity->thumbnail);
        }

        $data = $request->validated();
        $data['thumbnail'] = $filename ?? $activity->thumbnail;

        if ($request->filled('guides')) {
            $data['guide_id'] = $request->guides[0];
        }

        $activity->update($data);

        if ($request->filled('guides')) {
            $activity->participants()->sync($request->guides);
        }

        // $activity->update($request->validated() + [
        //     // 'photo' => $path ?? $activity->photo,
        //     'thumbnail' => $filename ?? $activity->thumbnail,
        // ]);
 
        return to_route('companies.activities.index', $company);
    }
 
    public function destroy(Company $company, Activity $activity)
    {
        // Gate::authorize('delete', $company);

        Gate::authorize('delete', $activity);


        if ($activity->thumbnail) {
            Storage::disk('public')->delete($activity->thumbnail);
            // Storage::disk('public')->delete('activities/thumbs/' . $activity->thumbnail);
        }

        $activity->delete();
 
        return to_route('companies.activities.index', $company);
    }

    // private function uploadImage(StoreActivityRequest|UpdateActivityRequest $request): string|null 
    // {
    //     if (! $request->hasFile('image')) {
    //         return null;
    //     }
 
    //     $filename = $request->file('image')->store(options: 'activities');
 
    //     $thumb = Image::imagick()->read(Storage::disk('activities')->get($filename))
    //         ->scaleDown(274, 274)
    //         ->toJpeg()
    //         ->toFilePointer();
 
    //     Storage::disk('activities')->put('thumbs/' . $request->file('image')->hashName(), $thumb);
 
    //     return $filename;
    // } 

    protected function uploadImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('activities', 'public');


    }

// protected function uploadImage(StoreActivityRequest $request): ?string
// {
//     if (! $request->hasFile('image')) {
//         return null;
//     }

//     // 👇 VERY IMPORTANT: disk name MUST match test
//     return $request->file('image')->store('thumbs', 'activities');
// }


    // Is method ko controller class ke andar add karein
    public function show(Company $company, Activity $activity)
    {
        // Authorization check (agar zaroori ho)
        // Gate::authorize('view', [Activity::class, $company]);    
        Gate::authorize('view', $activity);

        // Activity details view load karein
        return view('companies.activities.show', compact('company', 'activity'));
    }
    }