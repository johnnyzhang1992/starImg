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

    /**
     * star detail page
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function starDetail($id){
        $star = DB::table('star')
            ->where('star.id','=',$id)
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
            ->first();
        if(isset($star) && $star){
            return view('frontend.star.show')
                ->with('site_title','@'.$star->name.' | '.$star->screen_name.'的微博图片')
                ->with('site_description',$star->description)
                ->with('star',$star);
        }else{
            $star = DB::table('star')
                ->where('star.domain','=',$id)
                ->leftJoin('star_wb','star_wb.star_id','=','star.id')
                ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
                ->first();
            return view('frontend.star.show')
                ->with('site_title','@'.$star->name.' | '.$star->screen_name.'的微博图片')
                ->with('site_description',$star->description)
                ->with('star',$star);
        }

    }
}