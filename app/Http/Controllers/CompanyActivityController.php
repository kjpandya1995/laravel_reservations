<?php

namespace App\Http\Controllers;


use App\Enums\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Activity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
class CompanyActivityController extends Controller
{
    public function index(Company $company)
    {
        Gate::authorize('view', $company); 
        $company->load('activities');
 
        return view('companies.activities.index', compact('company'));
    }
 
    public function create(Company $company)
    {
        Gate::authorize('create', $company); 
        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');
 
        return view('companies.activities.create', compact('guides', 'company'));
    }
 
    public function store(StoreActivityRequest $request, Company $company)
    {
        Gate::authorize('create', $company);
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('activities', 'public');
        // }
        $filename = $this->uploadImage($request);
 
        $activity = Activity::create($request->validated() + [
            'company_id' => $company->id,
            // 'photo' => $path ?? null,
            'photo' => $filename, 
        ]);
//  if ($request->filled('guides')) {
//         $activity->participants()->sync($request->input('guides'));
//     }

        $activity->participants()->sync($request->input('guides'));
 
        return to_route('companies.activities.index', $company);
    }
 
    public function edit(Company $company, Activity $activity)
    {
        Gate::authorize('update', $company);
 
        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');
 
        return view('companies.activities.edit', compact('guides', 'activity', 'company'));
    }
 
    public function update(UpdateActivityRequest $request, Company $company, Activity $activity)
    {
        Gate::authorize('update', $company); 
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('activities', 'public');
        //     if ($activity->photo) {
        //         Storage::disk('public')->delete($activity->photo);
        //     }
        // }
 
        $filename = $this->uploadImage($request); 

if ($filename && $activity->photo) {
            // Delete old original and thumbnail if new image uploaded
            Storage::disk('public')->delete('activities/' . $activity->photo);
            Storage::disk('public')->delete('activities/thumbs/' . $activity->photo);
        }

        $activity->update($request->validated() + [
            // 'photo' => $path ?? $activity->photo,
            'photo' => $filename ?? $activity->photo,
        ]);
 
        return to_route('companies.activities.index', $company);
    }
 
    public function destroy(Company $company, Activity $activity)
    {
        Gate::authorize('delete', $company);
if ($activity->photo) {
            Storage::disk('public')->delete('activities/' . $activity->photo);
            Storage::disk('public')->delete('activities/thumbs/' . $activity->photo);
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

    protected function uploadImage(Request $request): string|null 
    {
        if ($request->hasFile('photo')) {
             return $request->file('photo')
            ->store('activities', 'activities'); // 👈 YAHI LINE
    }

    return null;

        // $imageFile = $request->file('image');
        // $hashName = $imageFile->hashName();
        
        // // 1. Original Image Save karein (public disk use kar raha hoon for easy access)
        // $imageFile->storeAs('activities', $hashName, 'public');
 
        // // 2. Thumbnail banayein (Intervention Image v3)
        // // Default driver (GD) use karna best hai jab tak Imagick confirmed na ho
        // $thumb = Image::read($imageFile)
        //     ->scaleDown(274, 274)
        //     ->toJpeg(80); // 80% quality
 
        // // 3. Thumbnail save karein
        // Storage::disk('public')->put('activities/thumbs/' . $hashName, $thumb);
 
        // return $hashName;

        // Storage::disk('activities')->putFileAs('', $imageFile, $hashName);

    // Thumbnail: storage/app/public/activities/thumbs/filename.jpg
    // $thumb = Image::read($imageFile)->scaleDown(274, 274)->toJpeg(80);
    // Storage::disk('activities')->put('thumbs/' . $hashName, (string) $thumb);

    // return $hashName;
        // return $request->file('photo')->store('thumbs', 'activities');

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
        Gate::authorize('view', $company);

        // Activity details view load karein
        return view('companies.activities.show', compact('company', 'activity'));
    }
    }