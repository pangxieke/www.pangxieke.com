---
title: linux安装swoole扩展
id: 474
categories:
  - linux
date: 2015-02-28 15:56:50
tags:
---

1.首先我们要安装swoole扩展的话，需要把它的包下载下来，下载地址是：

https://github.com/swoole/swoole-src

2.下载下来之后进行解压：

unzip swoole-src-master.zip

3.解压之后打开解压的目录，我是解压在目录/opt下面的,所以

cd /opt/swoole-src-master

4.然后使用phpize重新编译php，执行命令：

 /usr/bin/phpize
如果你找不到phpize文件在哪，可以用指令查找，最好在根目录下，这样它才能从根目录下开始查找：

find -name phpize
如果你系统没有安装phpize的话，执行命令安装就可以了，
指令为：
yum install php-devel

如果这里出现
Can’t find PHP headers in /usr/include/php
The php-devel package is required for use of this command
也执行 yum install php-devel

5.然后再进行配置，指令为：

 ./configure --with-php-config=/usr/bin/php-config
因为我的php-config文件在/usr/bin/下面，所以只要你用自己的php-config路径就可以了，其他都一致

6.配置好之后，进行编译安装：

make && make install
但在这步可能会出现问题：

/usr/include/php/ext/pcre/php_pcre.h:29:18: error: pcre.h: No such file or directory
该错误是因为没有安装pcre-devel导致的，所有只要安装下就可以了

yum install pcre-devel

7.安装好之后会输出一个路径，那个就是生成swoole.so的文件路径，然后配置php.ini，把该路径配置进去：

extension=/usr/lib/php/modules/swoole.so

8.然后重启服务器

service httpd restart

9.通过php -m 或者phpinfo()查看是否安装成功