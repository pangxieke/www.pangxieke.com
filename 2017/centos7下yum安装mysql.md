---
title: centos7下yum安装mysql
tags:
  - Mysql
id: 1159
categories:
  - mysql
date: 2016-12-08 00:00:22
---

[![1480423042](/images/2016/12/1480423042.jpg)](/images/2016/12/1480423042.jpg)
最近安装了Centos7，需要重装LNMP环境，使用yum安装是最快捷的方式，CentOS7需要先下载mysql的repo源。

## 下载mysql的repo源

```php
wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm

#安装mysql-community-release-el7-5.noarch.rpm包
sudo rpm -ivh mysql-community-release-el7-5.noarch.rpm
```
安装这个包后，会获得两个mysql的yum repo源：/etc/yum.repos.d/mysql-community.repo，/etc/yum.repos.d/mysql-community-source.repo。

## 安装mysql

```php
sudo yum install mysql-server
```
根据步骤安装就可以了，不过安装完成后，没有密码，需要重置密码。

```php
# 登录mysql
mysql -u root
```

登录时有可能报这样的错：`ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/lib/mysql/mysql.sock'.`
原因是/var/lib/mysql的访问权限问题。
解决方法：改变var/lib/mysql的拥有者，或者权限，这里暂时改为777
```php
sudo chmod 0777  /var/lib/mysql
#然后，重启服务：
service mysqld restart
 
# 接下来登录重置密码：
mysql -u root
mysql > use mysql;
mysql > update user set password=password(‘123456‘) where user=‘root‘;
mysql > exit;
```

## 开放3306端口

```php
sudo vim /etc/sysconfig/iptables
# 添加以下内容：
-A INPUT -p tcp -m state --state NEW -m tcp --dport 3306 -j ACCEPT
# 或者
firewall-cmd --zone=public --add-port=3306/tcp --permanent
 
#保存后重启防火墙：
sudo service iptables restart
#或者
firewall-cmd --reload
#或者
systemctl stop firewalld.service #停止
systemctl disable firewalld.service #禁用
```

这样从其它客户机也可以连接上mysql服务了。

## 开放用户连接

上面是开发了端口连接权限，下面是开放用户连接权限。
```php
mysql -u root -p    //登录MySQL 
mysql> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'WITH GRANT OPTION;     //任何远程主机都可以访问数据库 
mysql> FLUSH PRIVILEGES;    //需要输入次命令使修改生效
```