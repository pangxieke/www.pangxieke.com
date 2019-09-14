---
title: centos7安装docker 
tags: docker
id: centos7-yum-install-docker
date: 2018.12.11 19:00:00
---

## 错误
本机CentOS7，安装docker之后出现了“Segmentation Fault or Critical Error encountered. Dumping core and abort”这个错误。

## 原因
安装时使用的 yum install docker，但实际上**CentOS7**上需要安装docker-io

## 解决
卸载后重装`docker-io`
查询已安装服务
```
yum list installed |grep docker
```

卸载
```
yum -y remove docker
```

安装`docker-io`
```
yum install -y docker-io
```

启动
```
service docker start
```
查看版本
```
docker --version
```
测试
```
docker run hello-world
```
