<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Log;
use App\Models\Log;
class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (app()->environment('local')) {
            // $log = [
            //     'URI' => $request->getUri(),
            //     'METHOD' => $request->getMethod(),
            //     'REQUEST_BODY' => $request->all(),
            //     'RESPONSE' => $response->getContent()
            // ];
           // print_r($request->all()); die;
            $log = new Log;
            $log->URI = $request->getUri();
            $log->METHOD = $request->getMethod();
            $log->REQUEST_BODY =json_encode($request->all());
            $log->RESPONSE = $response->getContent();
            $log->save();
            // Log::info(json_encode($log));
 
        }
       
        return $response;
    }
}
