---
title: 远程登陆linux服务器
id: 659
categories:
  - linux
date: 2015-04-11 18:53:11
tags:
---

1.在linux终端远程登陆linux服务器是非常容易的，一般linux都默认安装了ssh服务。

如果的服务器用户名是abc(也可以是root)，只需要在终端输入：

```php
 ssh abc@IP（服务器）

```

然后电脑会提示输入密码就登录服务器了。


2.如果想在系统之间传送文件使用scp指令完成。

例如：从服务器下载文件到本机中。

```php
scp abc@IP(服务器)：/home/abc/xxxx(文件)  /xxxx(本机目录)
```

之后提示输入abc用户的密码；



3.从本机上传文件到服务器：
```php
scp /xxxx(本机目录)  abc@IP(服务器)：/home/abc/xxxx(文件)
```



4.退出远程登录

```php
exit
```

linux之间互相连接，比window连接linux方便很多