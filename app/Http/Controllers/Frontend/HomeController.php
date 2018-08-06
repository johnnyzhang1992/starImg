<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
    public function starImage($id){
        $star = DB::table('star')
            ->first();
        return view('frontend.star');
    }
    public function test(){
        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->where('star_img.origin','微博')
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.*','star_wb.screen_name','star_wb.wb_id','star_wb.avatar','star_wb.description')
            ->orderBy('star_img.mid', 'desc')
            ->paginate(20);
        return view('frontend.home')
            ->with('images',$images);
    }
}