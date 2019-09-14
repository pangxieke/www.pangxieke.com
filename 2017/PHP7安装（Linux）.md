---
title: Linux下安装PHP7
tags:
  - php7
id: 797
categories:
  - linux
date: 2015-09-18 22:04:23
---

Linux环境PHP7.0安装

linux版本：64位CentOS 6.5

php版本：php-7.0.0RC1

### 安装
```php
#安装
wget  https://downloads.php.net/~ab/php-7.0.0RC1.tar.gz
#建议安装之前先看看安装帮助文件INSTALL
 
tar zxvf php-7.0.0RC1.tar.gz
cd php-7.0.0RC1
./configure   --help //查看帮助
./configure --prefix=/usr/local/php \
 --with-curl \
 --with-freetype-dir \
 --with-gd \
 --with-gettext \
 --with-iconv-dir \
 --with-kerberos \
 --with-libdir=lib64 \
 --with-libxml-dir \
 --with-mysqli \
 --with-openssl \
 --with-pcre-regex \
 --with-pdo-mysql \
 --with-pdo-sqlite \
 --with-pear \
 --with-png-dir \
 --with-xmlrpc \
 --with-xsl \
 --with-zlib \
 --enable-fpm \
 --enable-bcmath \
 --enable-libxml \
 --enable-inline-optimization \
 --enable-gd-native-ttf \
 --enable-mbregex \
 --enable-mbstring \
 --enable-opcache \
 --enable-pcntl \
 --enable-shmop \
 --enable-soap \
 --enable-sockets \
 --enable-sysvsem \
 --enable-xml \
 --enable-zip
 ```

*如果配置错误，需要安装需要的模块，直接yum一并安装依赖库*

```php
yum -y install libjpeg libjpeg-devel libpng libpng-devel freetype
freetype-devel libxml2 libxml2-devel mysql pcre-devel
 
#注意：安装php7beta3的时候有几处配置不过去，需要yum一下，现在php-7.0.0RC1已经不用这样了。
yum -y install curl-devel
yum -y install libxslt-devel
 
#如果 configure: error: Cannot find OpenSSL's <evp.h>
su #切换root用户，安装
yum install openssl openssl-devel 
 
#错误：configure: error: Please reinstall the libcurl distribution -easy.h
should be in <curl-dir>/include/curl/
#解决：
yum install curl curl-devel 
 
 
#错误 configure: error: freetype.h not found.
#解决：yum install freetype-devel
 
 
#configure: error: xslt-config not found. Please reinstall the libxslt >= 1.1.0 distribution
yum install libxslt-devel
```

### 编译安装

```php
# 编译安装
make && make install
 
cp php.ini-development /usr/local/php/lib/php.ini
cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf
cp /usr/local/php/etc/php-fpm.d/www.conf.default /usr/local/php/etc/php-fpm.d/www.conf
cp -R ./sapi/fpm/php-fpm /etc/init.d/php-fpm
 
#启动
/etc/init.d/php-fpm
```

### 测试
**php7和php5性能分析比较**

新建文件`search_by_key.php`

```php
<?php
//time /usr/local/php5/bin/php search_by_key.php
$a = array();
    for($i=0;$i<600000;$i++){
        $a[$i] = $i;
        }  
 
    foreach($a as $i)
    {
        array_key_exists($i, $a);
    }
?>
```

time /usr/local/php/bin/php search_by_key.php //php7

### php7环境下测试

[![php7速度](/images/2015/09/php7速度.png)](/images/2015/09/php7速度.png)

```

time /usr/bin/php search_by_key.php //php5.4.38
```
php5.4.38环境下测试

[![5.4速度](/images/2015/09/5.4速度.png)](/images/2015/09/5.4速度.png)


分析

php7速度

real    0m0.013s

user    0m0.010s

sys     0m0.002s

php 5.4.38速度，明显php7速度快很多

real    0m0.054s

user    0m0.016s

sys     0m0.008s

<span style="color: #000000;"> 参考文章 http://blog.csdn.net/21aspnet/article/details/47708763</span>