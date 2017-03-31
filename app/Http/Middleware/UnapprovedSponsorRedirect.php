<?php

namespace App\Http\Middleware;

use Closure;

class UnapprovedSponsorRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sponsor = $request->route()->parameter('sponsor');

        if (empty($sponsor)) {
            return $next($request);
        }

        if (!$sponsor->isActive()) {
            return redirect()->route('sponsors.awaiting-approval');
        }
    }
}
