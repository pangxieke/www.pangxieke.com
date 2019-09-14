---
title: IP地址库
id: 1322
categories:
  - share
date: 2017-09-06 19:06:52
tags:
---

项目中，经常会用到判断用户来源，所使用语言。使用IP判断是常用的方法。那就需要一个准确的IP地址库。这里有2种方法

## 一、ip2region

ip2region是准确率99.9%的ip地址定位库，0.0x毫秒级查询，数据库文件大小只有1.6M。

### 定时更新

99.9%准确率，定时更新：数据聚合了一些知名ip到地名查询提供商的数据，这些是他们官方的的准确率，经测试着实比纯真啥的准确多了。每次聚合一下数据需要1-2天，会不定时更新。

### 标准化的数据格式

每条ip数据段都固定了格式：城市Id|国家|区域|省份|城市|ISP。其中，只有中国的数据精确到了城市，其他国家只能定位到国家，后前的选项全部是0，已经包含了全部你能查到的大大小小的国家。(请忽略前面的城市Id，个人项目需求)

### 客户端

已经集成的客户端有：java, php, c，python，php扩展，nodejs，golang。

### 算法

提供了两种查询算法，响应时间如下：
客户端/binary算法/b-tree算法/Memory算法：
java/0.x毫秒/0.x毫秒/0.1x毫秒 (使用RandomAccessFile)
php/0.x毫秒/0.1x毫秒/0.1x毫秒 (php扩展将有更快的速度)
c/0.0x毫秒/0.0x毫秒/0.00x毫秒(b-tree算法基本稳定在0.02x毫秒级别)
python/0.x毫秒/0.1x毫秒/0.1x毫秒
任何客户端b-tree都比binary算法快

### demo

https://github.com/pangxieke/ip2region.git
其中有集成好的方法，直接使用

## 二、 GeoIp

官网 http://geoip.com/
能够集成安装为PHP的扩展
Nginx也有GeoIp模块
可以参考文章https://sjolzy.cn/GeoIP-PHP-version-use.html

## 三、纯真IP地址库

官网 http://www.cz88.net/
只用一个文件QQWry.dat就包含了所有记录
参考文章http://www.cnblogs.com/kjcy8/p/5787723.html