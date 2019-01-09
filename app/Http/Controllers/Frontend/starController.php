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
                ->with('og_image',$star->avatar)
                ->with('og_url',asset($star->domain))
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
             // post siteMap of recent pin
            $pins = $this->getStarImages($star->id);
            if(isset($pins) && $pins){
                $urls = '';
                foreach ($pins as $pin){
                    $urls .= asset('/pin/'.$pin->id).',';
                }
                $this->postSiteMapToBaiDu($urls);
            }
            return view('frontend.star.show')
                ->with('og_image',$star->avatar)
                ->with('og_url',asset($star->domain))
                ->with('site_title',$star->name.'的图片集|(@'.$star->domain.')')
                ->with('site_description',$star->description.'|微博图片集、Instagram网页版、Instagram图片集')
                ->with('site_keywords',$star->screen_name.','.$star->name.','.$star->domain.','.config('seo.keywords'))
                ->with('page_type','star')
                ->with('star',$star);
        }else{
            abort(404);
        }
    }

    /**
     * 获取最新的十条图片
     * @param $id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStarImages($id){
        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.star_id','=',$id)
            ->where('star_img.origin','微博')
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin','star_img.star_id',
                'star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name','star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id')
            ->orderBy('star_img.mid', 'desc')
            ->paginate(20);
        return $images;
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
        $posts_count = DB::table('star_img')
            ->where('star_id',$star->id)
            ->where('status','active')
            ->where('star_img.is_video',false)
            ->count();
        $wb_posts_count = DB::table('star_img')
            ->where('star_id',$star->id)
            ->where('origin','微博')
            ->where('status','active')
            ->where('star_img.is_video',false)
            ->count();

        $ins_posts_count = DB::table('star_img')
            ->where('star_id',$star->id)
            ->where('origin','instagram')
            ->where('status','active')
            ->where('star_img.is_video',false)
            ->count();
        if(isset($star) && $star){
            $star->posts_count = $posts_count;
            $res = [];
            $res['star'] = $star;
            $res['wb_count'] = $wb_posts_count;
            $res['ins_count'] = $ins_posts_count;
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
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
//            ->leftJoin('star_ins','star_wb.star_id','=','star.id')
            ->where('star.status','=','active')
            ->select('star.*','star_wb.verified')
            ->orderBy('id','asc')
            ->paginate(30);
        return response()->json($stars);
    }
    public function getUrlStarList(){

        $stars = DB::table('star')
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
//            ->leftJoin('star_ins','star_wb.star_id','=','star.id')
            ->where('star.status','=','active')
            ->select('star.*','star_wb.verified')
            ->orderBy('star.id')
            ->get();
        foreach ($stars as $star){
            print ' <a href="https://starimg.cn/'.$star->domain.'">'.$star->name.'</a>  ';
        }

    }
}