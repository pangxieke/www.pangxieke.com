---
title: Linux下Mysql数据库导入命令
id: 819
categories:
  - linux
date: 2015-09-20 11:09:15
tags:
---

linux下导入、导出mysql数据库命令

## 一、导出数据库用mysqldump命令

（注意mysql的安装路径，即此命令的路径）：

1、导出数据和表结构：
```
mysqldump -u用户名 -p密码 数据库名 > 数据库名.sql
#/usr/local/mysql/bin/ mysqldump -uroot -p abc > abc.sql
```
敲回车后会提示输入密码

2、只导出表结构
```
mysqldump -u用户名 -p密码 -d 数据库名 > 数据库名.sql
#/usr/local/mysql/bin/ mysqldump -uroot -p -d abc > abc.sql
```

注：`/usr/local/mysql/bin/` —> mysql的data目录
3、导出特定表
```
mysqldump　-uroot　-p　-B　数据库名　–table　表名　>　xxx.sql
```

## 二、导入数据库

1、首先建空数据库
```
mysql>create database abc;
```

2、导入数据库

方法一：
（1）选择数据库
```
mysql>use abc;
```
（2）设置数据库编码
```
mysql>set names utf8;
```
（3）导入数据（注意sql文件的路径）
```
mysql>source /home/abc/abc.sql;
```

方法二：
```
mysql -u用户名 -p密码 数据库名 < 数据库名.sql
#mysql -uabc_f -p abc < abc.sql
```

建议使用第二种方法导入。

注意：有命令行模式，有sql命令

## 导入速度优化
使用`source`导入数据库时，速度十分缓慢。400M的数据，大概需要5个小时。
此时修改
```
innodb_flush_log_at_trx_commit=0 
```
然后重启数据库
速度会有很大的提升。

默认值1的意思是每一次事务提交或事务外的指令都需要把日志写入（flush）硬盘，这是很费时的。特别是使用电池供电缓存（Battery backed up cache）时。
设成2对于很多运用，特别是从MyISAM表转过来的是可以的，它的意思是不写入硬盘而是写入系统缓存。日志仍然会每秒flush到硬 盘，所以你一般不会丢失超过1-2秒的更新。设成0会更快一点，但安全方面比较差，即使MySQL挂了也可能会丢失事务的数据。而值2只会在整个操作系统 挂了时才可能丢数据。 