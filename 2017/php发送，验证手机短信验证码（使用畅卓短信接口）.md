---
title: php发送手机短信验证码
tags:
  - 短信
id: 861
categories:
  - php
date: 2015-11-02 20:59:42
---

现在很多app使用手机号码作为注册号码，需要验证手机验证码，特提供此demo。
此方法使用畅卓短信接口

### DB Table
手机短信验证码数据库。也可以使用session储存，只需要修改模型文件mobile_verify.php。使用session存储更加高效和方便
```
CREATE TABLE `mobile_verify` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `mobile_phone` VARCHAR(15) NOT NULL COMMENT '用户手机号码',
    `code` VARCHAR(50) NOT NULL COMMENT '验证码',
    `add_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '条件时间',
    PRIMARY KEY (`id`),
    INDEX `mobile_phone` (`mobile_phone`),
    INDEX `add_time` (`add_time`)
)
COMMENT='手机验证表'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
```

### 手机短信发送接口
```
$mobile_phone       = 13800138000;//用户手机
//empty($mobile_phone) 验证是否手机号码
 
include('mobile_varify.php');
$mode = new mobile_varify();
$code = $mode->getCode();    //生成随机码
 
$res = $mode->saveVerifyInfo($mobile_phone, $code); //保存验证码到数据库
 
if(empty($res)){
    return false;
}
 
include('czsmsclient.php');
$czsmsclient = new czsmsclient();
$sendCode = $czsmsclient->register_send($mobile_phone, $code); //发送随机码
```

### 验证接口
```php
//验证 随机码
$user_phone = 13800138000;
$auth_code = 1234;  //验证码
include('mobile_varify.php');
$mode = new mobile_varify();
$mobile_verify = $mode->checkCode($user_phone, $auth_code);
if($mobile_verify == false){
    $errorMsg = $mode->getError();
    $this->ajaxReturn(0,$errorMsg);
}
```

### 短信供应商，使用畅卓短信接口。文件名 `czsmsclient.php`
```php
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

### 模型文件`mobile_verify.php`
```php
<?php
/**
 * 手机验证码模型
 * @author tlh
 * @date 2015.11.2
 */
class mobile_verifyModel extends RelationModel {
     
    public $errorMsg;   //错误信息
     
     
    /**
     * 获取手机验证码信息
     * @param string $mobile_phone 电话号码
     * @return array | false
     */
    public function info($mobile_phone){
        $info = $this->where(array('mobile_phone'=>$mobile_phone))->find();
        return $info;
    }
     
     
    /**
     * 获取随机验证码
     * @return int
     */
    public function getCode(){
        return $this->generateCode(4);
    }
     
     
    /**
     * 生成验证码
     * @param int $length 验证码长度
     * @return int
     */
    function generateCode($length = 4) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
     
     
    /**
     * 保存验证码信息
     * @param string $mobile_phone 电话号码
     * @param string $code 验证码
     * @return bool
     */
    public function saveVerifyInfo($mobile_phone, $code){
        $exist = $this->info($mobile_phone);
         
        if(!$exist){
            $data = array();
            $data['mobile_phone'] = $mobile_phone;
            $data['code'] = $code;
            $data['add_time'] = time();
            $res = $this->add($data);
             
        }else{
            $data = array();
            $data['code'] = $code;
            $data['add_time'] = time();
            $res = $this->where(array('mobile_phone'=>$mobile_phone))->save($data);
        }
        return $res;
    }
     
     
    /**
     * 检查验证码
     * @param string $mobile_phone
     * @param int $code
     * @return boolean
     */
    public function checkCode($mobile_phone, $code){
         
        $info = $this->info($mobile_phone);
        if(empty($info)){
            $this->errorMsg = '验证码错误';
            return false;
        }else if($info['code'] != $code){
            $this->errorMsg = '验证码错误';
            return false;
        }else if( $info['add_time'] < time()- 300){
            $this->errorMsg = '验证码已过期';
            return false;
        }else{
            return true;
        }
    }
     
     
    /**
     * 返回错误信息
     * @return string
     */
    public function getError(){
        return $this->errorMsg;
    }
     
}
```