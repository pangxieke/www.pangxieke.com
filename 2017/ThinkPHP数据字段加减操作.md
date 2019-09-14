---
title: ThinkPHP数据字段加减操作
id: 556
comment: false
categories:
  - php
date: 2015-03-13 19:22:57
tags: thinkphp
---

进行数据字段加减操作

经常有需要对某个数据表的计数字段进行加减操作，我们来看下在ThinkPHP中的具体使用办法。

最简单的，使用下面方法对score自动加1：

```php
M('User')->where('id=5')->setInc('score');
```

当然，也可以加更多的积分：

```php
M('User')->where('id=5')->setInc('score',5);
```

当然也可以减1操作

```php
M('User')->where('id=5')->setDec('score');
```

setInc和setDec方法只能单独对一个字段进行操作，如果你的字段加减操作要和其他字段的更新一起的话，则需要采用表达式更新的方式了，例如：

```php
$User = M('User');
$User->id = 5;
$User->nickname = 'ThinkPHP';
$User->score = array('exp','score+5');
$User->save();
```

表示对id为5的用户数据进行昵称和积分修改操作。

```php
$User->score = array('exp','score+5');
```

这段代码就称之为表达式更新。

[ThinkPHP进行数据字段加减操作](http://www.thinkphp.cn/code/53.html "进行数据字段加减操作")