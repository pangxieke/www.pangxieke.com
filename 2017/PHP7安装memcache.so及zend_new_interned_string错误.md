---
title: PHP7安装memcache.so及zend_new_interned_string错误
id: 1161
categories:
  - linux
date: 2016-12-08 19:36:14
tags:
---

[![memcache](/images/2016/12/memcache.png)](/images/2016/12/memcache.png)

PHP7最显著的变化就是性能的极大提升，Memcache是高性能、分配的内存对象缓存系统，可以加速动态web应用程序，减轻数据库负载。

## undefined symbol: zend_new_interned_string 错误

以前项目使用过memcache，最近php环境有5.4升级到php7。运行项目时出错，提示错误信息如下：
```php
Class 'Memcache' not found
```
原因：需要memcache扩展。

于是编译安装扩展。phpinfo检查memcached扩展已经安装。但访问项目，仍然报错。

原因：memcached扩展和memcache扩展不同，php有2个扩展，一个字母的差别。我安装的是memcached.so。memcache是旧扩展，memcached是基于原生的c的libmemcached的扩展，更加完善

于是重新php7编译安装memcache，启动时提示：
```php
NOTICE: PHP message: PHP Warning:  PHP Startup: Unable to load dynamic library '/usr/lib64/php/modules/memcache.so' 
- /usr/lib64/php/modules/memcache.so: undefined symbol: zend_new_interned_string in Unknown on line 0
```
网上找很多原因：有人说原因是命令行PHP是32位的，所以它不能加载64位扩展。

原因：我编译的是Memcache 3.08，原生的Memcache 3.08版无法在PHP7下编译，故选用Github的pecl-memcache分支版本

## 编译PHP7适应的memcache扩展

```php
cd /home

# 获取源码
git clone https://github.com/websupport-sk/pecl-memcache.git

cd pecl-memcache/
phpize
./configure  --with-php-config=/usr/bin/php-config  # php-config 路径

make &amp;&amp; make install

php -m #查看扩展

service php-fpm restart #重启

```

## 更多PHP7扩展

已有人汇总了php7需要的扩展extensions
[php7扩展：https://github.com/gophp7/gophp7-ext/wiki/extensions-catalog](https://github.com/gophp7/gophp7-ext/wiki/extensions-catalog)
[pecl-memcache源码](https://github.com/websupport-sk/pecl-memcache/)