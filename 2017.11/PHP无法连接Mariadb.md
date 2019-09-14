---
title: PHP无法连接Mariadb 
date: 2017.11.1 18:30:00
category: mysql
id: php-disable-connect-mariadb
---

最近重新安装了服务器环境，单独安装了`Nginx`,`PHP`,`MariaDb`,但是配置好Nginx服务后，访问网站却提示错误。

## 故障
**故障表现：**  网站wordpress提示`建立数据库连接时出错`
测试发现，Mysql命令行能够登录，但PHP无法连接。

## 排查
### PHPinfo
使用`phpinfo()`,查询是否安装Mysql扩展，查询到已经安装`mysqli`扩展
同时使用如下php代码查询
```
<?php
if (extension_loaded('mysqli'))
{
    echo 'yes';
}
else
{
    echo 'no';
}
```
返回yes，说明已经安装`mysqli`扩展

### php 数据库链接测试
使用如下代码测试
```
<?php
$db = new mysqli('localhost', 'root', 'password', 'database_name');
    if (mysqli_connect_errno())
    {
        echo '<p>' . 'Connect DB error';
    }
```
返回`Connect DB error`,说明PHP与Mysql未建立连接。查到问题后就很好解决了。
**原因** PHP与Mysql未建立通信链接，因为php与mysql是分别安装的，未配置php.ini的相关选项

## 解决

### 查询Mysql sock路径
```
show variables like 'socket';
```
```
MariaDB [(none)]> show variables like 'socket';
+---------------+---------------------------+
| Variable_name | Value                     |
+---------------+---------------------------+
| socket        | /var/lib/mysql/mysql.sock |
+---------------+---------------------------+
1 row in set (0.00 sec)

```

### php.ini配置Mysql socket路径
编辑php.ini，找到`mysql.default_socket`配置项或者``mysqli.default_socket`，默认一般是空值,将此项添加值为上面回显中的`/var/lib/mysql/mysql.sock`：
```
; Default socket name for local MySQL connects.  If empty, uses the built-in
; MySQL defaults.
; http://php.net/mysqli.default-socket
mysqli.default_socket = /var/lib/mysql/mysql.sock
```

### 重启PHP
```
service php-fpm restart
# 或者
service php restart
```