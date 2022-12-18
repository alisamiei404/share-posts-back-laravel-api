<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogRequest;


class LogRequestMid
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
        if(auth('sanctum')->user()){
            LogRequest::create([
                'user_id' => auth('sanctum')->user()->id,
                'url' => $request->url(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'system' => $request->server('HTTP_USER_AGENT')
            ]);
        }else{
            LogRequest::create([
                'user_id' => 1,
                'url' => $request->url(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'system' => $request->server('HTTP_USER_AGENT')
            ]);
        }

        

        return $next($request);
    }
}
