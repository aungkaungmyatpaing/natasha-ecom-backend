<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedCustomerCheck
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
        if(auth()->guard('api')->user()){
            if(Auth::guard('api')->user()->is_banned == '1'){
                $customer = Auth::guard('api')->user()->token();
                $customer->revoke();
                return response()->json([
                    'message' => "Your Account hove been banned by admin!",
                ]);
            }
        }
        return $next($request);
    }
}