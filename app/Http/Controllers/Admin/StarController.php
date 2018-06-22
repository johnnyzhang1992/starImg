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
use Qcloud\Cos\Service;
use Qcloud\Cos\Client as QcloudClient;
//require __DIR__ . '/vendor/autoload.php';

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
    public function http_get_data($url,$user) {

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        ob_start ();
        curl_exec ( $ch );
        $return_content = ob_get_contents ();
        ob_end_clean ();
        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        if(!file_exists(public_path() .'/test/img/')) {
            if(mkdir(public_path() .'/test/img/',0777, true)) {
                echo "创建文件夹成功";
            }else{
                echo "创建文件夹失败";
            }
        }
                $_filename  = strtolower(base64_encode($user . '-' . str_random(4))) . '.jpg';
        file_put_contents(public_path() .'/test/img/'.$_filename,$return_content);
        /* 压缩头像 */
//        $tmp_file=explode('.',$_filename);
//
//        copy(public_path() .'/test/img/'.$_filename,public_path().'/test/img/'.$tmp_file[0].'-orign.'.$tmp_file[1]);

        print public_path() .'/test/img/'.$_filename;
    }
}