<?php

namespace App\Http\Middleware;

use Closure; // Halkan ka eeg: "use" ayaa ka dhimanayd
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Haddii role-ka user-ku uu ku jiro kuwa loo ogol yahay
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Haddii uusan awood u lahayn
        abort(403, 'Unauthorized action. Ma lehid awood aad ku gasho boggan.');
    }
}