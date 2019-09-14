---
title: SSH免密码登录失败
tags:
  - ssh免密码
id: 1010
categories:
  - linux
date: 2016-05-31 19:50:34
---

今天尝试设置Linux系统下两台机信任，即SSH下www用户免密码登录。配置好后测试一直失败。切换到root用户设置却可以成功。找好久才发现原因为2台机的.ssh下文件要设置合理的权限，不能太高。

## 原理

将A的ssh公钥告诉B，这A可以免密码登录B。测试中A为192.168.116.129，B为192.168.116.128

## 1、A生成秘钥

```php
ssh-keygen -t rsa #(连续三次回车,即在本地生成了公钥和私钥,不设置密码)
```

此会在自己的家目录~/.ssh目录下生成2个文件

```php
id_rsa #私钥
id_rsa.pub #公钥
```

例如如果想为www用户设置秘钥
需要 su - www 切换为www用户再使用

## 2、A的id_rsa.pub告诉B

在B的对应用户的家目录的~/.ssh/目录下建立authorized_keys文件，文件内容为id_rsa.pub
可以手动vi，然后复制id_rsa.pub内容粘贴到authorized_keys文件或者

```php
scp -p /home/www/.ssh/id_rsa.pub root@192.168.116.128:/home/www/.ssh/authorized_keys
```

## 3、设置权限

一般上面两步做好就可以在A机器上直接ssh登录B机器了。但我尝试一直不成功，后来发现是权限问题。而且是权限给太高
.ssh目录及其下的文件权限都需要设置恰当的权限。
A机 .ssh 目录必须700，id_rsa文件必须600

## 4、测试

```php
ssh www@192.168.116.128
ssh 192.168.116.128 #使用当前用户登录B机
exit # 回到A机
```

[![ssh免密码登录](/images/2016/05/ssh免密码登录.png)](/images/2016/05/ssh免密码登录.png)</pre>