<?php
/**
 * Created by PhpStorm.
 * User: johnnyZhang
 * Date: 2018/6/22
 * Time: 18:56
 */

namespace App\Helpers;

require_once __DIR__ . '/QCloundImg/index.php';
use QcloudImage\CIClient;
use App\Models\Images;

class QcloundCiImage{
    /**
     * 云对象存储cos-php-sdk-v5
     */

    private $bucket = 'star-1256165736';
    private $origin = null;
    private $credentials = null;
    private $bucket_args = [];
    /**
     * QcloudUplodImage constructor.
     */
    public function __construct(){
        $this->origin = config('qcloudcos.location');
        $this->credentials =  array(
            'appId'     => config('qcloudcos.app_id'),
            'secretId'  => config('qcloudcos.secret_id'),
            'secretKey' => config('qcloudcos.secret_key')
        );
        $this->bucket_args = $this->setBucketArgus();
    }
    public function setBucketArgus(){
        return array(
            'region' =>$this->origin,
            'credentials' => $this->credentials
        );
    }

    static function porn_detect($img,$type){
        // $img is []
        $client = new CIClient(config('qcloudcos.app_id'),config('qcloudcos.secret_id'), config('qcloudcos.secret_key'), 'star-1256165736');
        $client->setTimeout(30);
        if($type == 'urls'){
            $res = $client->pornDetect(array('urls'=>$img));
            print_r($res);
        }else{
           $res = $client->pornDetect(array('files'=>$img));
            print_r($res);
        }
    }
}