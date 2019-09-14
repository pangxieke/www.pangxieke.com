---
title: Mac使用brew安装mongo失败,改用pctl安装
date: 2018.10.30 19:30
category: linux
id: pecl-install-mongo-extension-on-mac
---

使用Laravel中，提示`Class 'MongoDB\Driver\Manager' not found`,显示需要安装扩展。
查询Mac 上安装`mongodb`教程，都是按照下面命令安装：
```
brew tap homebrew/php

#brew install phpxx-mongodb
brew install php72-mongodb
```
但都提示安装失败。

## Brew安装已取消
后查看[官网文档](http://php.net/manual/en/mongodb.installation.homebrew.php),了解到这种方式已经取消。

	Homebrew 1.5.0 deprecated the » Homebrew/php tap and removed formulae for PHP extensions. 
    Going forward, macOS users should install the driver with PECL. 
    Community forks of the » Homebrew/php tap may still contain formale for installing the driver on various PHP versions.

	The mongodb install has been removed from Homebrew. To install the mongodb extension you need to use pecl.

## 使用Pecl安装
需使用
```
sudo pecl install mongodb
```

但提示错误信息`No releases available for package "pecl.php.net/mongodb".install failed`

解决办法
- download the extension from http://pecl.php.net/packages.php
- there you get an .tgz file
- install the file

命令如下：
```
curl 'http://pecl.php.net/get/mongodb-1.5.3.tgz' -o mongodb-1.5.3.tgz

pecl install mongodb-1.5.3.tgz
```

成功提示

    Build process completed successfully
    Installing '/usr/local/Cellar/php/7.2.10/pecl/20170718/mongodb.so'
    install ok: channel://pecl.php.net/mongodb-1.5.3
    Extension mongodb enabled in php.ini

查看
```
phpinfo()
```
![phpinfo](/images/2018/10/1540881516290.jpg)