<?php
/**
 * 极验行为式验证安全平台，php 网站主后台包含的库文件
 *@author Tanxu
 */
class GeetestLib{
    const GT_SDK_VERSION  = 'php_2.15.7.6.1';
    const PRIVATE_KEY = '684f0f9611d2ee64f9351fdd45536d45'; 
 
    public function validate($challenge, $validate, $seccode) {
        if ( ! $this->check_validate($challenge, $validate)) {
            return FALSE;
        }
        $data = array(
            "seccode"=>$seccode,
            "sdk"=>self::GT_SDK_VERSION,
        );
        $url = "http://api.geetest.com/validate.php";
        $codevalidate = $this->post_request($url, $data);
        if (strlen($codevalidate) > 0 && $codevalidate == md5($seccode)) {
            return TRUE;
        } else if ($codevalidate == "false"){
            return FALSE;
        } else {
            return $codevalidate;
        }
    }
    private function check_validate($challenge, $validate) {
        if (strlen($validate) != 32) {
            return FALSE;
        }
        if (md5(self::PRIVATE_KEY.'geetest'.$challenge) != $validate) {
            return FALSE;
        }
        return TRUE;
    }


    public function post_request($url, $postdata = null){
            $data = http_build_query($postdata);
            if(function_exists('curl_exec')){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                if(!$postdata){
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                }else{
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                $data = curl_exec($ch);
                curl_close($ch);
            }else{
                if($postdata){
                    $url = $url.'?'.$data;
                $opts = array(
                    'http' => array(
                                'method' => 'POST',
                                'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
                                'content' => $data
                                )
                    );
                $context = stream_context_create($opts);
                    $data = file_get_contents($url, false, $context);
                }
            }
        return $data;
    }
}
?>