<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use App\Models\UserInvitation;
use Illuminate\Support\Str;
use App\Mail\RegistrationInvite;
use Illuminate\Support\Facades\Mail;
class CompanyGuideController extends Controller
{
    public function index(Company $company)
    {

        Gate::authorize('view', $company);
 
        // $guides = $company->users()->where('role_id', Role::GUIDE->value)->get();
        $guides = User::where('company_id', $company->id)
              ->where('role_id', Role::GUIDE->value)
              ->get();
 
        // return view('companies.guides.index', compact('company', 'guides'));
        return view('guides.index', compact('guides'));
    }
 
    public function create(Company $company)
    {
        Gate::authorize('create', $company);
 
        return view('companies.guides.create', compact('company'));
    }
 
    public function store(StoreGuideRequest $request, Company $company)
    {
        Gate::authorize('create', $company);
 
        // $company->users()->create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'role_id' => Role::GUIDE->value,          
        // 'company_id' => auth()->user()->company_id, 
        // ]);
 
        // return to_route('companies.guides.index', $company);

        $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'company_id' => $company->id,
        'role_id' => Role::GUIDE->value,
        'password' => bcrypt(Str::random(10)),
    ]);

        $invitation = UserInvitation::create([
            'email' =>  $user->email,
            'token' => Str::uuid(),
            'company_id' => $company->id,
            'role_id' => Role::GUIDE->value,
        ]);
 
        // Mail::to($request->input('email'))->send(new RegistrationInvite($invitation));

        Mail::to($user->email)
        ->send(new RegistrationInvite($invitation));
 
        return to_route('companies.guides.index', $company);
    }
 
    public function edit(Company $company, User $guide)
    {
        Gate::authorize('update', $company);
 
        return view('companies.guides.edit', compact('company', 'guide'));
    }
 
    // public function update(UpdateGuideRequest $request, Company $company, User $guide)
    // {
    //     Gate::authorize('update', $company);
 
    //     $guide->update($request->validated());
 
    //     return to_route('companies.guides.index', $company);
    // }
 
public function update(Request $request, Company $company, User $guide)
{

    Gate::authorize('update', $company);

    abort_if($guide->company_id !== $company->id, 403);

    // 1. Validate data
    $request->validate([
        'email' => 'required|email',
    ]);

    // 2. Update guide email
    $guide->update([
        'email' => $request->email
    ]);

    Mail::to($request->email)
    ->send(new UserRegistrationInvite($invitation));

   return redirect()->route('companies.guides.index', $company);
}


    
    public function destroy(Company $company, User $guide)
    {
        Gate::authorize('delete', $company);
 
        $guide->delete();
 
        return to_route('companies.guides.index', $company);
    }
}
