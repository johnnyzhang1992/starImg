<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use TCG\Voyager\Traits\AlertsMessages;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Helpers\QcloudUplodImage;
use App\Models\Star;
use App\Models\Images;

class StarController extends BaseController
{
    use DispatchesJobs,
        ValidatesRequests,
        AuthorizesRequests,
        AuthenticatesUsers,
        AlertsMessages;
    public $show_action = true;
    public $listing_cols = ['id','domain','name','description','gender','follow_count','created_at'];
    public function __construct(){
        // test
    }
    // index page
    public function index(Request $request){
        return view('admin.star.index')
            ->with('show_actions',$this->show_action)
            ->with( 'listing_cols' , ['id','domain','name','description','gender','follow_count','created_at','wb_images','ins_images'])
            ->with('page_title','查看 Stars');
    }
    public function show($id){
        $star = Star::where('id',$id)->first();
        $star_wb = DB::table('star_wb')->where('star_id',$id)->first();
        $star_ins = DB::table('star_ins')->where('star_id',$id)->first();
        $wb_img_count = Images::where('is_video',false)->where('star_id',$id)->where('status','active')->where('origin','微博')->count();
        $ins_img_count = Images::where('is_video',false)->where('star_id',$id)->where('status','active')->where('origin','instagram')->count();
        if(isset($star) && $star){
            return view('admin.star.show')
                ->with('star',$star)
                ->with('star_wb',$star_wb)
                ->with('star_ins',$star_ins)
                ->with('wb_img_count',$wb_img_count)
                ->with('ins_img_count',$ins_img_count)
                ->with('page_title','视图 Star '.$star->name);
        }else{
            abort(404);
        }

    }

    public function dtajax()
    {
        $values = DB::table('star')
            ->select('star.id','star.domain','star.name','star.description','star.gender','star.follow_count','star.created_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        $_data = [];
        foreach ($data->data as $key=>$_item){
            $_arr = [];
            for($i=0;$i<count($this->listing_cols);$i++){
                $_val = $this->listing_cols[$i];
                $_arr[$i]= $_item->$_val;
            }
            array_push($_arr,Images::where('is_video',false)->where('star_id',$_item->id)->where('status','active')->where('origin','微博')->count());
            array_push($_arr,Images::where('is_video',false)->where('star_id',$_item->id)->where('status','active')->where('origin','instagram')->count());
            array_push($_data,$_arr);
        }
        $data->data = $_data;
        for($i=0; $i < count($data->data); $i++) {
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url('/admin/stars/'.$data->data[$i][0]).'" class="btn btn-sm btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;margin-right: 5px" target="_blank"><i class="voyager-eye"></i> </a>';
                $output .= ' <a href="'.url('/admin/stars/'.$data->data[$i][0]).'/edit'.'" class="btn btn-sm btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;margin-right: 5px" target="_blank"><i class="voyager-edit"></i> </a>';
                $output .= ' <a href="'.url('/admin/stars/'.$data->data[$i][0]).'/delete'.'" class="btn btn-sm btn-danger btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="voyager-trash"></i> </a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);

        return $out;
    }

}