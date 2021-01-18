<?php

namespace Lss\EasyTools\Tools\MsgRobot;
/**
 * 钉钉报错机器人
 * 官方设定一个机器人一秒上线10次请求
 * 使用加签安全方式发送
 * 官方文档:https://ding-doc.dingtalk.com/doc#/serverapi2/qf2nxq/e9d991e2
 */
class DingDingRobot
{
    private static $robotArray = [
        'SEC849e5d09a38a9acab9f5bf863bcd13be9b75e91c5e5011d8c02ea6e57a0e43fc' => 'https://oapi.dingtalk.com/robot/send?access_token=bd0c37f76092835abebf82bc8c2ce3f032dd03b53e8c4949777022ec4797fc1e',
    ];

    /**
     * @param string $robotKey
     * @return string
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    private static function encryption(string $robotKey): string
    {
        if (!$robotKey) {
            throw new \Exception('没有机器人');
        }
        list($s1, $s2) = explode(' ', microtime());
        $timestamp = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        $secret    = $robotKey;

        $data = $timestamp . "\n" . $secret;

        $signStr = base64_encode(hash_hmac('sha256', $data, $secret, true));

        $signStr = utf8_encode(urlencode($signStr));

        $webhook = self::$robotArray[$robotKey];
        $webhook .= "&timestamp=$timestamp&sign=$signStr";

        return $webhook;
    }


    /**
     * @param string $hook
     * @param string $post_string
     * @return mix
     */
    private static function request_by_curl(string $hook, string $post_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $hook);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * @return string
     * @throws \Exception
     *
     */
    private static function getAWebhook()
    {
        return self::encryption(array_rand(self::$robotArray));
    }

    /**
     * 发送普通文字消息
     * 可以@个人或所有人个人为手机号
     * @param string $msg
     * @param array $at
     * @param bool|null $atAll
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendTextMsg(string $msg, array $at = [], bool $atAll = null)
    {
        $sendData = [
            'msgtype' => 'text',
            'text'    => [
                'content' => $msg
            ],
            'at'      => [
                'atMobiles' => $at,
                'isAtAll'   => $atAll
            ]
        ];
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }

    /**
     * 发送图文link消息
     * @param $title
     * @param $text
     * @param $messageUrl
     * @param string $picUrl
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendLinkMsg($title, $text, $messageUrl, $picUrl = '')
    {
        $sendData = [
            'msgtype' => 'link',
            'link'    => [
                'text'       => $text,
                'title'      => $title,
                'picUrl'     => $picUrl,
                'messageUrl' => $messageUrl
            ]
        ];
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }

    /**
     * 发送markdown消息
     * @param string $title
     * @param string $msg
     * @param array $at
     * @param bool $atAll
     * @throws \Exception
     *
     * 官方支持markdown内容
     * 标题
     * # 一级标题
     * ## 二级标题
     * ### 三级标题
     * #### 四级标题
     * ##### 五级标题
     * ###### 六级标题
     *
     * 引用
     * > A man who stands for nothing will fall for anything.
     *
     * 文字加粗、斜体
     **bold**
     *italic*
     *
     * 链接
     * [this is a link](http://name.com)
     *
     * 图片
     * ![](http://name.com/pic.jpg)
     *
     * 无序列表
     * - item1
     * - item2
     *
     * 有序列表
     * 1. item1
     * 2. item2
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendMarkDown(string $title, string $msg, array $at = [], bool $atAll = false)
    {
        $sendData = [
            'msgtype'  => 'markdown',
            'markdown' => [
                'title' => $title,
                'text'  => $msg
            ],
            'at'       => [
                'atMobiles' => $at,
                'isAtAll'   => $atAll
            ]
        ];
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }

    /**
     * 发送整体跳转actioncard
     * @param string $title
     * @param string $text markdown内容
     * @param string $clickTitle
     * @param string $url
     * @param bool $btn
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendEntiretyActionCard(string $title, string $text, string $clickTitle, string $url, bool $btn = false)
    {
        $sendData = [
            'msgtype'    => 'actionCard',
            'actionCard' => [
                'title'          => $title,
                'text'           => $text,
                'btnOrientation' => $btn,
                'singleTitle'    => $clickTitle,
                'singleURL'      => $url
            ]
        ];
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }

    /**
     * 发送独立按钮类跳转actioncard
     * @param string $title
     * @param string $text
     * @param array $btnsArray $key 按钮名=>$value 跳转链接
     * @param bool $btn true:竖向按钮 false:横向按钮,只支持两个,多于两个自动转为竖向
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendAloneActionCard(string $title, string $text, array $btnsArray, bool $btn = false)
    {
        $sendData = [
            'msgtype'    => 'actionCard',
            'actionCard' => [
                'title'          => $title,
                'text'           => $text,
                'btnOrientation' => $btn ? 1 : 0,
                'btns'           => []
            ]
        ];
        foreach ($btnsArray as $k => $v) {
            $sendData['actionCard']['btns'][] = [
                'title'     => $k,
                'actionURL' => $v
            ];
        }
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }

    /**
     * 发送图文链接消息
     * @param array $linkArray title 显示标题 messageURL 点击跳转地址 picURL 图文显示图片
     * @throws \Exception
     *
     * @author lss <261015906@qq.com>
     * @date
     */
    public static function sendFeedCard(array $linkArray)
    {
        $sendData = [
            'msgtype'  => 'feedCard',
            'feedCard' => [
                'links' => []
            ]
        ];
        foreach ($linkArray as $v) {
            $sendData['feedCard']['links'][] = [
                'title'      => $v['title'] ?? '',
                'messageURL' => $v['messageURL'] ?? '',
                'picURL'     => $v['picURL'] ?? ''
            ];
        }
        self::request_by_curl(self::getAWebhook(), json_encode($sendData));
    }
}
