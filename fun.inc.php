
<?php

function getContents($fileName){
    $filePath = __DIR__ . "/files/" . $fileName . ".dat";
    $file = fopen($filePath, "r, ccs=UTF-8");
    $content = fread($file, filesize($filePath));
    $content = explode(PHP_EOL, $content);

    foreach ($content as $value) {

        if (in_array($value, LEVEL)) {
            $tmp = $value;
            $contents[$tmp] = [];
            continue;
        }else {
            $contents[$tmp][] = $value;
        }
    }

    if ($file) {
        fclose($filePath);
    }

    return $contents;
}

function getScore(){
    foreach (SUBJECT as $k => $v) {
		$contents[$k] = getContents($k);
	}
    foreach ($contents as $subId => $subInfo) {
        foreach ($subInfo as $level => $schools) {
            foreach ($schools as $key => $school) {
                $schoolScores[$school] = $schoolScores[$school] + SCORE[$level];
            }
        }
    }
    arsort($schoolScores);

    return $schoolScores;
}

function getSort($schoolScores, $schoolName){

    $scoreSort = array_keys(array_count_values($schoolScores));
    $schoolSort = array_search($schoolScores[$schoolName], $scoreSort); //学校积分名次

    return round( (1 - $schoolSort/count($scoreSort) )*100,1)."%";
}

function getWeixinConfig(){
    $getUrl = "http://zcbox.lystrong.cn?getAccessInfo=yes";
    $acInfo = json_decode(file_get_contents($getUrl), true);
    $jsapiTicket = getJsApiTicket($acInfo['actn']);
    $noncestr = getNonceStr();
    $timestamp = time();
    $protocol = (!empty($_SERVER[HTTPS]) && $_SERVER[HTTPS] !== off || $_SERVER[SERVER_PORT] == 443) ? "https://" : "http://";
    $url = $protocol.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
    // $url = "http://www.lystong.cn";
    $data = [
        'noncestr' => $noncestr,
        'jsapi_ticket' => $jsapiTicket,
        'timestamp' => $timestamp,
        'url' => $url,
    ];
    $sign = GetSign($data);
    $weixinConfig = [
        'appId' => APPID,
        'timestamp' => $timestamp,
        'nonceStr' => $noncestr,
        'signature' => $sign,
    ];

    return $weixinConfig;
}

function getJsApiTicket($accessToken){
    $ticketInfo = unserialize(C::t('common_setting')->fetch('jsapiTicket'))['svalue'];

    if (time() - $ticketInfo['timestamp'] >= $ticketInfo['expires_in']) {
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $accessToken ."&type=jsapi";
        $result = json_decode(file_get_contents($url), true);
        $result['timestamp'] = time();
        C::t('common_setting')->update('jsapiTicket', ['svalue'=>$result]);
        return $result['ticket'];
    }
    return $ticketInfo['ticket'];
}

function GetSign($data)
{
    $sign = MakeSign($data);
    return $sign;
}

/**
 * 格式化参数格式化成url参数
 */
function ToUrlParams($value)
{
    $buff = "";
    foreach ($value as $k => $v)
    {
        if($k != "sign" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }
    $buff = trim($buff, "&");
    return $buff;
}

/**
 * 生成签名
 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
 */
function MakeSign($value)
{
    //签名步骤一：按字典序排序参数
    ksort($value);
    $string = ToUrlParams($value);
    //签名步骤二：MD5加密
    // var_dump($string);
    $string = sha1($string);

    return $string;
}

/**
 *
 * 产生随机字符串，不长于32位
 * @param int $length
 * @return 产生的随机字符串
 */
function getNonceStr($length = 32)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}
