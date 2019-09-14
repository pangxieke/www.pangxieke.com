---
title: 畅卓短信接口demo
tags:
  - 短信
id: 865
categories:
  - php
date: 2015-11-02 21:04:00
---

短信供应商，使用畅卓短信接口。官方demo写的太简单，不是使用对象写成。自己将其改成对象形式。
```
<?php
//畅卓短信接口
class czsmsclient{
    private $userid = '***';        //用户ID
    private $account = '***';       //账号
    private $password = '***';      //密码
    private $sign = '【螃蟹壳】';    //公司签名
    private $host = "http://sms.chanzor.com:8001/sms.aspx";
     
     
    /**
     * demo发送方法
     * @param type $data
     * @param type $target
     * @return type
     */
    function post($data, $target) {
        $url_info = parse_url($target);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $data;
         
        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        return $gets;
    }
     
     
    /**
     * 获取账号余额
     */
    public function getAmount(){
        $target = $this->host;
        $post_data = "action=overage&userid={$this->userid}&account={$this->account}"
        . "&password={$this->password}&mobile={$mobile}&sendTime={$time}"
        . "&content=".rawurlencode($content);
        $gets = $this->post($post_data, $target);
        $start=strpos($gets,"<?xml");
        $data=substr($gets,$start);
        $xml=simplexml_load_string($data);
        $return = (json_decode(json_encode($xml),TRUE));
        return $return;
    }
     
     
    /**
     * 余额报警
     */
    public function amountWarning(){
        $return = $this->getAmount();
        if($return['overage'] <= 100){
            $user_email = '***@pangxieke.com';
            $mail_body = date('Y-m-d:H:i:s') . '手机验证码余额不足' 
                    . '现在账号余额条数' . $return['overage']
                    . '请尽快向手机验证项目账号充值'; 
             
            //发送
            $mailer = mailer::get_instance();
            $mailer->send($user_email, '手机验证码余额不足', $mail_body);
        }
    }
     
     
    public function send($mobile, $content, $time = ''){
//      $this->amountWarning();  //余额报警
        $target = $this->host;
        //替换成自己的测试账号,参数顺序和wenservice对应
         
        $content = $this->content($content);
         
//      header('content-type:text/html;charset=utf-8');
        $post_data = "action=send&userid={$this->userid}&account={$this->account}"
        . "&password={$this->password}&mobile={$mobile}&sendTime={$time}"
        . "&content=".rawurlencode($content);
        //$binarydata = pack("A", $post_data);
        $gets = $this->post($post_data, $target);
        $start=strpos($gets,"<?xml");
        $data=substr($gets,$start);
        $xml=simplexml_load_string($data);
        $return = (json_decode(json_encode($xml),TRUE));
         
        if($return['returnstatus'] == 'Success'){
            return true;
            //成功
//          'returnstatus' => string 'Success' (length=7)
//          'message' => string '操作成功' (length=12)
//          'remainpoint' => string '19' (length=2)
//          'taskID' => string '1511021506234479' (length=16)
//          'successCounts' => string '1' (length=1)
         
        }else{
            return false;
        }
    }
     
     
    /**
     * 发送注册短信 模板必须要与在第三方后台设置的统一
     * @param type $mobile 号码
     * @param string $code 验证码
     * @return bool
     */
    public function register_send($mobile, $code){
        $content = '您的验证码是：'. $code;
        return $this->send($mobile, $content);
    }
     
     
    /**
     * 发送内容添加签名
     */
    public function content($content){
        return $content . $this->sign;
    }
     
     
}
```