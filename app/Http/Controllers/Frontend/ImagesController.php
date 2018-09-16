<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Images;
use App\Models\Star;
use Illuminate\Support\Facades\DB;

class ImagesController extends Controller{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * 返回所有图片
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.origin','微博')
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin','star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name','star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id')
            ->orderBy('star_img.mid', 'desc')
            ->paginate(20);
        foreach ($images as $key=>$image){
            if(isset($image->pic_detail) && $image->pic_detail){
                $images[$key]->pic_detail = json_decode($images[$key]->pic_detail);
            }
            $images[$key]->text = strip_tags($image->text);
        }
        return response()->json($images);
    }

    /**
     * 获取某位明星的图片
     * @param $id
     * @return \Illuminate\Http\JsonResponse
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
        if(isset($images) && $images){
            foreach ($images as $key=>$image){
                if(isset($image->pic_detail) && $image->pic_detail){
                    $images[$key]->pic_detail = json_decode($images[$key]->pic_detail);
                }
                $images[$key]->text = strip_tags($image->text);
            }
            return response()->json($images);
        }else{
            return response()->json(['status'=>200,'msg'=>'内容不存在']);
        }
    }
    public function getStarNameImages(Request $request,$name){
        $id = $this->getStarId($name);
        $type = $request->input('type');
        $sort = $request->input('sort');
        $origin = '微博';
        if(isset($type) && $type>0){
            if($type==1){
                $origin = 'instagram';
            }else{
                $origin = 'others';
            }
        }

        if(isset($sort) && $sort){
            if($sort == 'time'){
                $images = DB::table('star_img')
                    ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
                    ->leftJoin('star','star.id','=','star_img.star_id')
                    ->where('star_img.star_id','=',$id)
                    ->where('star_img.origin',$origin)
                    ->where('star_img.status','active')
                    ->where('star_img.is_video',false)
                    ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin',
                        'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                        'star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                        'star_img.attitudes_count','star_img.cos_url','star_img.code')
                    ->orderBy('star_img.mid','desc')
                    ->paginate(20);
            }elseif($sort == 'like'){
                $images = DB::table('star_img')
                    ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
                    ->leftJoin('star','star.id','=','star_img.star_id')
                    ->where('star_img.star_id','=',$id)
                    ->where('star_img.origin',$origin)
                    ->where('star_img.status','active')
                    ->where('star_img.is_video',false)
                    ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin',
                        'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                        'star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                        'star_img.attitudes_count','star_img.cos_url','star_img.code')
                    ->orderBy('star_img.attitudes_count','desc')->paginate(20);
            }
        }else{
            $images = DB::table('star_img')
                ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
                ->leftJoin('star','star.id','=','star_img.star_id')
                ->where('star_img.star_id','=',$id)
                ->where('star_img.origin',$origin)
                ->where('star_img.status','active')
                ->where('star_img.is_video',false)
                ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin',
                    'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                    'star_wb.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                    'star_img.attitudes_count','star_img.cos_url','star_img.code')
                ->orderBy('star_img.mid', 'desc')
                ->paginate(20);
        }
        if(isset($images) && $images){
            foreach ($images as $key=>$image){
                if(isset($image->pic_detail) && $image->pic_detail){
                    $images[$key]->pic_detail = json_decode($images[$key]->pic_detail);
                }
                if(isset($image->text) && $image->text){
                    $images[$key]->text = strip_tags($image->text);
                }
            }
            return response()->json($images);
        }else{
            return response()->json(['status'=>200,'msg'=>'内容不存在']);
        }

    }
    public function getStarId($name){
        $star = Star::where('domain',$name)->first();
        if(isset($star) && $star){
            return $star->id;
        }else{
            return -1;
        }
    }
}