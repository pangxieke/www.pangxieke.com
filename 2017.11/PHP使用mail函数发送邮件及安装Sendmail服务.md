---
title: PHP使用mail函数发送邮件及安装Sendmail服务
date: 2017.11.2 18:30:00
category: linux
id: php-mail-linux-install-sendmail-service
---

在PHP中，常有发送邮件功能。PHP中有`mail()`函数，可以用来发送邮件。
但在本地使用时，我们常发送邮件发送失败。这是因为使用发送邮件功能，需要服务器支持。

在linux上，我们常使用`sendmail`组件。

php中`mail()`函数
```
bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] )
```

## 安装sendmail
Yum安装
```
yum install sendmail
```

设置主机名，主机名要设置一个域名格式的,例如:jb51.net
```
hostname jb51.net
```

设置主机名后，需要重启sendmail服务
查看sendmail服务状态
```
service sendmail status
service sendmail restart
```

## php配置
修改`php.ini`文件
```
vi /usr/local/php/etc/php.ini
```

配置`sendmail_path`
```
sendmail_path = /usr/sbin/sendmail -t -i
```

重启PHP
```
service php-fpm restart
```
使用`phpinfo()`查看服务是否配置成功

![phpinfo](/images/2017/11/php_sendmail.png)

## 测试
测试 代码
```
<?php 
$send = mail('pangxieke@126.com', '邮件标题', '测试邮件内容，如果收到此邮件，表示mail函数成功启用！'); 
echo $send ? 'send ok' : 'send false';
```

## 错误
查看错误日志
```
sudo tail -f /var/log/maillog 
```
得到错误信息
```
Nov  2 16:26:36 Visitor sendmail[3159]: vA28QaFS003159: from=www, size=161, class=0, nrcpts=1, msgid=<201711020826.vA28QaFS003159@pangxieke.com>, relay=www@localhost
Nov  2 16:26:36 Visitor sendmail[3160]: NOQUEUE: tcpwrappers (localhost, 127.0.0.1) rejection
Nov  2 16:26:36 Visitor sendmail[3159]: vA28QaFS003159: to=pangxieke@126.com, ctladdr=www (503/503), delay=00:00:00, xdelay=00:00:00, mailer=relay, pri=30161, relay=[127.0.0.1] [127.0.0.1], dsn=5.0.0, stat=Service unavailable
```

“Service unavailable”所指什么服务呢？

可能是主机名DNS无法解析

修改主机名 
```
hostname 主机名
```

修改了主机名为服务器的域名后, 重启sendmail服务,重试，还是无法发送。
> 原来原因是因为 /etc/hosts.allow 和 /etc/hosts.deny 设置有问题。
> 把 /etc/hosts.deny 中的 ALL:ALL 注释掉后，可以正常发送。

telent localhost 25可以， 
LINUX默认情况SMTP仅绑定127.0.0.1，因此不能从网络访问，要打开SMTP，如下即可： 
`vi /etc/sendmail.cf `

找到： 
```
# SMTP daemon options 
O DaemonPortOptions=Port=smtp,Addr=127.0.0.1, Name=MTA 
```
添加： 
```
# SMTP daemon options 
O DaemonPortOptions=Port=smtp,Addr=你的IP, Name=MTA 
```

问题可能在于 /etc/hosts.deny 设置了 ALL:ALL 之后，在 /etc/hosts.allow 中没有对sendmail充分的授权。
在 /etc/hosts.allow 中加入以下行：
```
 sendmail : hostname （主机名，不一定需要是域名）
 sendmail : hostip       （主机的ip地址）
```
这样就可以发送了。