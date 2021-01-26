<?php

namespace Lss\EasyTools\Tools\Upload;

use OSS\Core\OssException;
use OSS\OssClient;
use Psr\Http\Message\ServerRequestInterface;

class AliUpload
{
    private $accessKeyId;    //id
    private $accessKeySecret;//token
    private $endpoint;       //服务器节点
    private $bucket;         //储存桶
    private $securityToken;  //子账号token
    private $ossClient;

    public function __construct()
    {
        $this->accessKeyId     = env('OSS_ACCESS_ID');
        $this->accessKeySecret = env('OSS_ACCESS_SECRET');
        $this->endpoint        = env('OSS_ENDPOINT');
        $this->bucket          = env('OSS_BUCKET');
        $this->securityToken   = env('OSS_SECURITYTOKEN');
        if (!$this->ossClient) {
            try {
                if ($this->securityToken) {
                    //使用阿里云主账号
                    $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint, false, $this->securityToken);
                } else {
                    //使用阿里云子账号
                    $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint, false);
                }
            } catch (OssException $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    public function getOssClient()
    {
        return $this->ossClient;
    }

    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * 获取阿里上传链接地址 由移动端直接上传至服务器
     * @param $fileName
     * @param null $option
     * @param int $timeout
     * @return mixed
     *
     */
    public function getPutUrl($fileName, $option = null, $timeout = 600)
    {
        return $this->ossClient->signUrl($this->bucket, $fileName, $timeout, 'PUT', $option);
    }

    /**
     * 获取阿里查看链接地址 //私有授权
     * @param $fileName
     * @param float|int $timeout
     * @return mixed
     */
    public function getGetUrl($fileName, $timeout = 24 * 3600)
    {
        return $this->ossClient->signUrl($this->bucket, $fileName, $timeout, 'GET');
    }

}
