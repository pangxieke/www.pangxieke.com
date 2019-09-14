---
title: centos下php升级php7
id: 1151
categories:
  - linux
date: 2016-12-07 10:27:48
tags:
---

[![php7-wordpress](/images/2016/12/php7-wordpress.png)](/images/2016/12/php7-wordpress.png)

WordPress项目中，PHP7对比PHP5.6，QPS提升2.77倍

php7已经出了一年多了，本地window版本已经体验过很久了。但是服务器上还是使用的php4.5版本。最近用了点时间，将线上生产环境其升级为php7了。

## 卸载PHP安装包

```php
yum list installed | grep php #检查当前安装的PHP包

yum remove php* php-common #卸载历史安装包
```
[![20161207100415](/images/2016/12/20161207100415.png)](/images/2016/12/20161207100415.png)

## 安装源

```php
    #Centos 5.X
rpm -Uvh http://mirror.webtatic.com/yum/el5/latest.rpm
    #CentOs 6.x
rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm
    #CentOs 7.X
rpm -Uvh https://mirror.webtatic.com/yum/el7/epel-release.rpm
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

```
如果想删除上面安装的包，重新安装
```php
rpm -qa | grep webstatic
```

## 安装PHP7

```php
yum install php70w.x86_64 php70w-cli.x86_64 php70w-common.x86_64 php70w-gd.x86_64 php70w-ldap.x86_64 php70w-mbstring.x86_64 php70w-mcrypt.x86_64 php70w-mysql.x86_64 php70w-pdo.x86_64

yum install php70w-devel
```

安装PHP FPM
```php
yum install php70w-fpm

service php-fpm start #启动php服务

chkconfig php-fpm on #加入自启动
```

## php7 package汇总

```php
php70w
php70w-bcmath	 
php70w-cli	
php70w-common	
php70w-dba	 
php70w-devel	 
php70w-embedded	
php70w-enchant	 
php70w-fpm	 
php70w-gd	 
php70w-imap	 
php70w-interbase	
php70w-intl	 
php70w-ldap	 
php70w-mbstring	 
php70w-mcrypt	 
php70w-mysql	
php70w-mysqlnd	
php70w-odbc	
php70w-opcache	
php70w-pdo	 
php70w-pdo_dblib
php70w-pear	 
php70w-pecl-apcu	 
php70w-pecl-imagick	 
php70w-pecl-xdebug	 
php70w-pgsql	
php70w-phpdbg	 
php70w-process	
php70w-pspell	 
php70w-recode	 
php70w-snmp	 
php70w-soap	 
php70w-tidy	 
php70w-xml	
php70w-xmlrpc	 
```

## 查看

网站访问正常，查看版本，已经显示为php7
[![20161207102058](/images/2016/12/20161207102058.png)](/images/2016/12/20161207102058.png)

参考文章：[https://webtatic.com/packages/php70/](https://webtatic.com/packages/php70/)