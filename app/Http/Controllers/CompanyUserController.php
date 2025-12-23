<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Company;
use App\Enums\Role;
use App\Models\User;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\UserInvitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationInvite;


class CompanyUserController extends Controller
{
   
    public function index(Company $company)
    {
        //  $users = $users = $company->users()->where('role_id', Role::COMPANY_OWNER->value)->get();
 
        // return view('companies.users.index', compact('company', 'users'));
        //  if (auth()->user()->role_id !== 2) {
        // abort(403);
    // }

//     Gate::authorize('viewAny', $company);

//  $users = $company->users()
//         ->where('role_id', 2) // COMPANY_OWNER
//         ->get();    
//     return view('companies.users.index', compact('company', 'users'));

Gate::authorize('view', $company);

    $users = $company->users()->get();
 
        return view('companies.users.index', compact('company', 'users'));



    }
   

     public function create(Company $company)
    {
        Gate::authorize('create', $company);
        return view('companies.users.create', compact('company'));
    }


     public function store(StoreUserRequest $request, Company $company)
    {
        Gate::authorize('create', $company);

        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // Check for existing invitation
        $existingInvitation = UserInvitation::where('email', $request->email)
            ->whereNull('registered_at')
            ->first();
            
        if ($existingInvitation) {
            return back()->withErrors(['email' => 'Invitation with this email address already requested.']);
        }

        // Check if name is provided (full user creation) or just email (invitation only)
        if ($request->has('name')) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $company->id,
                'role_id' => Role::COMPANY_OWNER->value,
                'password' => bcrypt($request->password ?? Str::random(10)),
            ]);
        } else {
            // Create user with minimal data for invitation
            $user = User::create([
                'name' => 'Pending User',
                'email' => $request->email,
                'company_id' => $company->id,
                'role_id' => Role::COMPANY_OWNER->value,
                'password' => bcrypt(Str::random(10)),
            ]);
        }

        $invitation = UserInvitation::create([
            'email' => $user->email,
            'token' => Str::uuid(),
            'company_id' => $company->id,
            'role_id' => Role::COMPANY_OWNER->value,
        ]);
 
        Mail::to($request->email)->send(new RegistrationInvite($invitation));

        return redirect()->route('companies.users.index', $company);
    }

    
 
     public function edit(Company $company, User $user)
    {
        Gate::authorize('update', $company);

        return view('companies.users.edit', compact('company', 'user'));
    }

     public function update(UpdateUserRequest $request, Company $company, User $user)
    {
        Gate::authorize('update', $company);

        $user->update($request->validated());
 
        return redirect()->route('companies.users.index', $company);

    }

     public function destroy(Company $company, User $user)
    {
        Gate::authorize('delete', $company);

        // if (auth()->user()->isCompanyOwner() && $user->company_id !== $company->id) {
        //     abort(403);
        // }
        $user->delete();

        return redirect()->route('companies.users.index', $company);

    //     abort_if($user->company_id !== $company->id, 403);

    // $user->delete();

    // return redirect()->route('companies.users.index', $company->id);

    }
}
