---
title: 支付宝秘钥配置（php）
tags:
  - 支付宝秘钥
id: 835
categories:
  - php
date: 2015-09-23 22:12:35
---

## 一、支付宝证书设置

使用openssl工具生成商户私钥和商户公钥，支付宝demo中有该工具

上传商户公钥到支付宝官方

```php
RSA密钥生成命令
生成RSA私钥
openssl&gt;genrsa -out rsa_private_key.pem 1024
生成RSA公钥
openssl&gt;rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
将RSA私钥转换成PKCS8格式 -- php不需要此步，不需要转换
openssl&gt;pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt
```

## 二、商户公钥上传

登录支付宝官网网址。在RSA加密处，上传商户公钥（需去掉头尾注释“-----BEGIN PUBLIC KEY-----”、“-----END PUBLIC KEY-----”）

## 三、在项目中集成

◆商户的私钥（生成）
1、必须保证只有一行文字，即，没有回车、换行、空格等
2、不需要对刚生成的（原始的）私钥做pkcs8编码
3、不需要去掉“-----BEGIN PUBLIC KEY-----”、“-----END PUBLIC KEY-----”
简言之，只要维持刚生成出来的私钥的内容即可。

◆支付宝公钥（demo中）
1、必须保证只有一行文字，即，没有回车、换行、空格等
2、须保留“-----BEGIN PUBLIC KEY-----”、“-----END PUBLIC KEY-----”这两条文字。
简言之，支付宝公钥只需要维持原样即可。

◆CA证书，配置文件中cacert项CA证书 cacert.pem ，使用支付宝demo中的

## 四、错误经历

今天集成支付宝配置，已经已经调通过支付宝。今天只是更换了新的账号。能够正常支付。支付宝有回调通知，但是回调验证一直失败。尝试多种办法终于找到原因。

项目中错将公钥使用为商户公钥。而实际应该使用支付宝公钥。

代码使用商户私钥，及支付宝公钥。商户公钥在支付宝官网上传给支付宝，代码中不会使用。

代码中使用的公钥是支付宝的公钥,可以使用支付宝demo中的公钥。