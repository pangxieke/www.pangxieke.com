---
title: Centos7安装MariaDB 10.1.28
date: 2017.10.30
category: mysql
id: centos-install-mariadb-10.1.28
---

重新安装了服务器，现在服务器为Centos7.4版本，选择安装MariaDB。
MariaDB 是 MySQL 数据库的自由开源分支，与 MySQL 在设计思想上同出一源。命令及查询语句也基本一致。

## 添加 MariaDB yum库
在 RHEL/CentOS 和 Fedora 操作系统中添加 MariaDB 的 YUM 配置文件 MariaDB.repo 文件
```
vi /etc/yum.repos.d/MariaDB.repo
```
添加下列内容到文件的末尾
```
[mariadb]
name = MariaDB
baseurl = http://yum.mariadb.org/10.1/centos7-amd64
gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
gpgcheck=1
```

![Yum源](/images/2017/10/2017032011315833.png)
## Yum 安装MariaDB
```
yum install MariaDB-server MariaDB-client -y
```
![Yum安装](/images/2017/10/20171030093630.png)

## 启动服务

启动数据库服务守护进程，并可以通过下面的操作设置，在操作系统重启后自动启动服务。
```
systemctl start mariadb
systemctl enable mariadb
systemctl status mariadb
```
![启动](/images/2017/10/20171030093732.png)

## 安全配置
安全配置：设置 MariaDB 的 root 账户密码，禁用 root 远程登录，删除测试数据库以及测试帐号，最后需要使用下面的命令重新加载权限。

```
 mysql_secure_installation
 ```
 ![配置](/images/2017/10/2017032011315936.png)
 ## 测试
 检查下 MariaDB 的特性，比如：版本号、默认参数列表、以及通过 MariaDB 命令行登录。如下所示：
 
 ```
mysql -V
mysqld --print-defaults
mysql -u root -p
```
![测试](/images/2017/10/20171030093817.png)

