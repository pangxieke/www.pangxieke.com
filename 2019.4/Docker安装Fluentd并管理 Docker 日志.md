---
title: Docker安装Fluentd并管理 Docker 日志
id: docker-logging-fluentd
date: 2019-4-2 20:10:00
category: linux
---

## 问题
Docker每个容器运行一个单独的进程，进程的输出，一般保存在容器中，或者挂载在主机的磁盘上。
这样会存在一些问题：
- 日志无限制的增长。Docker 以 JSON 消息记录每一行日志，这能引起文件增长过快以及超过主机上的磁盘空间，因为它不会自动轮转。
- docker logs 命令返回在每次它运行的时候返回所有的日志记录。任何长时间运行的进程产生的日志都是冗长的，这会导致仔细检查非常困难。
- 日志位于容器 /var/log 下或者宿主机磁盘空间上。

## Docker 日志选项
有几种方法可以处理当前的 Docker 的日志：
### 在容器内收集 
除了正在运行的应用程序之外，每个容器设置一个日志收集进程。baseimage-docker 使用 runit 连同 syslog 作为一个示例。
### 在容器外收集 
一个单独的收集 agent 运行在主机上，容器有一个从该主机挂载的卷，它们把日志记录在那里。
### 在单独的容器中收集
这是一个在主机上运行收集 agent 的细微变化。该收集 agent 也是运行在一个容器中并且该容器的卷是使用 docker run 的 volumes-from 选项被绑定给任何应用程序容器。这篇 Docker 和 logstash 有一个这种方法的示例。

这些方法可以工作，但是也有一些缺点。如果收集被执行在容器里面，这时每个容器运行多个进程会导致资源浪费。
如果收集使用 volumes 运行在容器外面，你依然需要确保你的应用程序日志记录进这些 volumes 而不是 stdout/stderr。对于所有应用来说，这或许不可能。最终，容器运行依然有容器 JSON 日志文件，它也将无限增长。

## Docker 使用 Fluentd
容器外收集的另外一个变化就是通过一个中央化的 agent 来处理，不用绑定 volumes 到容器。这个方法是直接供工作于容器在主机上的 JSON 日志文件。
fluentd 是一个开源的数据收集器，它原生就支持 JSON 格式，因此你可以在主机上运行一个单独的 fluentd 实例并配置它来 tail 每个容器的 JSON 文件。

## Docker 安装 Fluentd
本机/tmp下创建`fluentd.conf`
```
<source>
@type forward
</source>

<match *>
@type stdout
</match>
```
 启动 Fluentd容器
```
docker run -d \
-p 24224:24224  -v /tmp:/fluentd/etc -e FLUENTD_CONF=fluentd.conf \
fluent/fluentd
```
返回容器ID `2e6d50875a07`
查看日志
```
docker logs 2e6d50875a07
```

## 测试  
启动测试服务Nginx
```
docker run -d --log-driver fluentd --log-opt fluentd-address=localhost:24224 --log-opt tag="nginx-test" --log-opt fluentd-async-connect --name nginx-test -p 9080:80 nginx
```
Curl测试
```
curl -X GET http://localhost:9080
```
再次查看fluentd容器日志
```
docker logs 2e6d50875a07
```
查看到日志
```
2019-04-02 02:45:01.000000000 +0000 nginx-test: {"container_id":"b182441e76a9142e963499980a84cc6de3626c72d1cd4bc5fb53f040ecee645c","container_name":"/nginx-test","source":"stdout","log":"172.17.0.1 - - [02/Apr/2019:02:45:01 +0000] \"GET / HTTP/1.1\" 200 612 \"-\" \"curl/7.63.0\" \"-\""}
```
官方示例[https://docs.fluentd.org/v0.12/articles/install-by-docker](https://docs.fluentd.org/v0.12/articles/install-by-docker)

## 进一步优化
实际的日志内容可以被发送到一个 elasticsearch 集群，并使用 kibana 或 graylog2 查看

## 相关文档
官方文档[https://docs.fluentd.org](https://docs.fluentd.org)
[日志收集工具Fluentd使用总结](http://www.imekaku.com/2016/09/26/fluentd-conclusion/#fluentd)
[https://docs.docker.com/config/containers/logging/fluentd/](https://docs.docker.com/config/containers/logging/fluentd/)