---
title: 在Apache服务器内部通信
tags:
  - apache
id: 207
categories:
  - php
date: 2014-09-08 11:16:32
---

问题：
	想要实现PHP与Apache请求进程的其他部分之间的同学，其中包括在access_log中设置变量

方案：
	使用 apache_nete()

```php
	//取值
	$session = apache_note('session');

	//设置值
	apache_note('session', $session);

```

Apache在处理来自客户端的请求时,会经过一系列步骤，而PHP只是整个链条中的一环而已。
Apache也能够实现重新映射URL、认证用户身份、记录请求等功能。
而在处理请求时，每个句柄都需要访问一组称为记录表的键/值对。
通过apache_note()函数可访问该请求中的先行句柄在记录表中设置的信息，并为后来的句柄留下信息。

例如，如果你使用session模块对用户进行跟踪并且实现了跨请求保存变量，
就可以将这些功能和日志文件分析结合起来，最后能够得到每个用户的平均页面浏览量(page views)
通过apache_note()与日志模块的结合，可以把每个请求的session Id直接写入到access_log中。
首先用下面的代码把session ID添加到记录表。

```php
	//取得session ID，并将其添加到Apache的记录表中
	apache_note('session_id', session_id());
```

然后，修改httpd.conf文件，把字符串%{session_id}n 添加到LogFormat指令中。
该字符串后面字母n的含义是告诉Apache通过另一个模块来使用保存在其记录表中的变量。

如果PHP中启用了--enable-momory-limit配置选项，就会把每个请求的峰值内存使用率，
保存到名为mod_php_momory_usage的记录中，用%{mod_php_memory_usage}n
就可以把相应的内存使用率信息添加到LogFormat中。