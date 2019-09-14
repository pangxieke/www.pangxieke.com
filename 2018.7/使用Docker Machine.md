---
title: 使用Docker Machine 
id: use-docker-machine
category: linux
date: 2018-7-3 19:00:00
---

![](/images/2018/07/docker-machine.png "docker-machine")

Docker Machine 官方的解释是：

    Docker Machine是一个工具，用来在虚拟主机上安装Docker Engine，并使用 docker-machine命令来管理这些虚拟主机。

官方给的Docker Machine的具体使用场景：

    1. 你目前只有一个老版本的MacOSX（比如10.10.2）或者Windows系统，想在上边运行docker。
    2. 想在远程系统上创建Docker主机。

Docker Engine 主要用来接收和处理docker命令请求的。
Docker Machine则主要用来管理 docker化的 host

## 安装
在 Linux 上的也安装十分简单，从 [官方 GitHub Release](https://github.com/docker/machine/releases) 处直接下载编译好的二进制文件即可。

例如，在 Linux 64 位系统上直接下载对应的二进制包。

```bash
$ sudo curl -L https://github.com/docker/machine/releases/download/v0.13.0/docker-machine-`uname -s`-`uname -m` > /usr/local/bin/docker-machine
$ sudo chmod +x /usr/local/bin/docker-machine
```

完成后，查看版本信息。

```bash
$ docker-machine -v
docker-machine version 0.13.0, build 9ba6da9
```

## 常用命令
```
Commands:
  active		Print which machine is active
  config		Print the connection config for machine
  create		Create a machine
  env			Display the commands to set up the environment for the Docker client
  inspect		Inspect information about a machine
  ip			Get the IP address of a machine
  kill			Kill a machine
  ls			List machines
  provision		Re-provision existing machines
  regenerate-certs	Regenerate TLS Certificates for a machine
  restart		Restart a machine
  rm			Remove a machine
  ssh			Log into or run a command on a machine with SSH.
  scp			Copy files between machines
  mount			Mount or unmount a directory from a machine with SSHFS.
  start			Start a machine
  status		Get the status of a machine
  stop			Stop a machine
  upgrade		Upgrade a machine to the latest version of Docker
  url			Get the URL of a machine
  version		Show the Docker Machine version or a machine docker version
  help			Shows a list of commands or help for one command
  ```
  
 示例
```
# 创建
docker-machine create manager1
# start
docker-machine start manager1
# ssh 进入
docker-machine ssh manager1
```

## 远程主机安装 Docker
通过 docker-machine 命令我们可以轻松的在远程主机上安装 Docker。

前提条件
在使用 docker-machine 进行远程安装前我们需要做一些准备工作：
- 在目标主机上创建一个用户并加入sudo 组
- 为该用户设置 sudo 操作不需要输入密码
- 把本地用户的 ssh public key 添加到目标主机上

1. 比如我们要在远程主机上添加一个名为 nick 的用户并加入 sudo 组：
```
$ sudo adduser nick
$ sudo usermod -a -G sudo nick
```

2. 然后设置 sudo 操作不需要输入密码：
`sudo visudo`把下面一行内容添加到文档的最后并保存文件：
```
nick ALL=(ALL:ALL) NOPASSWD: ALL
```

3. 把本地用户的 ssh public key 添加到目标主机上：
```
ssh-copy-id -i ~/.ssh/id_rsa.pub nick@xxx.xxx.xxx.xxx
```

4. 安装
本地运行命令：
```
$ docker-machine create -d generic \
    --generic-ip-address=xxx.xxx.xxx.xxx \
    --generic-ssh-user=nick \
    --generic-ssh-key ~/.ssh/id_rsa \
    krdevdb
```
    说明：
    create 命令本是要创建虚拟主机并安装 Docker，因为本例中的目标主机已经存在，所以仅安装 Docker。
    -d 是 --driver 的简写形式，主要用来指定使用什么驱动程序来创建目标主机。Docker Machine 支持在云服务器上创建主机，就是靠使用不同的驱动来实现了。本例中使用 generic 就可以了。
    --generic 开头的三个参数主要是指定操作的目标主机和使用的账户。
    最后一个参数 krdevdb 是虚拟机的名称，Docker Machine 会用它来设置目标主机的名称。

5. 检查
```
$ docker-machine ls
```

通过本地的客户端操作远程主机

```
eval $(docker-machine env krdevdb) 
```

参考文档`https://yeasy.gitbooks.io/docker_practice/machine/install.html` 