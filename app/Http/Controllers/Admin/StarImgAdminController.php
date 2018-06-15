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
use Illuminate\Support\Facades\DB;

class StarImgAdminController extends BaseController
{
    use DispatchesJobs,
        ValidatesRequests,
        AuthorizesRequests,
        AuthenticatesUsers,
        AlertsMessages;

    public function __construct()
    {
        if (Auth::user()) {

        } else {
            return Voyager::view('voyager::login');
        }

    }

    public function index(Request $request){
        $star_count = DB::table('star')->where('status','active')->count();
        $user_count = DB::table('users')->count();
        $img_count = DB::table('star_img')->where('status','active')->count();
        return view('admin.index')
            ->with('user_count',$user_count)
            ->with('img_count',$img_count)
            ->with('star_count',$star_count);
    }
}