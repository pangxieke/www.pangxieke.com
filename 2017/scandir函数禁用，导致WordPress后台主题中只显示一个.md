---
title: scandir函数禁用，导致WordPress后台主题中只显示一个
id: 1256
categories:
  - linux
date: 2017-04-19 18:54:39
tags:
---

今天在服务器上安装WordPress,发现在WordPress后台主题中只显示一个主题不显示其他主题，代码在本地运行时就一切正常，能够显示主题列表

### 异常如下

[![20170419160328](/images/2017/04/20170419160328.png)](/images/2017/04/20170419160328.png)

### 正常状况

本地是正常的，正常效果如下。

[![20170419160351](/images/2017/04/20170419160351.png)](/images/2017/04/20170419160351.png)

## 原因

出现这种WordPress主题无法识别问题的原因：服务器环境禁用了 scandir函数，导致WordPress无法正常缓存主题。

查看php.ini，果然，在“disable_funcions”后，有“scandir”。

[![20170419160446](/images/2017/04/20170419160446.png)](/images/2017/04/20170419160446.png)
删除禁用后，重启php服务，一切就回复正常了。

## 原理

查询php手册，可以了解到，scandir函数 — 列出指定路径中的文件和目录

```php
//返回一个 array，包含有 directory 中的文件和目录。 
array scandir ( string $directory [, int $sorting_order [, resource $context ]] )
```