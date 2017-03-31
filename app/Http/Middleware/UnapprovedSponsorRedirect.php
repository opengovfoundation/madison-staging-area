<?php

namespace App\Http\Middleware;

use App\Models\Sponsor;
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

        if (($sponsor instanceof Sponsor) && !$sponsor->isActive()) {
            return redirect()->route('sponsors.awaiting-approval');
        }

        return $next($request);
    }
}
