<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Is_User_AN_ADMIN(request()->user()->id)) {
            return response()->json([
                'status' => false,
                "message" => "Vous n'etes pas autorisé.e à éffectuer cette action! Seuls les admins ont ce droit!"
            ], 201);
        }
        return $next($request);
    }
}
