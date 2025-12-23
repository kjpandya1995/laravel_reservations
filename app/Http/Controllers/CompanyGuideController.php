<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;

use Illuminate\Http\Request;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use Illuminate\Support\Str;
use App\Mail\RegistrationInvite;
use Illuminate\Support\Facades\Mail;
class CompanyGuideController extends Controller
{
    public function index(Company $company)
    {

        Gate::authorize('view', $company);
 
        // $guides = $company->users()->where('role_id', Role::GUIDE->value)->get();
        // $guides = User::where('company_id', $company->id)
        //       ->where('role_id', Role::GUIDE->value)
        //       ->get();

        $guides = $company->users()->where('role_id', Role::GUIDE->value)->get();
 
        // return view('companies.guides.index', compact('company', 'guides'));
        return view('companies.guides.index', compact('guides', 'company'));
    }
 
    public function create(Company $company)
    {
        Gate::authorize('create', $company);
 
        return view('companies.guides.create', compact('company'));
    }
 
    public function store(StoreGuideRequest $request, Company $company)
    {
        Gate::authorize('create', $company);
 
        // Check for existing invitation
        $existingInvitation = UserInvitation::where('email', $request->email)
            ->whereNull('registered_at')
            ->first();
            
        if ($existingInvitation) {
            return back()->withErrors(['email' => 'Invitation with this email address already requested.']);
        }
        
        // Check if name is provided (full user creation) or just email (invitation only)
        if ($request->has('name')) {
            // Create full user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $company->id,
                'role_id' => Role::GUIDE->value,
                'password' => bcrypt($request->password ?? Str::random(10)),
            ]);
        } else {
            // Create user with minimal data for invitation
            $user = User::create([
                'name' => 'Pending User',
                'email' => $request->email,
                'company_id' => $company->id,
                'role_id' => Role::GUIDE->value,
                'password' => bcrypt(Str::random(10)),
            ]);
        }

        $invitation = UserInvitation::create([
            'email' =>  $user->email,
            'token' => Str::uuid(),
            'company_id' => $company->id,
            'role_id' => Role::GUIDE->value,
        ]);
 
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
        'name' => 'required|string|max:255',
        'email' => 'required|email',
    ]);

    // 2. Update guide
    $guide->update([
        'name' => $request->name,
        'email' => $request->email
    ]);

   return redirect()->route('companies.guides.index', $company);
}


    
    public function destroy(Company $company, User $guide)
    {
        Gate::authorize('delete', $company);
 
        $guide->delete();
 
        return to_route('companies.guides.index', $company);
    }
}
