---
title: CentOS6.5下编译安装MySQL 5.6.16
id: 821
categories:
  - linux
date: 2015-09-21 21:53:11
tags:
---

## 一、编译安装MySQL前的准备工作

安装编译源码所需的工具和库

```php
yum install gcc gcc-c++ ncurses-devel perl
```

安装cmake，从http://www.cmake.org下载源码并编译安装

```php
wget http://www.cmake.org/files/v2.8/cmake-2.8.10.2.tar.gz
tar -xzvf cmake-2.8.10.2.tar.gz
cd cmake-2.8.10.2
./bootstrap ; make ; make install
cd ~
```

## 二、设置MySQL用户和组

新增mysql用户组

```php
groupadd mysql
```

新增mysql用户

```php
useradd -r -g mysql mysql
```

## 三、新建MySQL所需要的目录

新建mysql安装目录

```php
mkdir -p /usr/local/mysql
```

新建mysql数据库数据文件目录

```php
mkdir -p /data/mysqldb
```

## 四、下载MySQL源码包并解压

```php
wget http://dev.mysql.com/get/Downloads/MySQL-5.6/mysql-5.6.16.tar.gz
tar -zxvf mysql-5.6.16.tar.gz
cd mysql-5.6.16
```

## 五、编译安装MySQL

从mysql5.5起，mysql源码安装开始使用cmake了，设置源码编译配置脚本。

```php
cmake -DCMAKE_INSTALL_PREFIX=/usr/local/mysql -DMYSQL_UNIX_ADDR=/usr/local/mysql/mysql.sock
-DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci -DWITH_INNOBASE_STORAGE_ENGINE=1
-DWITH_ARCHIVE_STORAGE_ENGINE=1 -DWITH_BLACKHOLE_STORAGE_ENGINE=1 -DMYSQL_DATADIR=/data/mysqldb
-DMYSQL_TCP_PORT=3306 -DENABLE_DOWNLOADS=1
```

注：重新运行配置，需要删除CMakeCache.txt文件

```php
rm CMakeCache.txt
```

编译源码,安装

```php
make
make install
```

## 六、修改mysql目录所有者和组

修改mysql安装目录

```php
cd /usr/local/mysql
chown -R mysql:mysql .
```

修改mysql数据库文件目录

```php
cd /data/mysqldb
chown -R mysql:mysql .
```

## 七、初始化mysql数据库

```php
cd /usr/local/mysql
scripts/mysql_install_db --user=mysql --datadir=/data/mysqldb
```

## 八、复制mysql服务启动配置文件

```php
cp /usr/local/mysql/support-files/my-default.cnf /etc/my.cnf
注：如果/etc/my.cnf文件存在，则覆盖。
```

## 九、复制mysql服务启动脚本及加入PATH路径

```php
cp support-files/mysql.server /etc/init.d/mysqld   

vim /etc/profile   

	PATH=/usr/local/mysql/bin:/usr/local/mysql/lib:$PATH  

	export PATH  

source /etc/profile
```

## 十、启动mysql服务并加入开机自启动(可选这个步骤，以后可以自己启动的)

```php
service mysqld start
chkconfig --level 35 mysqld on
```

可能出现的错误
Starting MySQL...The server quit without updating PID file (/usr/local/mysql/data/localhost.localdomain.pid)
原因：没有初始化权限表

解决

```php
cd /usr/local/mysql（进入mysql安装目录）
chown -R mysql.mysql .
su - mysql
scripts/mysql_install_db
```

## 十一、检查mysql服务是否启动

```php
netstat -tulnp | grep 3306
mysql -u root -p
```

密码为空，如果能登陆上，则安装成功。

## 十二、修改MySQL用户root的密码

```php
mysqladmin -u root password '123456'
```

注：也可运行安全设置脚本，修改MySQL用户root的密码，同时可禁止root远程连接，移除test数据库和匿名用户。

```php
/usr/local/mysql/bin/mysql_secure_installation
```

## 十三、可能会出现的错误

```php
问题：
Starting MySQL..The server quit without updating PID file ([FAILED]/mysql/Server03.mylinux.com.pid)
解决：
修改/etc/my.cnf 中datadir,指向正确的mysql数据库文件目录
```

```php
问题：
ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/tmp/mysql.sock'
解决：
新建一个链接或在mysql中加入-S参数，直接指出mysql.sock位置。
ln -s /usr/local/mysql/data/mysql.sock /tmp/mysql.sock
/usr/local/mysql/bin/mysql -u root -S /usr/local/mysql/data/mysql.sock
```

```php
&lt;span style=&quot;color: #000000;&quot;&gt;ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/var/lib/mysql/mysql.sock' (2)&lt;/span&gt;
解决：
&lt;span style=&quot;color: #000000;&quot;&gt;修改/etc/my.conf&lt;/span&gt;
&lt;span style=&quot;color: #000000;&quot;&gt;[mysqld] &lt;/span&gt;&lt;br style=&quot;color: #000000;&quot; /&gt;&lt;span style=&quot;color: #000000;&quot;&gt;socket=/var/lib/mysql/mysql.sock &lt;/span&gt;
```

```php
MySQL问题解决：-bash:mysql:command not found
因为mysql命令的路径在/usr/local/mysql/bin下面,所以你直接使用mysql命令时,
系统在/usr/bin下面查此命令,所以找不到了
解决办法是：
 ln -s /usr/local/mysql/bin/mysql /usr/bin　做个链接即可
```

参考文章

[ Linux CentOS6.5下编译安装MySQL 5.6.16【给力详细教程】](http://blog.csdn.net/wendi_0506/article/details/39478369 " Linux CentOS6.5下编译安装MySQL 5.6.16【给力详细教程】")