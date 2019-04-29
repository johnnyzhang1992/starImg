<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Images;
use App\Models\Star;

use Illuminate\Support\Facades\DB;

class StarController extends Controller
{
    public function __construct(){
//        $this->middleware('auth');
    }

    /**
     * 搜索明星
     * @param $request
     * @return mixed
     */
    public function searchStar(Request $request){
        $key = $request->input('key');
        $stars = Star::orwhere('domain','like','%'.$key.'%')
            ->orWhere('name','like','%'.$key.'%')
            ->orWhere('en_name','like','%'.$key.'%')
            ->orWhere('ins_name','like','%'.$key.'%')
            ->orWhere('wb_domain','like','%'.$key.'%')
            ->where('status','active')
            ->select('id','domain','name','avatar','profession')
            ->orderBy('id','asc')
            ->paginate(10);
        return response()->json($stars);

    }


    /**
     * 获取最新的十条图片
     * @param $id
     * @return mixed
     */
    public function getStarImages($id){
        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.star_id','=',$id)
            ->where('star_img.origin','微博')
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.display_url','star_img.code','star_img.id','star_img.pic_detail','star_img.origin','star_img.star_id',
                'star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name','star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id')
            ->orderBy('star_img.mid', 'desc')
            ->paginate(20);
        return $images;
    }

    /**
     * 明星详情
     * @param $name
     * @return mixed
     */
    public function getStarNameDetail($name){
        $star = Star::where('star.domain','=',$name)
            ->where('status','active')
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
            ->first();
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
            $star->posts_count = $wb_posts_count+$ins_posts_count;
            $star->description = mb_strlen($star->description) > 120 ? mb_substr($star->description,0,120).'...' : $star->description;
            $res = [];
            $res['star'] = $star;
            $res['wb_count'] = $wb_posts_count;
            $res['ins_count'] = $ins_posts_count;
            return response()->json($res);
        }else{
            return response()->json([
                'name' => $name,
                'status' => '404',
                'message'=> '此 name 对应的明星不存在！'
            ]);
        }
    }

    /**
     *
     * 通过 ID 获取 star 的个人信息
     * @param $id
     * @return mixed
     */
    public function getStarDetail($id){
        $star = Star::where('star.id','=',$id)
            ->where('status','active')
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->select('star.*','star_wb.screen_name','star_wb.description as wb_description','star_wb.verified','star_wb.verified_reason')
            ->first();
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
            $star->posts_count = $wb_posts_count+$ins_posts_count;
            $star->description = substr_count($star->description) > 120 ? substr($star->description,0,120).'...' : $star->description;
            $res = [];
            $res['star'] = $star;
            $res['wb_count'] = $wb_posts_count;
            $res['ins_count'] = $ins_posts_count;
            return response()->json($res);
        }else{
            return response()->json([
                'id' => $id,
                'status' => '404',
                'message'=> '此 ID 对应的明星不存在！'
            ]);
        }
    }

    /**
     * 明星列表
     * @param $request
     * @return mixed
     */
    public function getStarList(Request $request){
        $stars = DB::table('star')
            ->leftJoin('star_wb','star_wb.star_id','=','star.id')
            ->where('star.status','=','active')
            ->select('star.*','star_wb.verified')
            ->orderBy('id','asc')
            ->paginate(18);
        return response()->json($stars);
    }
}