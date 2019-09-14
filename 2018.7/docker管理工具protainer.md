---
title: docker管理工具protainer 
id: use-protainer-manage-docker
date: 2018-7-4 20:00:00
category: linux
---

## 介绍
Portainer（基于 Go） 是一个轻量级的Web管理界面，可让您轻松管理 `Docker` 主机 或 `Swarm` 集群。

Portainer 的使用意图是简单部署。 它包含可以在任何 Docker 引擎上运行的单个容器（Docker for Linux 和 Docker for Windows）。

Portainer 允许您管理 Docker 容器、image、volume、network 等。 它与独立的 Docker 引擎和 Docker Swarm 兼容。

[官网 https://portainer.io/)](https://portainer.io/)
[GitHub https://github.com/portainer/portainer]( https://github.com/portainer/portainer)
[Doc https://portainer.readthedocs.io/en/stable/](https://portainer.readthedocs.io/en/stable/)

## 部署

1. 方法一
```
$ docker volume create portainer_data
$ docker run -d -p 9000:9000 -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer
```



2. 方法二
使用Portainer管理Swarm集群，在swarm中部署
```
$ docker volume create portainer_data
$ docker service create \
--name portainer \
--publish 9000:9000 \
--replicas=1 \
--constraint 'node.role == manager' \
--mount type=bind,src=//var/run/docker.sock,dst=/var/run/docker.sock \
--mount type=volume,src=portainer_data,dst=/data \
portainer/portainer \
-H unix:///var/run/docker.sock
```

3. 方法三
通过docker compose部署
docker-composer.yml内容
```
version: '2'

services:
  portainer:
    image: portainer/portainer
    ports:
      - "9000:9000"
    command: -H unix:///var/run/docker.sock
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - portainer_data:/data

volumes:
  portainer_data:
  ```

## 测试
浏览器访问`localhost:9000`测试
![](/images/2018/07/portainer-docker-dashboard.jpg "portainer-docker-dashboard")