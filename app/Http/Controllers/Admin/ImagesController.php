<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use TCG\Voyager\Events\Routing;
use TCG\Voyager\Events\RoutingAdmin;
use TCG\Voyager\Events\RoutingAdminAfter;
use TCG\Voyager\Events\RoutingAfter;
use TCG\Voyager\Models\DataType;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use TCG\Voyager\Traits\AlertsMessages;
use TCG\Voyager\Facades\Voyager;
use App\Models\Images;
use App\Models\Star;
use App\Helpers\QcloudUplodImage;
use App\Helpers\QcloundCiImage;
use Illuminate\Support\Facades\DB;

class ImagesController extends BaseController{
    use DispatchesJobs,
        ValidatesRequests,
        AuthorizesRequests,
        AuthenticatesUsers,
        AlertsMessages;

    public function __construct(){

    }

    public function index(Request $request,$type=null){
        if (Auth::user()) {
            if($type && $type =='wb'){
              $images = Images::where('origin','微博')->where('status','active')->where('is_video',false)->orderBy('mid', 'desc')->paginate(20);
            }elseif($type && $type =='ins'){
                $images = Images::where('origin','instagram')
                    ->where('status','active')
                    ->where('is_video',false)
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            }else{
                $images = Images::where('is_video',false)->where('status','active')->orderBy('created_at', 'asc')->paginate(20);
            }
            // ->orderBy('attitudes_count','desc')
            return view('admin.img.images') ->with('images',$images);
        }else{
            return Voyager::view('voyager::login');
        }
    }

    /**
     * 删除图片
     * @param $id
     * @param $type
     * @return mixed
     */
    public function update($id,$type){
        $status = Images::where('id',$id)->update([
           'status'=>$type,
           'updated_at' => date('Y-m-d H:i:s',time())
        ]);
        $res = [];
        if($status){
            $res['message'] = 'success';
        }else{
            $res['message'] = 'fail';
        }
        return response()->json($res);
    }

    /**
     * 批量删除图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSome(Request $request){
        $ids = $request->input('ids');
        $id_arr = explode(',',$ids);
        $res = [];
        foreach ($id_arr as $id){
            if(isset($id) && $id !=''){
                $status = Images::where('id',$id)->update([
                    'status'=>'delete',
                    'updated_at' => date('Y-m-d H:i:s',time())
                ]);
                if($status){
                    $res['message'] = 'success';
                }else{
                    $res['message'] = 'fail';
                }
            }

        }
        return response()->json($res);
    }
    /**
     * 明星图片
     * @param $id
     * @param $type
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function starImage($id,$type,Request $request){
        if (Auth::user()) {
            $star = Star::where('id',$id)->first();
            $images = Images::where('star_id',$id)->where('status','active')->where('is_video',false);
            if($type && $type =='wb'){
                $images->where('origin','微博');
            }elseif($type && $type =='ins'){
                $images = $images->where('origin','instagram');
            }
            if($request->input('sort')){
                $images->orderBy('attitudes_count','desc');
            }else{
                if($type && $type =='wb'){
                    $images->orderBy('mid', 'desc');
                }elseif($type && $type =='ins'){
                    $images->orderBy('id', 'asc');
                }
            }
            $images = $images->paginate(15);
            if($request->input('sort')){
                $images->withPath('?sort=like');
            }
            return view('admin.img.images')
                ->with('star',$star)
                ->with('images',$images);
        }else{
            return Voyager::view('voyager::login');
        }
    }

    /**
     * 上传图片到腾讯云
     * @param $star_id
     */
    public function downloadHttpImages($star_id){
        if($star_id == 'all'){
            $uploadImage = new QcloudUplodImage();
            $images = Images::where('origin','instagram')->where('status','active')->where('is_video',false)->whereNull('cos_url')->orderBy('id', 'desc')->paginate(1);
            $count = Images::where('origin','instagram')->where('status','active')->where('is_video',false)->whereNull('cos_url')->orderBy('id', 'desc')->count();
            info(count($images));
            if(count($images)<1){
                print '没有待下载的图片';
            }else{
                print '待下载的图片张数为：'.($count-8);
            }
            foreach ($images as $image){
                info($image->display_url);
                $uploadImage->http_get_data($image->display_url,$image->star_id,$image->id,'ins');
            }
        }else{
            $uploadImage = new QcloudUplodImage();
            $images = Images::where('star_id',$star_id)->where('origin','instagram')->where('status','active')->where('is_video',false)->whereNull('cos_url')->orderBy('id', 'desc')->paginate(10);
            $count = Images::where('star_id',$star_id)->where('origin','instagram')->where('status','active')->where('is_video',false)->whereNull('cos_url')->orderBy('id', 'desc')->count();
            info(count($images));
            if(count($images)<1){
                print '没有待下载的图片';
            }else{
                print '待下载的图片张数为：'.($count-10);
            }
            foreach ($images as $image){
                info($image->display_url);
                $uploadImage->http_get_data($image->display_url,$star_id,$image->id,'ins');
            }
        }

    }

    /**
     * 下载star头像到腾讯服务器
     */
    public function downloadAvatarToCos(){
        $users = DB::table('star')->select('id','avatar')->orderBy('id','asc')->paginate(10);
        foreach ($users as $user){
            if(strpos($user->avatar,'starimg.cn') !== false){
                print($user->id.'------'.$user->avatar);
            }else{
                $uploadImage = new QcloudUplodImage();
                $uploadImage->http_get_data1($user->avatar,$user->id,'avatar');
            }
        }

    }


    /**
     * 腾讯与图片鉴黄
     */
    public function imageDetect(){
        QcloundCiImage::porn_detect(['https://wx1.sinaimg.cn/mw690/a5fa5943gy1ftmg6h2asej21kw2dc4qt.jpg'],'urls');
    }

    public function updateInsImagesSize(Request $request,$star_id){
        $images = Images::where('star_id',$star_id)
            ->where('status','active')
            ->where('size_flag',false)
            ->where('is_video',false)
            ->whereNotNull('cos_url')
            ->where('origin','instagram')
            ->orderBy('id','asc')
            ->paginate(10);
        print count($images).'<br>';
        foreach ($images as $image){
            echo $image->cos_url;
            print '<br>';
            $this->updateImageSize($image->id,'https://img.starimg.cn/'.$image->cos_url,$image->cos_url);
        }
    }
    /**
     * 更新ins图片的尺寸函数
     * @param $id
     * @param $url
     * @param $o_url
     */
    public function updateImageSize($id,$url,$o_url){
        $image_size = getimagesize($url);
//        print_r($image_size);
        if (isset($image_size) && $image_size) {
            print '----------<br>';
            $size = array();
            // 入库
            if (isset($image_size) && $image_size) {
                $size[0]['config_width'] = $image_size[0];
                $size[0]['config_height'] = $image_size[1];
                $size[0]['src'] = 'https://star-1256165736.picgz.myqcloud.com/'.$o_url.'!small';
            }
            print $id.'----'.$url;
            print_r($size);
            print '<br>';
            Images::where('id', $id)->update([
                'pic_detail' => json_encode($size),
                'size_flag'=>true
            ]);
        }
    }
}