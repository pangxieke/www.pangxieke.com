---
title: 阿里云Docker 容器时间与主机时间不一致
category: linux
id: docker-date-time-error
date: 2019.7.23 20:24:00
---


今天使用阿里云Docker容器，发现日志文件时间相差8个小时。

通过date命令查看时间

查看主机时间

```
2019年 7月23日 星期二 20时15分40秒 CST
```

查看容器时间

```
Tue Jul 23 12:15:40 UTC 2019
```

可以发现，他们相隔了8小时。

CST应该是指（China Shanghai Time，东八区时间） 
UTC应该是指（Coordinated Universal Time，标准时间） 

## 解决方案

### 方案一：共享主机的localtime

挂载localtime文件到容器内  ，保证两者所采用的时区是一致的

```
-v /etc/localtime:/etc/localtime:ro \
-v /etc/timezone:/etc/timezone:ro \
```

### **方案二：复制主机的localtime** 

```
docker cp /etc/localtime:【容器ID或者NAME】/etc/localtime
```

在完成后，再通过date命令进行查看当前时间。

但是，在容器中运行的程序的时间不一定能更新过来，比如在容器运行的MySQL服务，在更新时间后，通过sql查看MySQL的时间

```
select now() from dual;
```

这时候必须要重启mysql服务或者重启Docker容器，mysql才能读取到更改过后的时间。

### 方案三：创建自定义的dockerfile

创建dockerfile文件，其实没有什么内容，就是自定义了该镜像的时间格式及时区。

```
FROM redis
 
FROM tomcat
 
ENV CATALINA_HOME /usr/local/tomcat
 
#设置时区
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
  && echo 'Asia/Shanghai' >/etc/timezone \
```

保存后，利用docker build命令生成镜像使用即可。

原文[Docker 解决容器时间与主机时间不一致的问题三种解决方案](https://www.jb51.net/article/99906.htm)

