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
                $images = Images::where('origin','instagram')->where('status','active')->where('is_video',false)->orderBy('created_at', 'desc')->paginate(10);
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
     * update
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
     * @param $id
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function starImage($id,$type){
        if (Auth::user()) {
            $star = Star::where('id',$id)->first();
            if($type && $type =='wb'){
                $images = Images::where('origin','微博')->where('star_id',$id)->where('status','active')->where('is_video',false)->orderBy('mid', 'desc')->paginate(20);
            }elseif($type && $type =='ins'){
                $images = Images::where('origin','instagram')->where('star_id',$id)->where('status','active')->where('is_video',false)->orderBy('id', 'asc')->paginate(15);
            }else{
                $images = Images::where('is_video',false)->where('star_id',$id)->where('status','active')->orderBy('created_at', 'asc')->paginate(20);
            }
            return view('admin.img.images')
                ->with('star',$star)
                ->with('images',$images);
        }else{
            return Voyager::view('voyager::login');
        }
    }
    public function downloadHttpImages($star_id){
        $uploadImage = new QcloudUplodImage();
        $images = Images::where('star_id',$star_id)->where('origin','instagram')->where('status','active')->where('is_video',false)->whereNull('cos_url')->orderBy('id', 'desc')->paginate(5);
        foreach ($images as $image){
            info($image->display_url);
            $uploadImage->http_get_data($image->display_url,$star_id,$image->id,'ins');
        }
    }
}