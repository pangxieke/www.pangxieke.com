---
title: Go包管理工具Dep及翻墙设置
id: go-dep-install
category: go
date: 2019.9.9 20:00:00
---

Go语言早期没有包管理工具，社区里go的第三方包托管在各个git托管平台。需要用到包时通过go get 命令工具安装，但这个工具没有版本描述性文件，在go的世界里没有“package.json”这种文件。这个给我们带来直接的影响就是依赖放在外网，而且没有版本约束

而且使用Gopath配置，多个项目时，配置比较繁琐。

目前依赖工具有很多，如：glide、godep等

从1.9版本开始，官方发布依赖工具，用来管理和下载工程依赖的工具。

```
dep is a prototype dependency management tool for Go. 
It requires Go 1.9 or newer to compile. 
dep is safe for production use.
```

## 安装

### Mac安装

```
brew install dep
```

### linux

```
curl https://raw.githubusercontent.com/golang/dep/master/install.sh | sh
```

### Windows

```
go get -u github.com/golang/dep/cmd/dep
```

需要编译后，确 `$GOPATH/bin` 添加到环境变量`$PATH`下

### 验证

命令行

```
dep
```

![img](/images/2019/09/1940396-d30feb795633bf10.png)

## Dep使用

### 常用命令

```
dep init
dep ensure
dep ensure -add github.com/pkg/errors
```

`dep init`会产生Gopkg.toml和Gopkg.loc

### 原理

dep不会每次都去下载，会优先在本地仓库搜索，本地仓库未找到即在网络上下载，并添加到本地仓库。
`$GOPATH/pkg/dep/sources`

## 遇到问题

使用dep时遇到的`unable to deduce repository and source type for "golang.org/x/***"`

官方有issue  https://github.com/golang/dep/issues/1322

持问题，需要翻墙设置代理，安装翻墙工具，查看http代理设置

![image-20190909193249855](/images/2019/09/image-20190909193249855.png)

命令行设置代理

```
# linux 使用export；window 使用set
export http_proxy=127.0.0.1:1087
export https_proxy=127.0.0.1:1087
```

Git 也可以设置代理
```
git config --global https.proxy http://127.0.0.1:1087
git config --global https.proxy https://127.0.0.1:1087
```

## 相关文档

dep安装https://www.jianshu.com/p/926b0b43abf5

dep 使用说明 https://cloud.tencent.com/developer/news/221702

dep的安装与配置https://blog.csdn.net/aixiaoyang168/article/details/83142915

dep的安装与使用https://blog.csdn.net/guyan0319/article/details/81588316

netstat -ano   查看所有连接的PID及端口号

https://www.cnblogs.com/welhzh/p/8759760.html

代理https://www.jianshu.com/p/c4d7c5fd3c18

dep官方 issure  https://github.com/golang/dep/issues/1322