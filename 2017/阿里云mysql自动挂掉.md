---
title: 阿里云mysql自动挂掉
tags:
  - Mysql
id: 997
categories:
  - mysql
date: 2016-04-27 00:12:11
---

使用阿里云服务器，经常发现mysql服务会自动挂掉，重启能够解决，因此也没有特别重视。朋友建议我查看mysql日志。

今天查看下，发现了如下错误。
[![mysql错误](/images/2016/04/mysql错误.png)](/images/2016/04/mysql错误.png)

有时候重启也失败，怀疑是内存不足。
现在看到`cannot allocate the memory for the buffer pool`。明显是内存不足。使用free命令查看，内存只有67M

## 解决方法：

### 1、在 /etc/mysql/my.cnf 的 mysqld 下增加下面一句：

innodb_buffer_pool_size = 64M
还要设置一下swap分区，因为我的vps是没有swap分区的，通过fdisk -l 和 1mount 看不到swap的信息，需要手动添加一下。

### 2、 添加swap分区的步骤：

```php
dd if=/dev/zero of=/swapfile bs=1M count=1024
mkswap /swapfile
swapon /swapfile
添加这行： /swapfile swap swap defaults 0 0 到 /etc/fstab
```
说明：创建一个有 1024 个块的区，每块 1M，总的来说就是创建一个 1024M 的区；接下来将该区设为 swap 分区；再接着启用 swap 分区。服务器启动时自动挂载刚刚创建的 swap 分区。
目前已经设置了swap分区，并重启了mysql，后续观察一下看看还会不会出现吧。

参考：[阿里云vps上mysql挂掉的解决办法](http://hongjiang.info/aliyun-vps-mysql-aborting)
[http://stackoverflow.com/questions/10284532/amazon-ec2-mysql-aborting-start-because-innodb-mmap-x-bytes-failed-errno-12](http://stackoverflow.com/questions/10284532/amazon-ec2-mysql-aborting-start-because-innodb-mmap-x-bytes-failed-errno-12)