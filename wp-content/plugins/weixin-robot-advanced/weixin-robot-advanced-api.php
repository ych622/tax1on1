<?php
/*
define ( "TOKEN", "weixin" );
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];        //随机字符串
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
	private function checkSignature() {
      $signature = $_GET ["signature"];
      $timestamp = $_GET ["timestamp"];
      $nonce = $_GET ["nonce"];
      $token = TOKEN;
      $tmpArr = array (
            $token,
            $timestamp,
            $nonce
      );
      sort ( $tmpArr );
      $tmpStr = implode ( $tmpArr );
      $tmpStr = sha1 ( $tmpStr );
      
      if ($tmpStr == $signature) {
        return true;
      } else {
        return false;
      }
    }   
}

*/

include('../../../wp-config.php');

//file_put_contents(WP_CONTENT_DIR.'/uploads/weixin.log',var_export($_SERVER,true));
$wechatObj = new wechatCallback();
$wechatObj->valid();
exit;
