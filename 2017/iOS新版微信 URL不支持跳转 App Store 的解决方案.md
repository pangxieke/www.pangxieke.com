---
title: iOS新版微信 URL不支持跳转 App Store 的解决方案
id: 408
categories:
  - php
date: 2015-01-26 22:57:30
tags: 微信
---

今天接到公司反馈：手机端页面的下载按钮在iOS微信内置浏览器里面点击无效。 
经过测试，在IOS设备上，微信内置浏览器无效，用外部浏览器能够正常使用，用Safari浏览器也能够正常下载。 
在安卓设备上，能够正常使用，下载链接跳转到腾讯的“应用宝”上。 

IOS的微信，未升级前，能够正常下载，是跳转到app store下载。 
百度之后，确认问题出在了微信上，大概腾讯有做限制。 

原因：最新版微信在所有开放的 webview（网页界面）里禁止了通过链接打开本地 app 或跳转到 app store，只有自家使用的 webview 能够打开 app 或跳转 app store。 
而且这种做法不像是 bug 所致，而是刻意为之。 

用意：微信是一个重要的互联网入口和应用入口，但是微信为了自家利益，需要控制入口和流量，进而加强对公共帐号和第三方应用的控制，打击竞争对手 

解决办法：微信内置浏览器右上角的跳转按钮“在 Safari 中打开”可以间接的跳转 App Store ， 
所以最终我们的解决方案是如果是 iOS 的微信内置浏览器，点击按钮后，用弹出提示的方法来取代直接跳转。 

可参考http://dearb.me/archive/2013-11-07/ios7-weixin-unsupport-redirect-to-app-store/ 
```html
<!DOCTYPE html> 
<html> 
<head> 
</head> 
<body> 
<div class="wrap"> 
</div> 
<a class="dlTip" href="{$apkurl}" target="_blank"> 
<div class="d-btn">立即下载</div> 
<div class="d-close" onclick="$('.dlTip').css('display','none');return false;"></div> 
</div> 
</a> 
</body> 
<script> 
//点击下载按键时，检测客户端和浏览器，如是ios设备使用微信默认浏览器，弹出图片提示 
$(".d-btn").click(function(){ 
var ua = navigator.userAgent.toLowerCase(); 
if ( /iphone|ipod/.test(ua)) { 
if(/micromessenger/.test(ua)){  //检测微信内置浏览器 另外一种写法navigator.userAgent.toLowerCase().indexOf('micromessenger')>-1 
wx_guide_index('$url'); //$url 为图片地址 
} 
} 
}); 
 
// 弹出图片，再次点击图片时隐藏图片 
var wx_guide_index=function(src){ 
var wrap=$('.wrap')[0]; 
var img=document.createElement('img'); 
var scrollTop=0; 
 
img.src=src; 
img.setAttribute('style','position:absolute;z-index:999999;top:0px;left:0px;width:'+document.documentElement.clientWidth 
+'px;height:'+document.documentElement.clientHeight+'px;'); 
 
//弹出图片 
function popup(e){ 
scrollTop=document.documentElement.scrollTop||document.body.scrollTop; 
wrap.style.height=document.documentElement.clientHeight+'px'; 
wrap.style.overflow='hidden'; 
document.body.appendChild(img); 
AddEvent(img,'click',hide); 
}; 
//隐藏图片 
var hide=function(){ 
window.scrollTo(0,scrollTop); 
wrap.style.height='auto'; 
wrap.style.overflow='auto'; 
document.body.removeChild(img); 
RemoveEvent(img,'click',hide); 
} 
popup(); 
} 
</script> 
```