---
title: Nginx+Docker在一台服务器上模拟实现负载均衡
date: 2018.4.2 20.44
id: use-docker-and-nginx-implement-load-balancing-on-one-service
---

负载均衡是网站解决高并发、海量数据问题的常用手段。
通过负载均衡调度服务器，将来自浏览器的访问请求分发到应用服务器集群中的任何一台服务器上。
如果有更多的用户，就在集群中加入更多的应用服务器，使应用服务器的负载压力不再成为整个网站的瓶颈。

一般负载均衡常用LVS+keepalived+nginx实现。
现在利用Docker可以十分方便的在一台服务器上简单模拟负载均衡。

## 环境准备
1. 在服务器中搭建一个Nginx环境
2.  docker安装2个新的Nginx环境
3. 新建2个项目路径和文件

vi `/var/www/test/data1/index.html`
```
this is index1
```

vi `/var/www/test/data2/index.html`
```
this is index2
```
通过负载均衡，随机访问这2个项目

## docker安装nginx
```
docker pull nginx
```
启动2个nginx
```
docker run --name nginx-test -d -p 8081:80 -v /var/www/test/data1:/usr/share/nginx/html nginx

docker run --name nginx-test2 -d -p 8082:80 -v /var/www/test/data2:/usr/share/nginx/html nginx
```
查看`docker ps`
![](/images/2018/04/docker-nginx.png)

## 修改原服务器nginx配置文件
`vi nginx.conf`
在`http{}`添加对应代码
```
upstream docker_nginx {
            #ip_hash;
            server 127.0.0.1:8081 weight=1;
            server 127.0.0.1:8082 weight=1;
        }

```
其中`docker_nginx`为自定义名字，在下面配置中需要对应
weight越大，权重越高，被分配的几率越大

`vi vhost/test.conf`
```
server{
        listen 80;
        server_name test.pangxieke.com;
        index index.html index.htm index.php;

        location / {
            proxy_pass http://docker_nginx;
        }

```
`service nginx restart`

## 测试
访问test.pangxieke.com·有时候输出index1，有时候输出index2，证明配置成功！