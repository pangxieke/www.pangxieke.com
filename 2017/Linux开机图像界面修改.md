---
title: Linux开机图像界面修改
id: 653
categories:
  - linux
date: 2015-04-11 15:04:41
tags:
---

刚装了linux虚拟机，开机启动时图像界面，非常慢，而且耗资源。所以想着开机能够直接进入命令行模式

解放方法：

找到inittab文件，将默认的运行级别由5改为3

```php
cd /etc

vi inittab
```

如图:

[![开机图像界面](/images/2015/04/开机图像界面.png)](/images/2015/04/开机图像界面.png)

此时，将5修改为3，提示错误，原因是权限不够

```php
W10: Warning: Changing a readonly file
E325: ATTENTION
Found a swap file by the name &quot;/var/tmp/inittab.swp&quot;
owned by: tang dated: Fri Apr 10 23:51:59 2015
file name: /etc/inittab
modified: YES
user name: tang host name: localhost.localdomain
process ID: 12594 (still running)
While opening file &quot;inittab&quot;
dated: Fri Apr 10 23:08:02 2015

(1) Another program may be editing the same file.
If this is the case, be careful not to end up with two
different instances of the same file when making changes.
Quit, or continue with caution.
```

切换登录用户，切换到root权限，再次修改，修改后重启！

```php
su root #切换到root

#再次修改

vi inittab #将启动由5改为3

reboot #修改完后重启，成功

```
