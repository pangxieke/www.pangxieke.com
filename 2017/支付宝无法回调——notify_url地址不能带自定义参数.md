---
title: 支付宝无法回调——notify_url地址不能带自定义参数
tags:
  - 支付宝
id: 764
categories:
  - php
date: 2015-08-18 22:46:19
---

## 问题
今天测试支付宝支付功能，回调地址一直不能成功接收到支付宝的信息。开始已经是端口号有影响。因为线上的测试服务器使用的非80端口。
如`http://www.pangxieke.com:86/index.php?g=api&m=alipay&a=notify`

## 排查
先联系支付宝的在线客户。提供交易号，帮忙查询到支付宝有访问回调地址，但回调地址返回的是302(重定向。但我本地模拟，直接访问这个地址是能够正常访问的。

继续排查测试了很久，分几步测试。

先去掉端口号，是能够接受到回调

在86项目的根目录，建立一个notify.php,回调地址设为http://www.pangxieke.com:86/notify.php 也是能够接受到回调，即正常的

后多方查询，才了解到，**otify_url地址不能带有自定义参数**。

## 解决方法
使用了一个伪静态页面，http://www.pangxieke.com:86/api-alipay-notify.html 成功解决问题。

为什么notify_url地址不能带有自定义参数？在指定notify_url时，合法的方式如
`http://www.solagirl.net/notify_url.php`
不合法的方式如
`http://www.solagirl.net/notify_url.php?order_id=10`

与其说第二种格式不合法，不如说它是无效的。为什么这样说？因为不论你在这个地址后面带上多少自定义的参数，支付宝通过异步方式向你POST数据时，都会把`?`后面的参数去掉，所以即使传递给支付宝，也没有任何作用，更不可能影响签名判断。这点似乎不如paypal方便，要区分执行过程只能靠POST过来的数据。

因此支付宝的notify_url是不可以带有自定义参数的。