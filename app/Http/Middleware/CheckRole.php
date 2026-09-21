<?php

namespace App\Http\Middleware;

use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Haddii role-ka user-ku uu ku jiro kuwa loo ogol yahay
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt to security_logs table
        try {
            SecurityLog::create([
                'user_id' => $user->id,
                'event_type' => 'UNAUTHORIZED_ACCESS_ATTEMPT',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => "User with role '{$user->role}' attempted to access '{$request->fullUrl()}'. Allowed roles: ".implode(', ', $roles),
            ]);
        } catch (\Throwable $e) {
            // Silence log errors to not interrupt 403 handling
        }

        // Haddii uusan awood u lahayn
        abort(403, 'Unauthorized action. Ma lehid awood aad ku gasho boggan.');
    }
}
