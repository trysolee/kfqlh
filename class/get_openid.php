<?php

/**
 *
 */
class openID
{

    public static $OK, $openid;

    public static function get($code)
    {
      
        $appID  = 'wxfa85e735b154f325';
        $secret = '4220c26ad926c84fb4fbaf0e8b8217fb';

        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appID . "&secret=" . $secret . "&js_code=" . $code . "&grant_type=authorization_code";

        $weixin = file_get_contents($url); //

        $array = json_decode($weixin, true);

        if (empty($array['openid'])) {
            openID::$OK = false;
        } else {
            openID::$OK     = true;
            openID::$openid = $array['openid'];
        }

    }
}
