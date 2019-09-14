---
title: apache虚拟主机配置
tags:
  - apache
id: 312
categories:
  - php
date: 2014-09-25 22:18:18
---

apache虚拟主机配置
ServerAdmin webmaster@example.com ——管理员邮箱（可以随便写一个）
DocumentRoot "/home/phpok-com" ——网站目录
ServerName example.com —— 要绑定的域名
ServerAlias www.example.com ——要绑定的别名，如果有多个别名就用英文逗号隔开
CustomLog logs/example.com_custom_log——用户日志格式（这一行也可以为空）
ErrorLog logs/example.com_error_log ——错误日志（也可以为空）

步骤：
1，修改httpd.conf
把#Include conf/extra/httpd-vhosts.conf前面的#去掉，意思是让httpd.conf文件包含httpd-vhosts.conf这个配置文件，这是apache的配置模块化的一个表现，这里不多说。
寻找httpd.conf中的ServerName，如果ServerName的设置不是域名 www.abc1.com的话，那么改为ServerName www.abc1.com，如过你要用ssl之类的东西，那么改为ServerName www.abc1.com:80，就是加了个端口。
2.修改httpd-vhosts.conf
添加如下代码（有些可能文件里面就有，改一下就可以了）：
NameVirtualHost *

DocumentRoot "C:/aic"
ServerName www.abc1.com
ServerAlias abc1.com *.abc1.com

DocumentRoot "c:/aic/mybbonline"
ServerName www.efg2.com
ServerAlias efg2.com *.efg2.com

值得注意的是，VirtualHost是有顺序的，排在最前的VirtualHost的我们默认的网站域名，其中的DocumentRoot和ServerName都必须与httpd.conf中的一样，包括端口。
DocumentRoot是虚拟主机的路径
而ServerAlias是域名的别名，配置了这个，那么一些二级域名就都可以进行虚拟主机解析了。如*.efg2.com就可以代表bbs.efg2.com或news.efg2.com等。
-----------------------------------------------------------------
虚拟主机的一般形式诸如（extra/httpd-vhosts.conf）：
NameVirtualHost *:80

ServerName www.domain.tld
ServerAlias domain.tld *.domain.tld
DocumentRoot /www/domain

ServerName www.otherdomain.tld
DocumentRoot /www/otherdomain

这是apache2.2中文参考手册中的示例。一般的咱们这样配置在以前版本是没有问题的。但是现在就不一样了。当访问某个虚拟主机下的页面的时候会出现类似：“403(禁止访问)，你无法查看该网页…”的错误,

# Forbidden

You don't have permission to access / on this server.

很明显这是拒绝访问的提示。按照经验很容易找到(httpd.conf)下面的内容：

Options FollowSymLinks
AllowOverride None
Order deny,allow
//先拒绝后允许Deny from all
//拒绝所有的访问
这一个部分就是对目录进行访问控制的，很显然这设置得很严格，因此，我们必须手动加入虚拟机目录的权限控制块，才可以让用户正常访问虚拟机的目录及页面文件。有两个地方可以加入虚拟目录访问权限控制块：主配置文件httpd.conf和虚拟机配置文件httpd-vhost.conf,毫无疑问我们选择虚拟机配置文件，主要是维护起来更方便。那么我们把目录访问控制块插入到虚拟机配置文件，这样我们的虚拟主机的配置文件写法就类似这样：

DocumentRoot "E:/web"
ServerName www.domain.tld
//插入开始
&lt;Directory "E:/web"&gt;
Options -Indexes FollowSymLinks
AllowOverride None
Order allow,deny
Allow from all

//插入结束
3，打开C:\WINDOWS\system32\drivers\etc\hosts
在下列插入相应的配置信息：

# Copyright (c) 1993-1999 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
# 102.54.94.97 rhino.acme.com # source server
# 38.25.63.10 x.acme.com # x client host

127.0.0.1 localhost
127.0.0.1 www.domain.tld
127.0.0.1 www.otherdomain.tld

这样配置完成后，我们使用apachectl命令及相关参数来检测配置文件是否有问题。确认无误后进行，重新启动apache服务。然后使用浏览器进行访问测试（呵呵！又是废话了！）。如果还不行的话，那就检查你的页面文件的权限设置是否过高，导致的不能访问了！当然这种情况在windows下比较少见，一般可能出现在linux环境下。