---
title: mysql主从同步配置
id: 1191
categories:
  - share
date: 2016-12-23 19:38:05
tags:
---

[![thumb_1481508598](/images/2016/12/thumb_1481508598.jpg)](/images/2016/12/thumb_1481508598.jpg)

## 主从服务器作用

MySQL的主从同步是一个很成熟的架构，优点为：
1.在从服务器可以执行查询工作(即我们常说的读功能)，降低主服务器压力；
2.在从主服务器进行备份，避免备份期间影响主服务器服务；
3.当主服务器出现问题时，可以切换到从服务器。
所以在项目部署和实施中经常会采用这种方案.

## 主从同步原理

主服务器将更新写入二进制日志文件，并维护文件的一个索引以跟踪日志循环。这些日志可以记录发送到从服务器的更新。当一个从服务器连接主服务器时，它通知 主服务器从服务器在日志中读取的最后一次成功更新的位置。从服务器接收从那时起发生的任何更新，然后封锁并等待主服务器通知新的更新。

MySQL复制基于主服务器在二进制日志中跟踪所有对数据库的更改(更新、删除等等)。因此，要进行复制，必须在主服务器上启用二进制日志。

每个从服务器从主服务器接收主服务器已经记录到其二进制日志的保存的更新，以便从服务器可以对其数据拷贝执行相同的更新。

从服务器设置为复制主服务器的数据后，它连接主服务器并等待更新过程。如果主服务器失败，或者从服务器失去与主服务器之间的连接，从服务器保持定期尝试连 接，直到它能够继续帧听更新。由--master-connect-retry选项控制重试间隔。 默认为60秒。

每个从服务器跟踪复制时间。主服务器不知道有多少个从服务器或在某一时刻有哪些被更新了。

## 主服务器配置

实验中我先让19.43当主服务器，19.48为从服务器。
[![281049324061919](/images/2016/12/281049324061919.png)](/images/2016/12/281049324061919.png)

1.编辑my.cnf

```php
#启用二进制日志（如果定义到其他路径，请给予其mysql权限）：
log-bin=/mydata/data/mysql-bin

#定义server-id：
server-id       = 1

#datadir
datadir = /mydata/data             //增加此行
```

2.创建有复制权限的账号：

```php
GRANT REPLICATION SLAVE,REPLICATION CLIENT ON *.* TO slave@192.168.19.48 IDENTIFIED BY '123456';      //遵循最小权限原则
FLUSH PRIVILEGES;
```

3.记录最后的二进制日志信息，CHANGE MASTER时会用到：

```php
SHOW MASTER LOGS;
```

## 从服务器配置

1\. 操作配置文件：

```php
#启动中继日志（如果定义到其他路径，请给予其mysql权限）：
relay_log=/mydata/data/relay-log

#从服务器用中继日志就足够了，关闭二进制日志，减少磁盘IO：
#log-bin=mysql-bin             //将其注释
#binlog_format=mixed

#定义server-id：
server-id       = 2            //不能与主服务器相同
```

2.配置CHANGE MASTER：

```php
CHANGE MASTER TO MASTER_HOST='192.168.19.43',MASTER_USER='slave',MASTER_PASSWORD='123456',MASTER_LOG_FILE='mysql-bin.000002',MASTER_LOG_POS=326;
SHOW SLAVE STATUS 

#启动io thread以及sql thread：
START SLAVE;
```

3.在主服务器创建数据库，从服务器查看：

```php
CREATE DATABASE jason;     //主服务器创建数据库
SHOW DATABASES;            //从服务器查看
```

4.如果主数据库不是新建立的，而是使用过一段时间，且里面已经有不少数据的情况下，需要先把主服务器数据导出，再导入到从服务器，然后根据上面的步骤进行主从复制，这里将不再演示。

导出数据库命令参考：[mysql数据库导入命令(Linux)](http://www.pangxieke.com/linux/819.html)

参考原文 [**http://www.cnblogs.com/tae44/p/4682810.html**](http://www.cnblogs.com/tae44/p/4682810.html)