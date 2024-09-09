<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Staff;

class ApiAuthenticate
{

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('local-api')->check()) {
            abort(response()->json(
                [
                    'status' => 'false',
                    'message' => 'You are login from other device!',
                ],
                401
            ));
        }else{
            $user = Staff::where('id',auth()->guard('local-api')->user()->id)->first();
            if($user->deleted_at !=NULL){
                abort(response()->json(
                    [
                        'status' => 'false',
                        'message' => 'User deleted!',
                    ],
                    401
                ));
            }
            if($user->status == '0'){
                abort(response()->json(
                    [
                        'status' => 'false',
                        'message' => 'You are temporary blocked! Plaese contact to admin.',
                    ],
                    401
                ));
            }
        }
        return $next($request);
    }
}
