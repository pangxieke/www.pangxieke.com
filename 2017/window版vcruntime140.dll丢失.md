---
title: window版vcruntime140.dll丢失
id: 961
categories:
  - linux
date: 2016-04-10 22:23:59
tags:
---

前几天在公司的电脑新电脑上安装wamp最新版（php5.6和7），启动时，提示丢失vcruntime140.dll文件。

公司电脑window10，电脑是新的，刚新安装的系统。百度了很久，有说需要下载这个文件，然后在注册表注册。尝试下载，然后copy到对应目录，但是不管用。

后来才找到关键原因。原来需要安装微软的vc++2015版本才行

下载地址在下方：

VC14需要

http://www.microsoft.com/en-us/download/details.aspx?id=46881

VC11需要

http://www.microsoft.com/en-us/download/details.aspx?id=30679

VC9需要

64bit: http://www.microsoft.com/en-us/download/details.aspx?id=15336

32bit: http://www.microsoft.com/en-us/download/details.aspx?id=5582