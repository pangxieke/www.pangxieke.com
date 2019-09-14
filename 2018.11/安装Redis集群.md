---
title: 安装Redis集群
tags: liunx
id: install-redis-cluster
date: 2018.11.16 22:52:00
---

## Redis集群
Redis 集群由多个运行在集群模式（cluster mode）下的 Redis 实例组成， 实例的集群模式需要通过配置来开启， 开启集群模式的实例将可以使用集群特有的功能和命令。

开启集群一般需要修改如下配置
```
port 7000
cluster-enabled yes
cluster-config-file nodes.conf
cluster-node-timeout 5000
appendonly yes
```
cluster-enabled 选项用于开实例的集群模式。
cluster-conf-file 选项则设定了保存节点配置文件的路径， 默认值为 nodes.conf 。无须人为修改， 它由 Redis 集群在启动时创建。

**集群正常运作至少需要三个主节点**， 不过在刚开始试用集群功能时， 强烈建议使用六个节点

## 安装Redis
Mac上安装使用brew
```
brew install redis
```
安装后路径为`/usr/local/opt/redis`

```
cd /usr/local/opt/redis
mkdir cluster-test

cp /usr/local/etc/redis.conf /usr/local/opt/redis/cluster-test/7000.conf

vi 7000.conf
# 修改port 为 7000
# 修改cluster-config-file 7000.conf
# 开启 cluster-enabled yes
```

使用可执行文件 redis-server 启动redis
```
/usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/6379.conf
```

同样方式创建修改 `7001.conf` `7002.conf` `7003.conf` `7004.conf` `7005.conf`

## 创建集群
```
./redis-trib.rb create --replicas 1 127.0.0.1:7000 127.0.0.1:7001 \
127.0.0.1:7002 127.0.0.1:7003 127.0.0.1:7004 127.0.0.1:7005
```
**网上教程都是使用, Redis 集群命令行工具 redis-trib,实际上此方法已不可用**

**使用redis-cli替代**
`redis/bin`下有`redis-cli`
命令如下，需要先启动redis服务后再使用
```
 /usr/local/opt/redis/bin/redis-cli --cluster create 127.0.0.1:7000 127.0.0.1:7001 127.0.0.1:7002 127.0.0.1:7003 127.0.0.1:7004 127.0.0.1:7005 --cluster-replicas 1
 ```
命令的意义如下：
- 给 create ， 这表示我们希望创建一个新的集群。
- 选项 --replicas 1 表示我们希望为集群中的每个主节点创建一个从节点。
- 之后跟着的其他参数则是实例的地址列表， 我们希望程序使用这些地址所指示的实例来创建新集群。

## 启动集群
使用`nohup`后台运行
```
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7000.conf &
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7001.conf &
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7002.conf &
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7003.conf &
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7004.conf &
nohup /usr/local/opt/redis/bin/redis-server /usr/local/opt/redis/cluster-test/7005.conf &
```

启动集群
```
/usr/local/opt/redis/bin/redis-cli --cluster create 127.0.0.1:7000 127.0.0.1:7001 127.0.0.1:7002 127.0.0.1:7003 127.0.0.1:7004 127.0.0.1:7005 --cluster-replicas 1
```

## 可能错误
可能会提示
```
Node 127.0.0.1:7001 is not empty. Either the node already knows other nodes (check with CLUSTER NODES) or contains some key in database 0
```

需求清除dump.rdb备份文件

    dump.rdb是由Redis服务器自动生成的。
    默认情况下，每隔一段时间redis服务器程序会自动对数据库做一次遍历，把内存快照写在一个叫做“dump.rdb”的文件里，这个持久化机制叫做SNAPSHOT。
    有了SNAPSHOT后，如果服务器宕机，重新启动redis服务器程序时redis会自动加载dump.rdb，将数据库状态恢复到上一次做SNAPSHOT时的状态。

在`redis.conf`中有相关配置项
```
# The filename where to dump the DB
dbfilename dump.rdb

# The working directory. （备份文件目录）
#
# The DB will be written inside this directory, with the filename specified
# above using the 'dbfilename' configuration directive.
#
# The Append Only File will also be created inside this directory.
#
# Note that you must specify a directory here, not a file name.（注意你这里指定的必须是目录，不是文件名。）
dir /usr/local/var/db/redis/
```
所以需要清除`/usr/local/var/db/redis/`目录下的文件