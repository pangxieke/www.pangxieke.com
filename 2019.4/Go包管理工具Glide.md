---
title: Go包管理工具Glide
category: go
date: 2019-4-12 20:00:00
id: go-package-of-glide
---

## Go get
Go 语言原生包管理一般用`go get`
但get 子命令管理依赖有很多大缺陷：
- 能拉取源码的平台很有限，绝大多数依赖的是 github.com
- 不能区分版本，以至于令开发者以最后一项包名作为版本划分
- 依赖 列表/关系 无法持久化到本地，需要找出所有依赖包然后一个个 go get
- 只能依赖本地全局仓库（GOPATH/GOROOT），无法将库放置于局部仓库（$PROJECT_HOME/vendor）

## Glide
Go有很多包管理工具，如godep、govendor、glide、gvt、gopack等。
Glide目前比较受关注。 几大主要功能：
- 持久化依赖列表至配置文件中，包括依赖版本（支持范围限定）以及私人仓库等
- 持久化关系树至 lock 文件中（类似于 yarn 和 cargo），以重复拉取相同版本依赖
- 兼容 go get 所支持的版本控制系统：Git, Bzr, HG, and SVN
- 支持 GO15VENDOREXPERIMENT 特性，使得不同项目可以依赖相同项目的不同版本
- 可以导入其他工具配置，例如： Godep, GPM, Gom, and GB

## 安装
```
go get github.com/Masterminds/glide

go install github.com/Masterminds/glide
```
验证
```
glide
```
响应
```
NAME:
   glide - Vendor Package Management for your Go projects.

   Each project should have a 'glide.yaml' file in the project directory. Files
   look something like this:

       package: github.com/Masterminds/glide
       imports:
       - package: github.com/Masterminds/cookoo
         version: 1.1.0
       - package: github.com/kylelemons/go-gypsy
         subpackages:
         - yaml

   For more details on the 'glide.yaml' files see the documentation at
   https://glide.sh/docs/glide.yaml


USAGE:
   glide [global options] command [command options] [arguments...]

```

## 常用命令
初始化
```
glide init
```

安装依赖
```
glide install
```

升级版本
```
glide up
```

下载依赖
```
glide get
```

参考文档[https://studygolang.com/articles/10453?fr=email==mark](https://studygolang.com/articles/10453?fr=email==mark)