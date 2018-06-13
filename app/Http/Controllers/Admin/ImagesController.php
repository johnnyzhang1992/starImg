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
        if (Auth::user()) {

        }else{
            return Voyager::view('voyager::login');
        }

    }

    public function index(Request $request){
        if (Auth::user()) {
            //            $images = Images::orderBy('id', 'asc')->paginate(15);
            $images = Images::where('is_video',false)->orderBy('id', 'asc')->paginate(20);
            return view('admin.images') ->with('images',$images);
        }else{

            return Voyager::view('voyager::login');

        }
    }
}