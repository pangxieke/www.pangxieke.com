---
title: Mysql启动cannot allocate memory for the buffer pool
tags:
  - Mysql
id: 569
comment: false
categories:
  - mysql
date: 2015-03-20 19:48:54
---

今天打开开发网站突然不能使用，发现时MySQL数据库down掉了，就去重启：

 mysql.server  restart

一直会报错：“Manager of pid-file quit without updating file”。

然后就去找原因，网上说有以下三个方面的问题：

1、硬盘不够用了，无法写入pid文件

2、进程卡死了，找到mysql进程kill掉，然后重启

3、目录权限问题，找到pid文件写入的目录，查看目录权限是否是使用的安装mysql指定的用户

上述是关于这个问题的最多的解决方案，但是我试了一下都没效果。于是我去查看了mysql错误日志文件：
/var/log/mysqld.log
[![mysql错误](/images/2015/03/mysql错误.png)](/images/2015/03/mysql错误.png)

```php
150320 19:16:38 mysqld_safe mysqld from pid file /var/run/mysqld/mysqld.pid ended
150320 19:18:55 mysqld_safe Starting mysqld daemon with databases from /var/lib/mysql
150320 19:18:55 [Note] libgovernor.so not found
150320 19:18:55 [Note] Plugin 'FEDERATED' is disabled.
150320 19:18:55 InnoDB: The InnoDB memory heap is disabled
150320 19:18:55 InnoDB: Mutexes and rw_locks use GCC atomic builtins
150320 19:18:55 InnoDB: Compressed tables use zlib 1.2.3
150320 19:18:55 InnoDB: Using Linux native AIO
150320 19:18:55 InnoDB: Initializing buffer pool, size = 128.0M
InnoDB: mmap(137363456 bytes) failed; errno 12
150320 19:18:55 InnoDB: Completed initialization of buffer pool
150320 19:18:55 InnoDB: Fatal error: cannot allocate memory for the buffer pool
150320 19:18:55 [ERROR] Plugin 'InnoDB' init function returned error.
150320 19:18:55 [ERROR] Plugin 'InnoDB' registration as a STORAGE ENGINE failed.
150320 19:18:55 [ERROR] Unknown/unsupported storage engine: InnoDB
150320 19:18:55 [ERROR] Aborting

150320 19:18:55 [Note] /usr/libexec/mysqld: Shutdown complete
```

原因是内存不足 Fatal error: cannot allocate memory for the buffer pool 

然后就去检查了一下内存：

free -ml

发现确实不足，只剩下67M了，因此首先kill掉了机器上其他的一些无用的进程.
另外可以 去修改了my.cnf配置文件，找到了如下两个字段分别将前者由2G改为200M，后者改为了120M。
```php
vi /etc/my.cnf
innodb_buffer_pool_size=200M
key_buffer=120M
```

然后在重启MySQL后就可以了。