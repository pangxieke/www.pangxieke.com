---
title: 使用Docker Compose快速搭建Wordpress
id: use-docker-compose-install-wordpress
date: 2018-7-2 20:00:00
category: linux
---

## Compose 介绍
`Compose` 项目是 Docker 官方的开源项目，负责实现对 Docker 容器集群的快速编排。

在日常工作中，经常会碰到需要多个容器相互配合来完成某项任务的情况。例如要实现一个 Web 项目，除了 Web服务容器本身，往往还需要再加上后端的数据库服务容器，甚至还包括负载均衡容器等。

`Compose` 恰好满足了这样的需求。它允许用户通过一个单独的 `docker-compose.yml` 模板文件（YAML 格式）来定义一组相关联的应用容器为一个项目。

`Compose` 中有两个重要的概念：

* 服务 (`service`)：一个应用的容器，实际上可以包括若干运行相同镜像的容器实例。

* 项目 (`project`)：由一组关联的应用容器组成的一个完整业务单元，在 `docker-compose.yml` 文件中定义。

## 安装

在 Linux 上的也安装十分简单，从 [官方 GitHub Release](https://github.com/docker/compose/releases) 处直接下载编译好的二进制文件即可。

例如，在 Linux 64 位系统上直接下载对应的二进制包。

```bash
$ sudo curl -L https://github.com/docker/compose/releases/download/1.17.1/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
```

查看
```bash
$ docker-compose --version

docker-compose version 1.21.1, build 5a3f1a3
```

## 常用命令
可以通过`--help`查看
```
docker-compose --help
```
```
build              Build or rebuild services
  bundle             Generate a Docker bundle from the Compose file
  config             Validate and view the Compose file
  create             Create services
  down               Stop and remove containers, networks, images, and volumes
  events             Receive real time events from containers
  exec               Execute a command in a running container
  help               Get help on a command
  images             List images
  kill               Kill containers
  logs               View output from containers
  pause              Pause services
  port               Print the public port for a port binding
  ps                 List containers
  pull               Pull service images
  push               Push service images
  restart            Restart services
  rm                 Remove stopped containers
  run                Run a one-off command
  scale              Set number of containers for a service
  start              Start services
  stop               Stop services
  top                Display the running processes
  unpause            Unpause services
  up                 Create and start containers
  version            Show the Docker-Compose version information
  ```
  
  **build**
  构建项目中的服务容器
  ```
  docker-compose build [options] [SERVICE...]
  ```
  
  **up**
  尝试自动完成包括构建镜像，（重新）创建服务，启动服务，并关联服务相关容器的一系列操作。
  
  **down**
  此命令将会停止 up 命令所启动的容器，并移除网络
  
  **scale**
  设置指定服务运行的容器个数
  
  ## Compose 模板文件
  默认的模板文件名称为 docker-compose.yml，格式为 YAML 格式。
  ```
  version: "3"

services:
  webapp:
    image: examples/web
    ports:
      - "80:80"
    volumes:
      - "/data"
  ```
  注意每个服务都必须通过 image 指令指定镜像或 build 指令（需要 Dockerfile）等来自动构建生成镜像。
  
 ## 创建Wordpress环境
`Compose` 可以很便捷的让 `Wordpress` 运行在一个独立的环境中。

### 创建空文件夹

假设新建一个名为 `wordpress` 的文件夹，然后进入这个文件夹。

### 创建 `docker-compose.yml` 文件

`docker-compose.yml` 文件将开启一个 `wordpress` 服务和一个独立的 `MySQL` 实例：

```yaml
version: "3"
services:

   db:
     image: mysql:5.7
     volumes:
       - db_data:/var/lib/mysql
     restart: always
     environment:
       MYSQL_ROOT_PASSWORD: somewordpress
       MYSQL_DATABASE: wordpress
       MYSQL_USER: wordpress
       MYSQL_PASSWORD: wordpress

   wordpress:
     depends_on:
       - db
     image: wordpress:latest
     ports:
       - "8000:80"
     restart: always
     environment:
       WORDPRESS_DB_HOST: db:3306
       WORDPRESS_DB_USER: wordpress
       WORDPRESS_DB_PASSWORD: wordpress
volumes:
    db_data:
```

### 构建并运行项目

运行 `docker-compose up -d` Compose 就会拉取镜像再创建我们所需要的镜像，然后启动 `wordpress` 和数据库容器。 接着浏览器访问 `127.0.0.1:8000` 端口就能看到 `WordPress` 安装界面了。

### 查看
`docker ps`查看
```
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
f1b9b964773e        wordpress:latest    "docker-entrypoint.s…"   36 seconds ago      Up 35 seconds       0.0.0.0:8000->80/tcp   wordpress_wordpress_1
d7a724606e25        mysql:5.7           "docker-entrypoint.s…"   37 seconds ago      Up 36 seconds       3306/tcp               wordpress_db_1
```

看到可以通过8000端口测试
浏览器访问`http://localhost:8000`, 可以看到wordpress的安装界面，这样就快速的搭建了一个Wordpress的独立运行环境