<?php

namespace App\Http\Middleware;

use Closure;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next) {
//        $response = $next($request);
//        $response->header('Access-Control-Allow-Origin', '*');
//        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept');
//        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//        $response->header('Access-Control-Allow-Credentials', 'true');  //ession共享的需求才用到
//        return $response;
//    }
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            'http://localhost:3000',
            'http://localhost:5000',
            'http://127.0.0.1:3000',
            'http://test.starimg.cn',
            'https://test.starimg.cn',
            'https://starimg.cn',
            'https://api.starimg.cn'
        ];
        if (in_array($origin, $allow_origin)) {
//            echo $origin;
            $response->header('Access-Control-Allow-Origin', $origin);
//            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN, X-Requested-With');
//            $response->header('Access-Control-Allow-Headers', 'Origin,  Cookie, X-CSRF-TOKEN, Accept, X-XSRF-TOKEN');
            $response->header('Access-Control-Allow-Headers', '*');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'false');
        }
        return $response;
    }
}
