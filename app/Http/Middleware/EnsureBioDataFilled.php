<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBioDataFilled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'peserta' && empty($user->bio_data)) {
            return redirect()->route('bio.edit')->with('warning', 'Harap lengkapi data bio terlebih dahulu.');
        }

        return $next($request);
    }
}
