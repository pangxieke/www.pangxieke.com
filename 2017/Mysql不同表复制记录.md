---
title: Mysql不同表复制记录
id: 887
categories:
  - php
date: 2015-12-21 16:22:07
tags: mysql
---

Mysql复制一条或多条记录并插入表|mysql从某表复制一条记录到另一张表

一、复制表里面的一条记录并插入表里面
    ```
    insert into article(title,keywords,desc,contents) select title,keywords,desc,contents from article where article_id = 100;
    ```

二、复制表里的多条数据/记录，并插入到表里面
    ```
    INSERT INTO `power_node`(title,type,status) SELECT title,type,status FROM power_node WHERE id < 5;
    INSERT into jiaban (num,overtime) SELECT num,overtime from jiaban where id IN(1,3,5,6,7,9);
    ```

三、在创建表时，就插入另一张表里面的某些数据
    ```
     create table user AS select * from member where id < 10
    ```