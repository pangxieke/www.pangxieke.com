---
title: docker push私有仓库错误
date: 2018.11.21 21:48:00
id: docker-push-error
tag: docker
---

## docker push 错误
上传镜像报错：server gave HTTP response to HTTPS client

想使用 127.0.0.1:5000 作为仓库地址，比如想让本网段的其他主机也能把镜像推送到私有仓库。你就得把例如 192.168.199.100:5000 这样的内网地址作为私有仓库地址，这时你会发现无法成功推送镜像。

这是因为 Docker 默认不允许非 HTTPS 方式推送镜像。我们可以通过 Docker 的配置选项来取消这个限制，或者查看下一节配置能够通过 HTTPS 访问的私有仓库。

## Linux系统修改
Ubuntu 16.04+, Debian 8+, centos 7等使用 systemd 的系统，请在 /etc/docker/daemon.json 中写入如下内容（如果文件不存在请新建该文件）
```
{
  "registry-mirror": [
    "https://registry.docker-cn.com"
  ],
  "insecure-registries": [
    "192.168.199.100:5000"
  ]
}
```

## mac下修改
通过docker客户端修改preferences/Daeman 下Advanced

![mac ](/images/2018/11/docker-push.png)

```
{
  "insecure-registries" : [
    "registry.docker.cloudream.com:22000"
  ]
}
```
