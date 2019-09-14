---
title: mysql将一列数据转换成一行
tags:
  - Mysql
id: 771
categories:
  - mysql
date: 2015-08-19 20:35:39
---

ysql将一列数据用指定分隔符转换成一行,使用到mysql的一个函数`group_concat`

该函数返回带有来自一个组的连接的非NULL值的字符串结果

``` SELECT group_concat(id,’,’) FROM bs_ticket where id < 700```

result：

`‘673,,674,,675,,676,,677,,678,,679,,680,,681,,682,,683,,684,,685,,686,,687,’`