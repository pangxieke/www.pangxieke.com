---
title: Linux下php安装Memcache扩展
id: 948
categories:
  - linux
date: 2016-03-30 18:15:02
tags:
---

## 1、安装memcache

```php
wget http://pecl.php.net/get/memcache-3.0.8.tgz
 
tar -vxzf memcache-3.0.8.tgz  #解压
 
cd memcache-3.0.8 #进入安装目录
 
/usr/bin/phpize #用phpize生成configure配置文件，可以使用which phpize查看路径
 
#配置 笔者的php-config目录为/usr/bin/php-config，可以使用php-config查看路径
./configure --enable-memcache --with-php-config=/usr/bin/php-config --with-zlib-dir  
  
make  #编译
 
make install  #安装
```

安装完成之后，出现下面的安装路径

```php
/usr/lib64/php/modules/
ls /usr/lib64/php/modules/ #检查是否成功生成了.so文件
```

## 2、配置php支持

```php
vi /etc/php.ini  #编辑配置文件，在最后一行添加以下内容
 
#添加
extension="memcache.so"
```

:wq! #保存退出

## 3 重启服务

```php
sudo /etc/init.d/php-fpm restart
sudo service nginx restart
```

## 4.使用phpinfo查看。

记得重启php和nginx。

## 5.其它

memcache地址[http://pecl.php.net/package/memcache](http://pecl.php.net/package/memcache)