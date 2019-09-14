---
title: Group by 和sum组合使用
tags:
  - Mysql
id: 229
categories:
  - mysql
date: 2014-09-09 21:31:30
---

a表  编号    通话时间  通话次数
b表     编号    销售量
 查询结果：  编号 通话时间 通话次数  销售量   
select 编号,sum(通话时间)，sum（通话次数），sum（销售量）  from 表A left join 表B on a.编号=b.编号 group by a.编号 这样查询，通话次数超过两次。统计出来的销售量就有错误

这里统计出来的销售量肯定是有问题的。在表A left join 表 B on a.编号=b.编号的情况下,当编号相同的记录在a表中有多条通话记录的时候,销售量也就扩大到多少倍.
例如:编号为1的,在a表中有5条通话记录,在b表中有一条记录,销售量为4.然后a表和b表一关联,就出现了5条都有销售量为4的记录,在这样的情况下,sum(销售量)的结果就是5(条记录)*4(每条记录的销售量)=20.得到这样的结果肯定不是你所需要的。 

Group By与聚合函数

group by语句中select指定的字段必须是“分组依据字段”，其他字段若想出现在select中则必须包含在聚合函数中，常见的聚合函数如下表：
函数	作用	支持性
sum(列名) 	求和 	　　　　
max(列名) 	最大值 	　　　　
min(列名) 	最小值 	　　　　
avg(列名) 	平均值 	　　　　
first(列名) 	第一条记录 	仅Access支持
last(列名) 	最后一条记录 	仅Access支持
count(列名) 	统计记录数 	注意和count(*)的区别

select 类别, avg(数量) AS 平均值 from A group by 类别;

求各组记录数目

select 类别, count(*) AS 记录数 from A group by 类别;

Having与Where的区别

where 子句的作用是在对查询结果进行分组前，将不符合where条件的行去掉，即在分组之前过滤数据，where条件中不能包含聚组函数，使用where条件过滤出特定的行。
having 子句的作用是筛选满足条件的组，即在分组之后过滤数据，条件中经常包含聚组函数，使用having 条件过滤出特定的组，也可以使用多个分组标准进行分组。