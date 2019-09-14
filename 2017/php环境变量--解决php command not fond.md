---
title: php环境变量--解决php command not fond
id: 1048
categories:
  - php
date: 2016-07-07 19:17:54
tags:
---

window下使用php命令php -v，提示php command not fond
原因为未配置php环境变量。系统无法知道php.exe文件在哪个目录。需要进入php目录才能使用。这样太麻烦
简单的办法是，配置环境变量

1.环境变量配置
打开我的电脑->属性->高级->环境变量，进入环境变量配置界面

点击 用户变量中的path
在后面加入php环境目录，多个目录用;分隔
例如C:\wamp\bin\php\php5.6.16;C:\wamp\bin\php\php5.6.16\ext
螃蟹的php.exe目录在C:\wamp\bin\php\php5.6.16;

点击下方 系统变量
添加 变量PHPRC，值为C:\wamp\bin\php\php5.6.16
[![20160707190452](/images/2016/07/20160707190452.png)](/images/2016/07/20160707190452.png)
2.测试 
cmd下，php -v
[![20160707190604](/images/2016/07/20160707190604.png)](/images/2016/07/20160707190604.png)