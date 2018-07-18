<?php
/**
 * Created by PhpStorm.
 * User: johnnyZhang
 * Date: 2018/6/22
 * Time: 18:56
 */

namespace App\Helpers;

use Qcloud\Cos\Service;
use Guzzle\Service\Resource\Model;
use App\Models\Images;
use Qcloud\Cos\Client as QcloudClient;

class QcloudUplodImage{
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

    /**
     * 获取 Bucket 列表
     */
    public function getBuckets(){
        $cosClient = new QcloudClient($this->bucket_args);
        $res = $cosClient->listBuckets();
        info($res);
    }

    /**
     * 获取文件列表
     */
    public function ListObjects(){
        $cosClient = new QcloudClient($this->bucket_args);
        $result = $cosClient->listObjects(array('Bucket' => $this->bucket));
        info($result);
    }

    /**
     * 简单上传文件
     */
    public function putObjectToCos(){
        $cosClient = new QcloudClient($this->bucket_args);
        $_key = '';//文件名称已经目录
        //putObject(上传接口，最大支持上传5G文件)

        // 上传内存中的字符串
        try {
            $result = $cosClient->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => 'b.txt',
                'Body' => 'Hello World!'));
//            $result = $cosClient->doesBucketExist('star-1256165736');
            info($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }


        ### 设置 header 和 meta
        try {
            $result = $cosClient->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $_key,
                'Body' => fopen(public_path(), 'rb'),
                'ACL' => 'string',
                'CacheControl' => 'string',
                'ContentDisposition' => 'string',
                'ContentEncoding' => 'string',
                'ContentLanguage' => 'string',
                'ContentLength' => 222222,
                'ContentType' => 'string',
                'Expires' => 'mixed type: string (date format)|int (unix timestamp)|\DateTime',
                'GrantFullControl' => 'string',
                'GrantRead' => 'string',
                'GrantWrite' => 'string',
                'Metadata' => array(
                    'string' => 'string',
                ),
                'StorageClass' => 'string'));
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }

    /**
     * 简单上传图片
     * 大小小于5M
     * @param $star_id
     * @param $img_name
     */
    public function putImageToCos($star_id,$img_name,$type){
        $cosClient = new QcloudClient($this->bucket_args);
        $file_size = filesize(public_path().'/test/img/'.$img_name);
        ### 上传文件流
        try {
            $result = $cosClient->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => 'star/'.$star_id.'/'.$type.'/'.$img_name,
                'ContentLength' => $file_size,
                'StorageClass' => 'STANDARD',
                'Body' => fopen(public_path().'/test/img/'.$img_name, 'rb'))
            );
            info($result);
            info($result['ObjectURL']);
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }
    /**
     * 分块文件上传
     */
    public function upload(){
        $cosClient = new QcloudClient($this->bucket_args);
        $_key = '';
        // $key 文件名
        ## Upload(高级上传接口，默认使用分块上传最大支持50T)

        ### 上传内存中的字符串
        try {
            $result = $cosClient->Upload(
                $bucket = $this->bucket,
                $key = $_key,
                $body = 'Hello World!');
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }

        ### 上传文件流
        try {
            $result = $cosClient->Upload(
                $bucket = $this->bucket,
                $key = $_key,
                $body = fopen(public_path(), 'rb'));
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }

        ### 设置 header 和 meta
        try {
            $result = $cosClient->upload(
                $bucket= $this->bucket,
                $key = $_key,
                $body = fopen(public_path(), 'rb'),
                $options = array(
                    'ACL' => 'string',
                    'CacheControl' => 'string',
                    'ContentDisposition' => 'string',
                    'ContentEncoding' => 'string',
                    'ContentLanguage' => 'string',
                    'ContentLength' => 200000,//文本大小
                    'ContentType' => 'string',
                    'Expires' => 'mixed type: string (date format)|int (unix timestamp)|\DateTime',
                    'GrantFullControl' => 'string',
                    'GrantRead' => 'string',
                    'GrantWrite' => 'string',
                    'Metadata' => array(
                        'string' => 'string',
                    ),
                    'StorageClass' => 'string'));
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }

    /**
     * 分块上传大文件
     * @param $star_id
     * @param $img_name
     */
    public function uploadImageToCos($star_id,$img_name){
        $cosClient = new QcloudClient($this->bucket_args);
        ### 上传文件流
        try {
            $result = $cosClient->Upload(
                $bucket = $this->bucket,
                $key = 'star/'.$star_id.'/'.$img_name,
                $body = fopen(public_path().'/test/img/'.$img_name, 'rb'));
            info($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }
    /**
     * 根据url下载文件到服务器
     * @param $url
     * @param $user
     * @param $id
     * @param $type
     * 返回文件地址
     * @return mixed
     */
    public function http_get_data($url,$user,$id,$type) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        ob_start ();
        curl_exec ( $ch );
        $return_content = ob_get_contents ();
        ob_end_clean ();
        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        print $url.'<br>';
        print_r($return_code);
        if(!file_exists(public_path() .'/test/img/')) {
            if(mkdir(public_path() .'/test/img/',0777, true)) {
                echo "创建文件夹成功";
            }else{
                echo "创建文件夹失败";
            }
        }

        $_filename  = strtolower($user . '-' . str_random(4)) . '.jpg';
        file_put_contents(public_path() .'/test/img/'.$_filename,$return_content);
        $this->putImageToCos($user,$_filename,$type);
        Images::where('id',$id)->update([
            'cos_url' =>'star/'.$user.'/ins/'.$_filename
        ]);
        echo '<img src="https://i.starimg.cn/star/'.$user.'/'.$type.'/'.$_filename.'!small" style="width:200px"><br>';
        unlink(public_path() .'/test/img/'.$_filename);
//
//        return public_path() .'/test/img/'.$_filename;
    }
}