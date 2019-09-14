---
title: 使用time()时出现警告错误
id: 495
categories:
  - php
date: 2015-03-02 17:29:47
tags:
---

<span style="text-indent: 2em; text-align: justify;">最近使用了阿里云主机，自己安装了PHP环境，最近使用time()函数时，出现一个警告错误</span>

```php
Warning: date(): It is not safe to rely on the system's timezone settings. 
You are *required* to use the date.timezone setting or the 
date_default_timezone_set() function. 
In case you used any of those methods and you are still getting this warning, 
you most likely misspelled the timezone identifier.
 We selected the timezone 'UTC' for now, but please set date.timezone to select 
your timezone. in /var/www/swoole/client.php on line 16
```

原理：

从 PHP 5.1.0 引用了时区设置(date.timezone)，但其默认又是关闭的，所以使用date()等函数时，都会产生E_NOTICE 或者 E_WARNING 信息。

解决方案：

1.  <span style="text-indent: 2em;">在页头加入代码：ate_default_timezone_set("PRC");</span>
2.  <span style="text-indent: 2em;">在页头加入代码：ini_set('date.timezone','注释：RPC');</span>
3.  <span style="text-indent: 2em;">在php.ini中启用date.timezone设置并设置其值：date.timezone=PRC，并重启apache;</span>
注释：RPC代表中华人民共和国。