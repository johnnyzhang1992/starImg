<?php


namespace App\Http\Controllers\Admin;

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
use App\Helpers\QcloudUplodImage;

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
        // test
    }
    // index page
    public function index(Request $request){
        return view('admin.star.index')
            ->with('show_actions',$this->show_action)
            ->with( 'listing_cols' ,$this->listing_cols)
            ->with('page_title','查看 Stars');
    }

    public function dtajax()
    {
        $values = DB::table('star')
            ->select('star.id','star.domain','star.name','star.description','star.gender','star.follow_count','star.created_at','star.updated_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        $_data = [];
        foreach ($data->data as $key=>$_item){
            $_arr = [];
            for($i=0;$i<count($this->listing_cols);$i++){
                $_val = $this->listing_cols[$i];
                $_arr[$i]= $_item->$_val;
            }
            array_push($_data,$_arr);
        }
        $data->data = $_data;
        for($i=0; $i < count($data->data); $i++) {
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url('/admin/stars/'.$data->data[$i][0]).'" class="btn btn-sm btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">视图</span></a>';
                $output .= ' <a href="'.url('/admin/stars/'.$data->data[$i][0]).'/edit'.'" class="btn btn-sm btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">编辑</span></a>';
                $output .= ' <a href="'.url('/admin/stars/'.$data->data[$i][0]).'/delete'.'" class="btn btn-sm btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">编辑</span></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);

        return $out;
    }

}