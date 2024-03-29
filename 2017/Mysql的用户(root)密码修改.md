---
title: Mysql的用户(root)密码修改
tags:
  - Mysql
  - 用户密码
id: 637
categories:
  - linux
date: 2015-04-26 21:41:22
---

Linux下修改Mysql的用户(root)的密码,修改的用户都以root为列。

### 一、拥有原来的myql的root的密码

#### 方法一：
在mysql系统外，使用mysqladmin
```
# mysqladmin -u root -p password "test123"
Enter password: 【输入原来的密码】
```
#### 方法二：
通过登录mysql系统，
```php
mysql -uroot -p
Enter password: 【输入原来的密码】
mysql>use mysql;
mysql> update user set password=passworD("test") where user='root';
mysql>flush privileges;
mysql> exit;
```

### 二、忘记原来的myql的root的密码；

首先，你必须要有操作系统的root权限了。要是连系统的root权限都没有的话，先考虑root系统再走下面的步骤。类似于安全模式登录系统，有人建议说是pkill mysql，但是我不建议哈。因为当你执行了这个命令后，会导致这样的状况：
```php
/etc/init.d/mysqld status
mysqld dead but subsys locked
```
这样即使你是在安全模式下启动mysql都未必会有用的，所以一般是这样/etc/init.d/mysqld stop，如果你不幸先用了pkill，那么就start一下再stop咯。
```php
mysqld_safe --skip-grant-tables &;
```
&，表示在后台运行，不再后台运行的话，就再打开一个终端咯。
```php
# mysql
mysql> use mysql;
mysql>UPDATE user SET password=password("test123") WHERE user='root';
mysql>flush privileges;
mysql> exit;
```
**本来mysql是不分大小写的，但是这个是修改的mysql中的mysql数据库的具体的值，要注意到**