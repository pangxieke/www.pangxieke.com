---
title: Mysql日志记录慢查询及所有Sql
category: mysql
id: mysql-switch-on-slow-log-and-general-log
date: 2018.4.18 19:30:00
---

启慢查询日志，可以让MySQL记录下查询超过指定时间的语句。
通过定位分析性能的瓶颈，可以更好的优化数据库系统的性能。这是常用的MySQL性能优化方式。精准定位到慢查询语句，可以快速解决问题。

同时，有时候也需要监控所有Sql的执行，我们可以开启Mysql的日志，记录所有的Sql语句，方便排查问题。

## 记录所有Sql

```
show variables like "%general_log%";
```

### 设置
```
set general_log=on;
//提示
ERROR 1229 (HY000): Variable 'general_log' is a GLOBAL variable and should be set with SET GLOBAL

set global general_log=on;
```
再次查看

![](/images/2018/04/1524046650799.jpg)


## 开启 慢查询

```
mysql> show variables like 'slow_query%';
+---------------------------+----------------------------------+
| Variable_name             | Value                            |
+---------------------------+----------------------------------+
| slow_query_log            | OFF                              |
| slow_query_log_file       | /mysql/data/localhost-slow.log   |
+---------------------------+----------------------------------+

mysql> show variables like 'long_query_time';
+-----------------+-----------+
| Variable_name   | Value     |
+-----------------+-----------+
| long_query_time | 10.000000 |
+-----------------+-----------+
```

### 开启

在Mysql命令行操作
将 `slow_query_log` 全局变量设置为`ON`状态
```
set global slow_query_log='ON'; 
```
设置慢查询日志存放的位置
```
set global slow_query_log_file='/usr/local/mysql/data/slow.log';

```
查询超过1秒就记录
```
set global long_query_time=1;
```

查看
```
show variables like 'slow_query%';
```

测试
```
select sleep(2);
```

查看日志
```
ls  /usr/local/mysql/data/slow.log
```

如果日志存在，MySQL开启慢查询设置成功！
