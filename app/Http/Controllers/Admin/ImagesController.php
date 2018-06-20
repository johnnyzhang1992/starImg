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
              $images = Images::where('origin','微博')->where('status','active')->where('is_video',false)->orderBy('attitudes_count','desc')->orderBy('created_at', 'desc')->paginate(20);
            }elseif($type && $type =='ins'){
                $images = Images::where('origin','instagram')->where('is_video',false)->orderBy('attitudes_count','desc')->orderBy('created_at', 'desc')->paginate(20);
            }else{
                $images = Images::where('is_video',false)->where('status','active')->orderBy('attitudes_count','desc')->orderBy('created_at', 'asc')->paginate(20);
            }
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
}