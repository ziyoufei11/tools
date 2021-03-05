<?php

namespace Lss\EasyTools\Tools\CodeControl;

/**
 *  代码加密
 */
class CodeEncryption
{
    static $data = [
        'code' => 0,
        'msg'  => '成功'
    ];

    /**
     * 加/解密目录至指定目录
     * @param $dir 需要加密的目录
     * @param $toDir
     *
     */
    public static function codeExec($dir, $toDir, $encode = True)
    {
        $data = self::$data;
        try {
            if (!is_dir($dir)) return;
            //递归读取文件
            self::readDir($dir, $dir, $toDir, $encode);
            return $data;
        } catch (\Throwable $e) {
            self::deleteDir($toDir);
            $data['code'] = $e->getCode();
            $data['msg']  = $e->getMessage();
            return $data;
        }
    }

    //读取目录
    static function readDir($dir, $topDir, $toDir, $encode = true)
    {
        if ($path = opendir($dir)) {
            while (false !== ($file = readdir($path))) {
                if ((is_dir($dir . "/" . $file)) && $file != "." && $file != "..") {
                    self::readDir($dir . "/" . $file . '/', $topDir, $toDir, $encode);
                } else {
                    if ($file == "." || $file == "..") continue;
                    //获取当前目录
                    $dirName = str_replace('//', '/', $dir);
                    //获取目标目录
                    $toDirName = str_replace($topDir, $toDir, $dirName);
                    //创建目标目录
                    if (!is_dir($toDirName) && !mkdir($toDirName, 0777, true)) throw new \Exception('创建目标目录失败');
                    if ($encode) {
                        self::encode($dirName . $file, $toDirName . $file);
                    } else {
                        self::decode($dirName . $file, $toDirName . $file);
                    }
                }
            }
            closedir($path);
        }
    }

    //删除目录及文件
    static function deleteDir($path)
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

    //代码加密
    private static function encode($readFile, $writeFile)
    {
        $T_k1 = self::RandAbc(); //随机密匙1
        $T_k2 = self::RandAbc(); //随机密匙2
        $vstr = file_get_contents($readFile);
        $v1   = base64_encode($vstr);
        $c    = strtr($v1, $T_k1, $T_k2); //根据密匙替换对应字符。
        $c    = $T_k1 . $T_k2 . $c;
        $q1   = "O00O0O";
        $q2   = "O0O000";
        $q3   = "O0OO00";
        $q4   = "OO0O00";
        $q5   = "OO0000";
        $q6   = "O00OO0";
        $s    = '$' . $q6 . '=urldecode("%6E1%7A%62%2F%6D%615%5C%76%740%6928%2D%70%78%75%71%79%2A6%6C%72%6B%64%679%5F%65%68%63%73%77%6F4%2B%6637%6A");
        $' . $q1 . '=$' . $q6 . '[3].$' . $q6 . '[6].$' . $q6 . '[33].$' . $q6 . '[30];
        $' . $q3 . '=$' . $q6 . '[33].$' . $q6 . '[10].$' . $q6 . '[24].$' . $q6 . '[10].$' . $q6 . '[24];$' . $q4 . '=$' . $q3 . '[0].$' . $q6 . '[18].$' . $q6 . '[3].$' . $q3 . '[0].$' . $q3 . '[1].$' . $q6 . '[24];
        $' . $q5 . '=$' . $q6 . '[7].$' . $q6 . '[13];
        $' . $q1 . '.=$' . $q6 . '[22].$' . $q6 . '[36].$' . $q6 . '[29].$' . $q6 . '[26].$' . $q6 . '[30].$' . $q6 . '[32].$' . $q6 . '[35].$' . $q6 . '[26].$' . $q6 . '[30];
        eval($' . $q1 . '("' . base64_encode('$' . $q2 . '="' . $c . '";
        eval(\'?>\'.$' . $q1 . '($' . $q3 . '($' . $q4 . '($' . $q2 . ',$' . $q5 . '*2),$' . $q4 . '($' . $q2 . ',$' . $q5 . ',$' . $q5 . '),$' . $q4 . '($' . $q2 . ',0,$' . $q5 . '))));') . '"));';

        $s = '<?php ' . "\n" . $s . "\n" . ' ?>';
        // 生成 加密后的PHP文件
        if (!file_put_contents($writeFile, $s)) throw new \Exception('写入文件错误');
    }

    private static function RandAbc($length = "")
    { // 返回随机字符串
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        return str_shuffle($str);
    }

    //根据上方加密解密
    private static function decode($readFile, $writeFile)
    {
        $content  = file_get_contents($readFile);
        $content  = str_replace('<?php', '', $content);
        $content  = str_replace('?>', '', $content);
        $content  = trim($content);
        $allArray = explode(';', $content);
        $count    = count($allArray);
        foreach ($allArray as $k => $v) {
            if ($k == $count - 2) {
                $temp = str_replace('eval(', '', $v);
                $temp = str_replace('$O00O0O("', '', $temp);
                $temp = substr($temp, 0, -3);
                $a    = $O00O0O($temp . ';');
            } else {
                eval($v . ';');
            }
        }
        $array  = explode(';', $a);
        $O0O000 = str_replace('$O0O000="', '', $array[0]);
        $O0O000 = str_replace('"', '', $O0O000);
        $s      = $O00O0O($O0OO00($OO0O00($O0O000, $OO0000 * 2), $OO0O00($O0O000, $OO0000, $OO0000), $O0OO00($O0O000, 0, $OO0000)));
        // 生成 加密后的PHP文件
        if (!file_put_contents($writeFile, $s)) throw new \Exception('写入文件错误');
    }

    //轮询去注释
    public static function read_all($dir)
    {
        if (!is_dir($dir)) return false;

        $handle = opendir($dir);
        if ($handle) {
            while (($fl = readdir($handle)) !== false) {
                $temp = $dir . DIRECTORY_SEPARATOR . $fl;
                //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
                if (is_dir($temp) && $fl != '.' && $fl != '..') {
                    echo '目录：' . $temp . '<br>';
                    self::read_all($temp);
                } else {
                    if ($fl != '.' && $fl != '..') {
                        file_put_contents($temp, php_strip_whitespace($temp));
                        echo '文件：' . $temp . '<br>';
                    }
                }
            }
        }
    }
}
