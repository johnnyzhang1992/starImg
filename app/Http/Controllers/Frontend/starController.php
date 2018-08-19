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

class starController extends Controller
{
    public function __construct(){
//        $this->middleware('auth');
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
                ->with('site_title',$star->name.'的主页 | (@'.$star->screen_name.')')
                ->with('site_description',$star->description)
                ->with('star',$star);
        }else{
            abort(404);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function starNameDetail($name){
        $star = DB::table('star')
            ->where('star.domain','=',$name)
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
            ->first();
        if(isset($star) && $star){
            return view('frontend.star.show')
                ->with('site_title',$star->name.'的主页 | (@'.$star->screen_name.')')
                ->with('site_description',$star->description)
                ->with('star',$star);
        }else{
            abort(404);
        }
    }

    /**
     * 明星详情
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStarNameDetail($name){
        $star = DB::table('star')
            ->where('star.domain','=',$name)
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
            ->first();
        if(isset($star) && $star){
            $res = [];
            $res['star'] = $star;
            return response()->json($res);
        }else{
            abort(404);
        }
    }

    public function explore(){
        return view('frontend.star.list')
            ->with('site_title','发现更多')
            ->with('site_description','发现更多明星图片，明星列表页');
    }
    /**
     * 明星列表
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStarList(Request $request){
        $stars = DB::table('star')
            ->where('status','active')
            ->paginate(15);
        return response()->json($stars);
    }
}