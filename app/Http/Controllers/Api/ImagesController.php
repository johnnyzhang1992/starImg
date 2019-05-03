<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Images;
use App\Models\Star;
use Illuminate\Support\Facades\DB;
//use Session;
use Illuminate\Support\Facades\Session;

class ImagesController extends Controller{

    public function __construct(){

    }

    /**
     * 获取最新图片
     * 首页使用
     * @param Request $request
     * @return mixed
     */
    public function getRecentImages(Request $request){
        $pre_page = $request->input('pre_page');
        $images =Images::leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.origin','instagram')
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.code','star_img.id','star_img.pic_detail','star_img.origin','star_img.star_id',
                'star_img.cos_url','star_img.text','star_wb.screen_name','star.avatar','star_wb.verified','star.domain','star.name','star_wb.wb_id')
//            ->select('star_img.display_url','star_img.id','star_img.pic_detail','star_img.origin','star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name','star.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id')
            ->orderBy('star_img.id', 'desc')
            ->paginate(isset($pre_page) && $pre_page>0 ? $pre_page : 12);
        foreach ($images as $key=>$image){
            if(isset($image->pic_detail) && $image->pic_detail){
                $images[$key]->pic_detail = json_decode($images[$key]->pic_detail,true);
//                if(isset($images[$key]->pic_detail)){
//                    print_r($images[$key]->pic_detail);
//                    if(strpos($images[$key]->pic_detail[0]->src,'i.starimg.cn') !== false){
//                        $images[$key]->pic_detail[0]->src = str_replace('i.starimg.cn','star-1256165736.picgz.myqcloud.com',$images[$key]->pic_detail[0]->src);
//                    }
//                }
            }
            $images[$key]->description = strip_tags($image->text);

        }
//        $images['status'] = 200;
        return response()->json($images);
    }

    /**
     * 获取某位明细的图片
     * @param Request $request
     * @param $name
     * @return mixed
     */
    public function getStarNameImages(Request $request,$name){
        $id = $this->getStarId($name);
        $type = $request->input('type');
        $sort = $request->input('sort');
        $origin = $request->input('origin');
        if(isset($type) && $type){
            if($type =='time'){
                $images = $this->getStarImagesSortByTime($id,$sort,$origin);
            }else{
                $images = $this->getStarImagesSortByLike($id,$sort,$origin);
            }
        }else{
            $images = DB::table('star_img')
                ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
                ->leftJoin('star','star.id','=','star_img.star_id')
                ->where('star_img.star_id','=',$id)
                ->where('star_img.origin',$origin)
                ->where('star_img.status','active')
                ->where('star_img.is_video',false)
                ->select('star_img.display_url','star_img.code','star_img.id','star_img.pic_detail','star_img.origin',
                    'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                    'star.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                    'star_img.attitudes_count','star_img.cos_url','star_img.code','star_img.updated_at')
                ->orderBy('star_img.mid', 'desc')
                ->orderBy('star_img.id', 'desc')
                ->paginate(12);
        }
        if(isset($images) && $images){
            foreach ($images as $key=>$image){
                if(isset($image->pic_detail) && $image->pic_detail){
                    $images[$key]->pic_detail = json_decode($images[$key]->pic_detail);
                }
                if(isset($image->text) && $image->text){
                    $images[$key]->description = strip_tags($image->text);
                }
            }
            return response()->json($images);
        }else{
            return response()->json(['status'=>200,'msg'=>'内容不存在']);
        }

    }

    /**
     * 获取时间排序后的明星图片
     * @param $id
     * @param $sort
     * @param $origin
     * @return mixed
     */
    public  function  getStarImagesSortByTime($id,$sort,$origin){
        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.star_id','=',$id)
            ->where('star_img.origin',$origin)
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.display_url','star_img.code','star_img.id','star_img.pic_detail','star_img.origin',
                'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                'star.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                'star_img.attitudes_count','star_img.cos_url','star_img.code','star_img.updated_at')
            ->orderBy('star_img.mid',$sort)
            ->orderBy('star_img.id', $sort)
            ->paginate(12);

        return $images;
    }

    /**
     * 获取赞排序后的明星图片
     * @param $id
     * @param $sort
     * @param $origin
     * @return mixed
     */
    public function getStarImagesSortByLike($id,$sort,$origin){

        $images = DB::table('star_img')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.star_id','=',$id)
            ->where('star_img.origin',$origin)
            ->where('star_img.status','active')
            ->where('star_img.is_video',false)
            ->select('star_img.display_url','star_img.code','star_img.id','star_img.pic_detail','star_img.origin',
                'star_img.star_id','star_img.status','star_img.text','star_img.origin_url','star_wb.screen_name',
                'star.avatar','star_wb.description','star_wb.verified','star.domain','star.name','star_wb.wb_id',
                'star_img.attitudes_count','star_img.cos_url','star_img.code','star_img.updated_at')
            ->orderBy('star_img.attitudes_count',$sort)
            ->paginate(12);
        return $images;
    }

    /**
     * 获取明星的 ID
     * @param $name
     * @return int
     */

    public function getStarId($name){
        $star = Star::where('domain',$name)->first();
        if(isset($star) && $star){
            return $star->id;
        }else{
            return -1;
        }
    }

}