---
title: form-data与x-www-form-urlencoded的区别
id: 1302
categories:
  - linux
date: 2017-08-22 21:03:08
tags:
---

[![](/images/2017/08/postman.png)](/images/2017/08/postman.png)
在postman中 有form-data、x-www-form-urlencoded、raw、binary这几种不同的传值方式

## form-data

是http请求中的multipart/form-data,它会将表单的数据处理为一条消息，以标签为单元，用分隔符分开。既可以上传键值对，也可以上传文件。当上传的字段是文件时，会有Content-Type来表名文件类型；content-disposition，用来说明字段的一些信息；

由于有boundary隔离，所以multipart/form-data既可以上传文件，也可以上传键值对，它采用了键值对的方式，所以可以上传多个文件

## x-www-form-urlencoded

是application/x-www-from-urlencoded,会将表单内的数据转换为键值对，比如,name=Java&amp;age = 23

## raw

可以上传任意格式的文本，可以上传text、json、xml、html等

## binary

相当于Content-Type:application/octet-stream,从字面意思得知，只可以上传二进制数据，通常用来上传文件，由于没有键值，所以，一次只能上传一个文件

## 区别

multipart/form-data：既可以上传文件等二进制数据，也可以上传表单键值对，只是最后会转化为一条信息

x-www-form-urlencoded：只能上传键值对，并且键值对都是间隔分开的。他是默认的MIME内容编码类型，一般可以用于所有的情况。

但是他在传输比较大的二进制或者文本数据时效率极低。这种情况应该使用"multipart/form-data"。如上传文件或者二进制数据和非ASCII数据。

## 示例

这是一个表单，有2个表单域：name和email

在 application/x-www-form-urlencoded 消息中:

```php
name=pangxieke&email=pangxieke@pangxieke.com
```
(不同的field会用"&amp;"符号连接;空格被替换成"+";field和value间用"="联系,等等)

再看multipart/form-data 消息中:

```php
-----------------------------7cd1d6371ec
Content-Disposition: form-data; name="name"

pangxieke
-----------------------------7cd1d6371ec
Content-Disposition: form-data; name="email"

pangxieke@pangxieke.com
-----------------------------7cd1d6371ec
Content-Disposition: form-data; name="Logo"; filename="D:\My Documents\My Pictures\Logo.jpg"
Content-Type: image/jpeg
```
(每个field被分成小部分，而且包含一个value是"form-data"的"Content-Disposition"的头部；一个"name"属性对应field的ID,等等)