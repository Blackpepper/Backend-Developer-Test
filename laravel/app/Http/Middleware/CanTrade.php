<?php

namespace App\Http\Middleware;

use App\Models\Martian;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CanTrade
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $martian = Martian::find($request->martianId);
        Log::info("can trade middleware");
        Log::info($martian);
        if(!$martian->can_trade) {
            return response()->json([
                'message' => 'Sorry this trader was flagged, trading and inventory manipulation was disabled',
            ], 400);
        }

        return $next($request);
    }
}
