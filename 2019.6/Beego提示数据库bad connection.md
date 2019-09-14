---
title: Beego提示数据库bad connection
id: mysql-bad-connection-in-beego
tags: go
category: mysql
date: 2019-6-27 20:35:00
---


使用Beego开发Go项目中，有时候在有大量操作Mysql时，有时候会发生如下错误。
```
"driver: bad connection"
```

## 原因
这是因为Mysql服务器主动关闭了Mysql链接。
在项目中使用了一个mysql链接，同时使用了事务，处理多个表操作。处理时间长。
导致空闲链接超时，Mysql关闭了链接。而客户端保持了已经关闭的链接。

**具体原因是**：
beego没有调用db.SetConnMaxLifetime 这个方法，导致客户端保持了已经关闭的链接。

## 解决
Beego调用`db.SetConnMaxLifetime(time.Second)`,设置数据库闲链接超时时间。
```
engine.SetConnMaxLifetime(time.Second * 30)
```
![enter description here](/images/2019/06/1561638690942.jpg)

参考文章[https://studygolang.com/topics/5576](https://studygolang.com/topics/5576)