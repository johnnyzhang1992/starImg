<?php
/**
 * Created by PhpStorm.
 * User: johnnyZhang
 * Date: 2018/6/22
 * Time: 18:56
 */

namespace App\Helpers;

use Qcloud\Cos\Service;
use App\Models\Images;
use Qcloud\Cos\Client as QcloudClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Guzzle\Service\Resource\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

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
     * @param $type
     * @return mixed
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
//            info($result);
            info($result['ObjectURL']);
            return $result['ObjectURL'];
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
     * @return mixed
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
            return $result;
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }

    /**
     * 删除文件
     * @param $file_name
     * @return mixed
     */
    public function deleteObject($file_name){
        $cosClient = new QcloudClient($this->bucket_args);
        // 删除 COS 对象
        $result = $cosClient->deleteObject(array(
            //bucket 的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
            'Bucket' => $this->bucket,
            'Key' => $file_name));
        return $result;
    }

    /**
     * 获取文件权限控制信息
     * @param $key
     */
    public function getObjectACL($key){
        $cosClient = new QcloudClient($this->bucket_args);
        #getObjectACL
        try {
            $result = $cosClient->getObjectAcl(array(
                //bucket的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
                'Bucket' => 'testbucket-125000000',
                'Key' => $key));
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
    }
    /**
     * 获取对象属性
     * 查询获取 COS 上的对象属性
     * @param $key
     * @return mixed
     */
    public function headObject($key){
        $cosClient = new QcloudClient($this->bucket_args);
        // 获取 COS 文件属性
        //bucket 的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
        $result = $cosClient->headObject(array('Bucket' =>$this->bucket, 'Key' =>$key));
        return $result;
    }
    /**
     * 根据url下载文件到服务器
     * @param $url
     * @param $user
     * @param $id
     * @param $type
     * @throws $e
     * @return mixed
     */
    public function http_get_data($url,$user,$id,$type) {
//        $ch = curl_init ();
//        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
//        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
//        curl_setopt ( $ch, CURLOPT_URL, $url );
//        ob_start ();
//        curl_exec ( $ch );
//        $return_content = ob_get_contents ();
//        ob_end_clean ();
//        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
//        file_put_contents(public_path() .'/test/img/'.$_filename,$return_content);
//        return public_path() .'/test/img/'.$_filename;
        print $url.'<br>';
        // 创建服务器文件夹，授予权限
        if(!file_exists(public_path() .'/test/img/')) {
            if(mkdir(public_path() .'/test/img/',0777, true)) {
                info('创建文件夹成功');
                echo "创建文件夹成功";
            }else{
                info('创建文件夹失败');
                echo "创建文件夹失败";
            }
        }
        $_filename  = strtolower($user . '-' . str_random(8)) . '.jpg';
        if(file_exists(public_path() .'/test/img/'.$_filename)) {
            $_filename  = strtolower($user . '-' . str_random(8)) . '.jpg';

        }
        // 下载远程文件到服务器
        if(strpos($url,'s.insstar.cn') !== false){
//            print '原图片已丢失<br>';
//            return false;
            $url = str_replace('s.insstar.cn','inbmi.com',$url);
        }
        $client = new Client(['verify' => false]);  //忽略SSL错误
        $_res = null;
        try {
            $_res = $client->request('GET',$url);
        } catch (\RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
        if($_res){
//            echo $_res->getBody();
            info('status_code:'.$_res->getStatusCode());
            print('status_code:'.$_res->getStatusCode());
            if($_res->getStatusCode() == 200) {
                $response = $client->get($url, ['save_to' => public_path().'/test/img/'.$_filename]);  //保存远程url到文件
                if($response){
                    // 转存文件到腾讯云
                    $result = $this->putImageToCos($user,$_filename,$type);
                    $image_size = getimagesize( public_path().'/test/img/'.$_filename);
                    if(isset($result) && $result){
                        $size = array();
                        // 入库
                        if(isset($image_size) && $image_size){
                            $size[0]['config_width'] = $image_size[0];
                            $size[0]['config_height'] = $image_size[1];
                            $size[0]['src'] = 'https://i.starimg.cn/star/'.$user.'/'.$type.'/'.$_filename.'!small';
                        }
                        Images::where('id',$id)->update([
                            'pic_detail'=>json_encode($size),
                            'cos_url' =>'star/'.$user.'/ins/'.$_filename,
                            'size_flag' =>true
                        ]);

                        echo 'https://i.starimg.cn/star/'.$user.'/'.$type.'/'.$_filename.'!small'.'<br>';
                        // 删除服务器暂存文件
                        unlink(public_path() .'/test/img/'.$_filename);
                    }
                }
            } else{
                print '请求页面不存在';
            }
        }
    }

    /**
     * 下载用户头像到本地
     * @param $url
     * @param $user
     * @param $type
     * @throws $e
     * @return mixed
     */

    public function http_get_data1($url,$user,$type) {
        print $url.'<br>';
        // 创建服务器文件夹，授予权限
        if(!file_exists(public_path() .'/test/img/')) {
            if(mkdir(public_path() .'/test/img/',0777, true)) {
                info('创建文件夹成功');
                echo "创建文件夹成功";
            }else{
                info('创建文件夹失败');
                echo "创建文件夹失败";
            }
        }
        $_filename  = strtolower($user . '-' . str_random(8)) . '.jpg';
        if(file_exists(public_path() .'/test/img/'.$_filename)) {
            $_filename  = strtolower($user . '-' . str_random(8)) . '.jpg';

        }
        // 下载远程文件到服务器
        if(strpos($url,'s.insstar.cn') !== false){
//            print '原图片已丢失<br>';
//            return false;
            $url = str_replace('s.insstar.cn','inbmi.com',$url);
        }
        $client = new Client(['verify' => false]);  //忽略SSL错误
        $_res = null;
        try {
            $_res = $client->request('GET',$url);
        } catch (\RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
        if($_res){
//            echo $_res->getBody();
            info('status_code:'.$_res->getStatusCode());
            print('status_code:'.$_res->getStatusCode());
            if($_res->getStatusCode() == 200) {
                $response = $client->get($url, ['save_to' => public_path().'/test/img/'.$_filename]);  //保存远程url到文件
                if($response){
                    // 转存文件到腾讯云
                    $result = $this->putImageToCos($user,$_filename,$type);
                    if(isset($result) && $result){
                        // 入库
                        DB::table('star')->where('id',$user)->update([
                            'avatar'=> 'https://img.starimg.cn/star/'.$user.'/'.$type.'/'.$_filename
                        ]);

                        echo 'https://img.starimg.cn/star/'.$user.'/'.$type.'/'.$_filename.'!small'.'<br>';
                        // 删除服务器暂存文件
                        unlink(public_path() .'/test/img/'.$_filename);
                    }
                }
            } else{
                print '请求页面不存在';
            }
        }
    }
}