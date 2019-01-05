<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function postSiteMapToBaiDu($url){
        $urls = array($url);
        $api = 'http://data.zz.baidu.com/urls?site=https://starimg.cn&token=h8mnaYLAL7YF7pcK';
        try{
            $ch = curl_init();
            $options =  array(
                CURLOPT_URL => $api,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => implode("\n", $urls),
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            \Log::info('---url:--'.implode("\n", $urls).'------');
            \Log::info($result);
        }catch (Exception $e){
            var_dump($e);
        }
//        echo $result;
    }
}
