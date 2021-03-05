<?php

namespace Lss\EasyTools\Tools\CodeControl;

/**
 *  代码加密
 */
class CodeDelAnnotation
{
    private static $data = [
        'code' => 0,
        'msg'  => '成功'
    ];

    /**
     * 去注释目录至指定目录
     * @param $dir 需要加密的目录
     * @param $toDir
     *
     */
    public static function codeExec($dir, $toDir)
    {
        $data = self::$data;
        try {
            if (!is_dir($dir)) return;
            //递归读取文件
            self::readDir($dir, $dir, $toDir);
            return $data;
        } catch (\Throwable $e) {
            self::deleteDir($toDir);
            $data['code'] = $e->getCode();
            $data['msg']  = $e->getMessage();
            return $data;
        }
    }

    //读取目录
    private static function readDir($dir, $topDir, $toDir)
    {
        if ($path = opendir($dir)) {
            while (false !== ($file = readdir($path))) {
                if ((is_dir($dir . "/" . $file)) && $file != "." && $file != "..") {
                    self::readDir($dir . "/" . $file . '/', $topDir, $toDir);
                } else {
                    if ($file == "." || $file == "..") continue;
                    //获取当前目录
                    $dirName = str_replace('//', '/', $dir);
                    //获取目标目录
                    $toDirName = str_replace($topDir, $toDir, $dirName);
                    //创建目标目录
                    if (!is_dir($toDirName) && !mkdir($toDirName, 0777, true)) throw new \Exception('创建目标目录失败');
                    file_put_contents($toDirName . $file, php_strip_whitespace($dirName . $file));
                }
            }
            closedir($path);
        }
    }

    //删除目录及文件
    private static function deleteDir($path)
    {
        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);

            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ($dir != '.' && $dir != '..') {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path . '/' . $dir;
                    if (is_dir($sonDir)) {
                        //递归删除
                        self::deleteDir($sonDir);
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {

                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
    }

}
