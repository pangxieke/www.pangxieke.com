---
title: Linux下php安装Redis扩展
id: 916
categories:
  - linux
date: 2016-03-08 23:59:07
tags:
---

Linux下php安装Redis扩展

1、安装redis
```php
wget https://github.com/nicolasff/phpredis/archive/2.2.4.tar.gz

tar zxvf 2.2.4.tar.gz #解压

cd phpredis-2.2.4 #进入安装目录

/usr/local/php/bin/phpize #用phpize生成configure配置文件
#笔者的目录为(/usr/bin/phpize)可以使用which phpize查看路径

./configure --with-php-config=/usr/local/php/bin/php-config  #配置
#笔者的目录为(./configure --with-php-config=/usr/bin/php-config )

make  #编译

make install  #安装
```
安装完成之后，出现下面的安装路径

`/usr/local/php/lib/php/extensions/no-debug-non-zts-20090626/`
笔者的目录为`/usr/lib64/php/modules`

2、配置php支持

`vi /usr/local/php/etc/php.ini`  #编辑配置文件，在最后一行添加以下内容
笔者的目录为`/etc/php.ini`

添加
```
extension="redis.so"

:wq! #保存退出
```

3  重启服务
```
sudo /etc/init.d/php-fpm restart
sudo service nginx restart
```

4.使用`phpinfo查看`。记得重启php和nginx。

5.redis的官网http://www.redis.io/
php的redis扩展有很多http://www.redis.io/clients#php,
笔者使用的是phpredis，https://github.com/owlient/phpredis