---
title: Docker搭建Orange Api网关
id: install-orange-api-gateway-by-docker
date: 2018.12.21 19:00:00
tags: docker
---

## Orange介绍
orange 是一个基于 openresty 的API Gateway，提供API及自定义规则的监控和管理，如访问统计、流量切分、API重定向、API鉴权、WEB防火墙等功能。
Orange可用来替代前置机中广泛使用的Nginx/OpenResty， 在应用服务上无痛前置一个功能丰富的网关系统。

![doc](/images/2018/12/1545385905917.jpg)

## 相关文档
[Orange官方安装文档](http://orange.sumory.com/install/)
[使用文档](http://orange.sumory.com/docs/)

[Docker安装Orange](https://hub.docker.com/r/syhily/orange)
[Orange Github](https://github.com/sumory/orange )

## Docker安装Orange

### 安装Mysql
```
docker run --name orange-database -e MYSQL_ROOT_PASSWORD=your_root_pwd -p 3306:3306 mysql:5.7
```

进入Mysql容器
```
docker exec -it orange-database /bin/bash
```
创建Orange DB
```
CREATE DATABASE orange;
CREATE USER 'orange'@'%' IDENTIFIED BY 'orange';
GRANT ALL PRIVILEGES ON orange.* TO 'orange'@'%';
```

### 安装Orange
```
docker run -d --name orange \
    --link orange-database:orange-database \
    -p 7777:7777 \
    -p 8888:8888 \
    -p 9999:9999 \
    --security-opt seccomp:unconfined \
    -e ORANGE_DATABASE=orange \
    -e ORANGE_HOST=orange-database \
    -e ORANGE_PORT=3306 \
    -e ORANGE_USER=orange \
    -e ORANGE_PWD=orange \
    syhily/orange
```
注意：` -p 8888:8888 \` 官方使用的是` -p 8888:80 \`

## 测试
后台管理
`http://localhost:9999`
Api service
`http://localhost:7777/`
实际使用转发Url
`http://localhost:8888`

### 后台配置
访问`http://localhost:9999`

![Dashboard](/images/2018/12/orange.png "orange")

创建一个分流规则

![divide](/images/2018/12/orange-divide_1.png "orange-divide")
配置规则

![divide-detail](/images/2018/12/orange-divide-detail.png "orange-divide-detail")

### 分流测试
访问`http://localhost:8888`
![test](/images/2018/12/orange-test.png "orange-test")
返回404，是正常的
`http://localhost:8888/api/test`

![url-test](/images/2018/12//orange-test2.png "orange-test2")
此url Rewrite到`http://192.168.5.76:8082/api/test`
显示502，证明规则生效了，只是8082的服务未生效。
本机开启一个8082端口的服务，就能够分流到对应的服务了。
注：`192.168.5.76`是本机Ip，这里不能使用`127.0.0.1`

![](/images/2018/12/1545386063816.jpg)

这样就实习了一个分流。
