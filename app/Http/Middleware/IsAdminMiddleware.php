<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\Role;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user->role_id !== Role::ADMINISTRATOR->value && $user->role_id !== Role::COMPANY_OWNER->value ) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
