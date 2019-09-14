---
title: PHP获取IP地址，及地址的地理位置
tags:
  - ip地址
id: 278
categories:
  - php
date: 2014-09-10 20:28:57
---

获取用户ip地址，获取IP地址所在的地理位置

```php
<?php
/**
 *  获取ip地址
 */
function getIPAddress() {
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }
    return $ip;
}
   
 
  /* 
 *根据腾讯IP分享计划的地址获取IP所在地，比较精确 
 */
function getIPLoc_QQ($queryIP){ 
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;
     
    $ch = curl_init($url); 
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312'); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
    $result = curl_exec($ch);
    curl_close($ch); 
     
    //腾讯IP分享计划这个网站用的是gb2312编码,需要转换编码    
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码 
     
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray); 
    $loc = $ipArray[1]; 
    return $loc; 
} 
 
 
/* 
 *根据新浪IP查询接口获取IP所在地 
 */
function getIPLoc_sina($queryIP){ 
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP; 
    $ch = curl_init($url); 
    //curl_setopt($ch,CURLOPT_ENCODING ,'utf8'); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
    $location = curl_exec($ch); 
    $location = json_decode($location); 
    curl_close($ch); 
      
    $loc = ""; 
    if($location===FALSE) return ""; 
    if (empty($location->desc)) { 
        $loc = $location->province.$location->city.$location->district.$location->isp; 
    }else{ 
        $loc = $location->desc; 
    } 
    return $loc; 
}
```
