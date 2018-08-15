<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Images;
use App\Models\Star;
use App\Helpers\QcloudUplodImage;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * homepage
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.home');
    }
}