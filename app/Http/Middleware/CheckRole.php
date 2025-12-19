<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            Log::error('CheckRole: User not authenticated');
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Debug log
        Log::info('CheckRole Debug', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role_id' => $user->role_id,
            'user_role' => $user->role ? $user->role->name : 'NULL',
            'required_roles' => $roles,
        ]);
        
        // Cek apakah user punya role
        if (!$user->role) {
            Log::error('CheckRole: User has no role', ['user_id' => $user->id]);
            abort(403, 'User tidak memiliki role yang valid.');
        }

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (!in_array($user->role->name, $roles)) {
            Log::warning('CheckRole: Access denied', [
                'user_role' => $user->role->name,
                'required_roles' => $roles,
            ]);
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}