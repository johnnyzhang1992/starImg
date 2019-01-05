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
use Illuminate\Support\Facades\DB;

class PinController extends Controller
{
    public function __construct(){
//        $this->middleware('auth');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function pinDetail($id){
        $pin = DB::table('star_img')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->where('star_img.id','=',$id)
            ->where('star_img.status','=','active')
            ->select('star.domain','star.name','star.description','star_img.*')
            ->first();
        if(isset($pin) && $pin){
            $this->postSiteMapToBaiDu(asset('/pin/'.$pin->id));
            return view('frontend.pin.show')
                ->with('site_title',@$pin->name.'的图片 | @'.$pin->domain)
                ->with('site_keywords',$pin->name.','.$pin->domain.','.config('seo.keywords'))
                ->with('site_description',strip_tags($pin->text))
                ->with('pin',$pin);
        }else{
            abort(404);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPinDetail($id){
        $pin = DB::table('star_img')
            ->leftJoin('star','star.id','=','star_img.star_id')
            ->leftJoin('star_wb','star_wb.star_id','=','star_img.star_id')
            ->where('star_img.id','=',$id)
            ->where('star_img.status','=','active')
            ->select('star.avatar','star.domain','star.name','star.description','star_img.*','star_wb.verified')
            ->first();
        if(isset($pin) && $pin){
            if(isset($pin->pic_detail) && $pin->pic_detail){
                $pin->pic_detail = json_decode($pin->pic_detail);
            }
            $pin->text = strip_tags($pin->text);
            $data['pin'] = $pin;
            return response()->json($data);
        }else{
            $data['status'] = 404;
            $data['msg'] = '内容不存在';
            return response()->json($data);
        }
    }
}
