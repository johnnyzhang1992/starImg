<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Events\Routing;
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
use Yajra\DataTables\DataTables;

class StarController extends BaseController
{
    use DispatchesJobs,
        ValidatesRequests,
        AuthorizesRequests,
        AuthenticatesUsers,
        AlertsMessages;
    public $show_action = true;
    public $listing_cols = ['id','domain','name','description','gender','follow_count','created_at','updated_at'];
    public function __construct(){

    }
    // index page
    public function index(Request $request){

        return view('admin.star.index')
            ->with('show_actions',$this->show_action)
            ->with( 'listing_cols' ,$this->listing_cols)
            ->with('page_title','明星列表');
    }

    public function dtajax()
    {
        $values = DB::table('star')
            ->select('star.id','star.domain','star.name','star.description','star.gender','star.follow_count','star.created_at','star.updated_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        info(count($data->data));
        info(\GuzzleHttp\json_encode($data->data));
        $_data = [];
        foreach ($data->data as $key=>$_item){
            $_arr = [];
            info(json_encode($_item));
            for($i=0;$i<count($this->listing_cols);$i++){
                $_val = $this->listing_cols[$i];
                info($this->listing_cols[$i]);
                $_arr[$i]= $_item->$_val;
            }
            info(json_encode($_arr));
            array_push($_data,$_arr);
        }
        $data->data = $_data;
//        info(\GuzzleHttp\json_encode($_data));
        for($i=0; $i < count($data->data); $i++) {
//            $data->data[$i][6] = json_decode($data->data[$i][6] );
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url('/admin/stars/'.$data->data[$i][0]).'" class="btn btn-sm btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">视图</span></a>';
                $output .= ' <a href="'.url('/admin/stars/'.$data->data[$i][0]).'/edit'.'" class="btn btn-sm btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-edit"></i><span class="hidden-xs hidden-sm">编辑</span></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);

        return $out;
    }
}