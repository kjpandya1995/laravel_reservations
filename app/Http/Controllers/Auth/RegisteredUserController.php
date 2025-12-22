<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Enums\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $email = null;
 
        if ($request->has('invitation_token')) {
            $token = $request->input('invitation_token');
 
            session()->put('invitation_token', $token);
 
            $invitation = UserInvitation::where('token', $token)
                ->whereNull('registered_at')
                ->firstOrFail();
 
            $email = $invitation->email;
        }
 
        return view('auth.register', compact('email'));
    }
    

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed'],
        ]);

        // $invitation = UserInvitation::where('token', $request->token)->first();

        // if (!$invitation) {
        //     abort(403, 'Invalid or expired invitation.');
        // }
        // $invitationToken = session('invitation_token');

    $invitation = null;

    if (session()->has('invitation_token')) {
        $invitation = UserInvitation::where('token', session('invitation_token'))->first();
    }

        // if ($request->session()->get('invitation_token')) { 
        //     $invitation = UserInvitation::where('token', $request->session()->get('invitation_token'))
        //         ->where('email', $request->email)
        //         ->whereNull('registered_at')
        //         ->firstOr(fn() => throw ValidationException::withMessages(['invitation' => 'Invitation link does not match the email']));
 
        //     $role = $invitation->role_id;
        //     $company = $invitation->company_id;
 
        //     $invitation->update(['registered_at' => now()]);
        // } 

// if ($invitationToken) {
//         $invitation = UserInvitation::where('token', $invitationToken)
//             ->where('email', $request->email)
//             ->whereNull('registered_at')
//             ->firstOrFail();
//     }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            // 'role_id' => $invitation ?? Role::USER->value,
            // 'company_id' => $invitation->company_id,
             'company_id' => $invitation?->company_id,
            'role_id' => $invitation?->role_id ?? Role::USER->value,
        ]);

    //      if ($invitation) {
    //     $invitation->update(['registered_at' => now()]);
    //     session()->forget('invitation_token');
    // }

    //     event(new Registered($user));

     if ($invitation) {
        $invitation->delete();
        session()->forget('invitation_token');
    }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
//     public function store(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email',
//         'password' => 'required|confirmed|min:8',
//         'token' => 'nullable|string',
//     ]);

//     $invitation = UserInvitation::where('token', $request->token)->first();

//     $user = User::create([
//         'name'       => $request->name,
//         'email'      => $request->email,
//         'password'   => Hash::make($request->password),
//         'company_id' => $invitation?->company_id,
//         'role_id'    => $invitation?->role_id ?? Role::USER->value,
//     ]);

//     if ($invitation) {
//         $invitation->delete();
//     }

//     Auth::login($user);

//     return redirect()->route('dashboard');
// }
}
