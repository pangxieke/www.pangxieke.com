---
title: MySQL doesn't yet support 'LIMIT & IN/ALL/ANY/SOME
tags:
  - Mysql
id: 337
categories:
  - mysql
date: 2014-10-05 22:02:46
---

今天，写了一条SQL语句，但提示This version of MySQL doesn't yet support 'LIMIT &amp; IN/ALL/ANY/SOME subquery'。

这句话的意思是，这版本的 MySQL 不支持使用 LIMIT 子句的 IN/ALL/ANY/SOME 子查询，即是支持非 IN/ALL/ANY/SOME 子查询的 LIMIT 子查询。

也就是说，这样的语句是不能正确执行的。 select * from table where id in (select id from table limit 10)

但是，只要你再来一层就行。如： select * from table where id in (select t.id from (select * from table limit 10)as t)