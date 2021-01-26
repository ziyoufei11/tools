<?php

namespace Lss\EasyTools\Tools\Upload;

use Aws\S3\S3Client;

/**
 * 亚马逊文件上传
 * 需要引入composer aws/aws-sdk-php
 * Class AwsUpload
 */
class AwsUpload
{
    /**
     * s3实例
     * @var S3Client
     */
    private $s3Client;

    /**
     * 配置信息
     * @var mixed
     */
    private $config = [];

    //美国东部（俄亥俄州）(us-east-2)
    //
    //用户访问网站 – https://us-east-2.quicksight.amazonaws.com
    //
    //IP 地址范围 – 52.15.247.160/27
    //
    //终端节点 (HTTPS) – quicksight.us-east-2.amazonaws.com
    //
    //美国东部（弗吉尼亚北部）(us-east-1)
    //
    //用户访问网站 – https://us-east-1.quicksight.amazonaws.com
    //
    //IP 地址范围 – 52.23.63.224/27
    //
    //终端节点 (HTTPS) – quicksight.us-east-1.amazonaws.com
    //
    //美国西部（俄勒冈）(us-west-2)
    //
    //用户访问网站 – https://us-west-2.quicksight.amazonaws.com
    //
    //IP 地址范围 – 54.70.204.128/27
    //
    //终端节点 (HTTPS) – quicksight.us-west-2.amazonaws.com
    //
    //亚太地区（孟买） (ap-south-1)
    //
    //用户访问网站 – https://ap-south-1.quicksight.amazonaws.com
    //
    //IP 地址范围 – 52.66.193.64/27
    //
    //终端节点 (HTTPS) – quicksight.ap-south-1.amazonaws.com
    //
    //亚太区域（首尔）(ap-northeast-2)
    //
    //用户访问网站 – https://ap-northeast-2.quicksight.aws.amazon.com
    //
    //IP 地址范围 – 13.124.145.32/27
    //
    //终端节点 (HTTPS) – quicksight.ap-northeast-2.amazonaws.com
    //
    //亚太区域（新加坡）(ap-southeast-1)
    //
    //用户访问网站 – https://ap-southeast-1.quicksight.aws.amazon.com
    //
    //IP 地址范围 – 13.229.254.0/27
    //
    //终端节点 (HTTPS) – quicksight.ap-southeast-1.amazonaws.com
    //
    //亚太区域（悉尼）(ap-southeast-2)
    //
    //用户访问网站 – https://ap-southeast-2.quicksight.amazonaws.com
    //
    //IP 地址范围 – 54.153.249.96/27
    //
    //终端节点 (HTTPS) – quicksight.ap-southeast-2.amazonaws.com
    //
    //亚太区域（东京）(ap-northeast-1)
    //
    //用户访问网站 – https://ap-northeast-1.quicksight.amazonaws.com
    //
    //IP 地址范围 – 13.113.244.32/27
    //
    //终端节点 (HTTPS) – quicksight.ap-northeast-1.amazonaws.com
    //
    //欧洲（法兰克福）(eu-central-1)
    //
    //用户访问网站 – https://eu-central-1.quicksight.amazonaws.com
    //
    //IP 地址范围 – 35.158.127.192/27
    //
    //终端节点 (HTTPS) – quicksight.eu-central-1.amazonaws.com
    //
    //欧洲（爱尔兰）(eu-west-1)
    //
    //用户访问网站 – https://eu-west-1.quicksight.amazonaws.com
    //
    //IP 地址范围 – 52.210.255.224/27
    //
    //终端节点 (HTTPS) – quicksight.eu-west-1.amazonaws.com
    //
    //欧洲（伦敦）(eu-west-2)
    //
    //用户访问网站 – https://eu-west-2.quicksight.aws.amazon.com
    //
    //IP 地址范围 – 35.177.218.0/27
    //
    //终端节点 (HTTPS) – quicksight.eu-west-2.amazonaws.com
    //

    /**
     * 初始化,需要传入亚马逊配置及本地包含key、secret的credentials文件
     * credentials 位置~/.aws/credentials on Linux, macOS, or Unix
     * 可以通过修改亚马逊默认获取系统参数HOME来指定目录
     * AwsUpload constructor.
     * @param $options [
     * 'profile' => 'default',//配置项
     * 'region' => 'us-west-2',//区域
     * 'version' => 'latest',//版本
     * 'http' => [
     * 'verify' => '/home/www/xxxx.pem' //证书名称
     * ],
     * 'Bucket' => '存储桶名称',
     * 'img_host' => "https://prod-live-bucket.s3-ap-southeast-1.amazonaws.com"//图片域名存储地址
     * 'img_cdn' => "xxx.com"//图片cdn存储地址
     * 'thumb' => "xxx.com"//亚马逊图片处理服务器
     * ]
     */
    public function __construct($options)
    {
        //        putenv('HOME=' . './');
        $this->config   = $options;
        $this->s3Client = new S3Client($this->config);
    }

    /**
     * 获取一个上传地址 由移动端直接上传至服务器
     * @param $filename
     * @param string $dir
     * @param int $time
     * @return string
     */
    public function getUploadUrl($filename, $dir = '', $time = 5)
    {
        $cmd          = $this->s3Client->getCommand('PutObject', [
            'Bucket' => $this->config['Bucket'],
            'Key'    => $dir ? $dir . '/' . $filename : $filename
        ]);
        $request      = $this->s3Client->createPresignedRequest($cmd, '+' . $time . ' minutes');
        $presignedUrl = (string)$request->getUri();
        return $presignedUrl;
    }


    /**
     * 本地上传 由服务器上传至亚马逊
     * 默认公共打开
     * @param $file_tmp 临时文件/base64图片
     * @param $dir 保存目录
     * @param $type 文件类型 如果不指定.浏览器打开时会直接下载
     * @param $open true:临时文件 false:base64流
     * @return mixed
     */
    public function localUploadFile($file_tmp, $dir, $type, $open = true)
    {
        $bucket   = $this->config['Bucket'];
        $response = $this->s3Client->upload(
            $bucket,
            $dir,
            $open ? fopen($file_tmp, 'r') : $file_tmp,
            'public-read',
            array('params' => array('ContentType' => $type))
        );
        return $response;
    }

    /**
     * 获取文件查看链接
     * @param $file
     * @return string
     *
     */
    public function getImageUrl($file)
    {
        if (!isset($this->config['img_cdn']) || !$this->config['img_cdn']) return $this->config['img_host'] . '/' . $file;
        return $this->config['img_cdn'] . '/' . $file;
    }

    /**
     * 将已有亚马逊图片处理,此为生成一张小图
     * @param $url
     * @param int $width
     * @return string
     *
     */
    public function getThumbUrl($url, $width = 100)
    {
        $tempArray = explode('amazonaws.com/', $url);
        $filename  = $tempArray[1];
        /* 图片属性选配
         * edits:
         *  resize:width   height   fit:cover,contain,fill,inside,outside
         *  grayscale
         *  flip
         *  flop
         *  negate
         *  flatten
         *  normalise
         *  tint:r  g  b
         *  smartCrop:faceIndex:0  "padding": 1 裁剪填充 智能脸识别,识别的第n张脸
         */
        //fit 种类:cover,contain,fill,inside,outside
        $data = [
            'buket' => $this->config['Bucket'],
            'key'   => $filename,
            'edits' => [
                'resize' => [
                    'width' => $width,
                    'fit'   => 'cover'
                ]
            ]
        ];
        $base = base64_encode(json_encode($data));
        $url  = $this->config['thumb'] . '/' . $base;
        return $url;
    }

}