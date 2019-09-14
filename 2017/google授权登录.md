---
title: google授权登录
tags:
  - google第三方登录
id: 1044
categories:
  - php
date: 2016-07-06 19:49:57
---

[![20160706200503](/images/2016/07/20160706200503.png)](/images/2016/07/20160706200503.png)
最近刚完成了google+ 的授权登录。查找很多，也没有找到中文相关资料，只能啃google英文说明，终于完成了。方便后人，特记录一些要点。

## 1.账号注册

官方文档 
https://developers.google.com/identity/protocols/OAuth2InstalledApp#handlingtheresponse

账号注册
https://developers.google.com/

注册获取获取到client_id，client_secret
填写回调地址redirect_uri，可以使用本地地址，方便开发

## 2.获取跳转链接

可以使用JS方式获取，也可以使用服务器获取。

```php
$clinet = new ModelModuleGoogle(); //ModelModuleGoogle 为自己写的获取url的class，代码见附录
$url = $clinet->getAuthorizeURL();
```

## 3.回调oauth_callback()

```php
$clinet = new ModelModuleGoogle();
$request_args = ['code'=>$_GET['code']];
$userInfo = $clinet->getUserInfo($request_args);
$userInfo['keyid'];//第三方返回的用户ID。unique
```

## 4.用户绑定

查询$userInfo['keyid']是否在user_bind表中绑定过用户。
如果已经绑定，获取uid登录

如果未绑定，引导客户注册或绑定，获取到新注册的uid。
然后uid和$userInfo['keyid']插入user_bind表实现绑定。

数据库sql

```php
CREATE TABLE `user_bind` (
    `uid` INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
    `type` VARCHAR(25) NOT NULL COMMENT '类型（qq,sina,weixin,google）',
    `keyid` VARCHAR(100) NOT NULL,
    `info` TEXT NOT NULL,
    INDEX `uid` (`uid`),
    INDEX `uid_type` (`uid`, `type`),
    INDEX `type_keyid` (`type`, `keyid`)
)
COMMENT='第三方登录绑定'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
```

## 5.注意事项

国内请求google api速度太慢，crul总提示超时，需要使用vpn才能测试。
google提供了demo，不过demo文件太多，有几M，全集成到项目中完全没必要。
demo地址 https://github.com/googlesamples/identity-toolkit-php/archive/master.zip
谷歌使用了JWT规范，详见上一篇文章[JSON Web Token（JWT）简介](http://www.pangxieke.com/share/1042.html)

## 6.附录自己写的class

```php
<?php
/**
 * googel+ 第三方登录
 * 获取url 方法getAuthorizeURL()
 * 获取uid方法 getUserInfo();
 */
class ModelModuleGoogle extends Model {
    protected $setting = [
        'app_key' => 'app.apps.googleusercontent.com',  //ID
        'app_secret' => 'U78HVJ1ZUWtnRuTHitz8174R',     //secret
        'redirect_uri' => 'http://www.pangxieke.com/oauth_callback',   //回调地址
    ];
 
    public function __construct()
    {
        $this->client_id = $this->setting['app_key'];
        $this->client_secret = $this->setting['app_secret'];
        $this->redirect_uri = $this->setting['redirect_uri'];
 
    }
 
    /**
     * 获取登录跳转链接
     * @return string
     */
    public function getAuthorizeURL()
    {
        $params = array(
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
            'scope' => 'email profile',
            'state' => 'google',
        );
        $host = 'https://accounts.google.com/o/oauth2/v2/auth?';
        return $host. http_build_query($params, '', '&');
    }
 
    /**
     * @param $request_args
     * @return array|bool
     */
    public function getUserInfo(array $request_args)
    {
        $info = $this->getAccessToken($request_args);
        $data = [];
        if($info && is_array($info)){
            $data['keyid'] = $info['sub'];
            $data['type'] = 'google';
            $data['info'] = serialize($info);
        }
        return $data;
    }
 
    /**
     * 通过code 获取 uid等信息
     * @param $request_args 需要包含$request_args['code']
     * @return array
     */
    public function getAccessToken($request_args)
    {
        $postData = [
            'code' => $request_args['code'],
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ];
 
        $url = 'https://www.googleapis.com/oauth2/v4/token';
 
        $fields = (is_array($postData)) ? http_build_query($postData) : $postData;
        $curlHeaders = [
            'content-type: application/x-www-form-urlencoded;CHARSET=utf-8',
            'Content-Length: ' . strlen($fields),
        ];
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
        $response = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
 
        $data = [];
        if($response && $responseCode == 200){
            //JWT 获取数据
            $json = json_decode($response, true);
            $arr = explode('.', $json['id_token']);
            $data = json_decode(base64_decode($arr[1]),true);
        }
 
        return $data;
    }
}
```