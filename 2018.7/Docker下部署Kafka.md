---
title: Docker下部署Kafka 
date: 2018.7.21 20:00:00
category: linux
id: use-docker-install-kafka
---

## 介绍
### Kafka
Kafka是一种高吞吐量的分布式发布订阅消息系统。

Kafka系统的角色：

- Broker ：一台kafka服务器就是一个broker。一个集群由多个broker组成。一个broker可以容纳多个topic

- topic： 可以理解为一个MQ消息队列的名字

- Partition：为了实现扩展性，一个非常大的topic可以分布到多个broker（即服务器）上，一个topic可以分为多个partition，每个partition是一个有序的队列。partition中的每条消息 都会被分配一个有序的id（offset）。kafka只保证按一个partition中的顺序将消息发给consumer，不保证一个topic的整体 （多个partition间）的顺序。也就是说，一个topic在集群中可以有多个partition，那么分区的策略是什么？(消息发送到哪个分区上，有两种基本的策略，一是采用Key Hash算法，一是采用Round Robin算法)

![](/images/2018/07/kafka.png)

### ZooKeeper

ZooKeeper是一个分布式的，开放源码的分布式应用程序协调服务，是Google的Chubby一个开源的实现，是Hadoop和Hbase的重要组件。它是一个为分布式应用提供一致性服务的软件，提供的功能包括：配置维护、域名服务、分布式同步、组服务等。

•1) Producer端使用zookeeper用来"发现"broker列表,以及和Topic下每个partition leader建立socket连接并发送消息.

•2) Broker端使用zookeeper用来注册broker信息,以及监测partitionleader存活性.

•3) Consumer端使用zookeeper用来注册consumer信息,其中包括consumer消费的partition列表等,同时也用来发现broker列表,并和partition leader建立socket连接,并获取消息.


## 安装
启动zookeeper
```
docker run -d --name zookeeper -p 2181 -t wurstmeister/zookeeper
```
启动kafka
```
docker run -d --name kafka --publish 9092:9092 --link zookeeper --env KAFKA_ZOOKEEPER_CONNECT=zookeeper:2181 --env KAFKA_ADVERTISED_HOST_NAME=localhost --env KAFKA_ADVERTISED_PORT=9092 --volume /tmp/kafka:/tmp/kafka wurstmeister/kafka:latest
```

查看状态
```
docker ps
```

## 测试
执行docker ps，找到kafka的CONTAINER ID，进入容器内部：
```
docker exec -it ${CONTAINER ID} /bin/bash 
```
进入kafka默认目录
```
cd opt/kafka_2.11-0.10.1.1/ 
```

创建一个主题：
```
bin/kafka-topics.sh --create --zookeeper zookeeper:2181 --replication-factor 1 --partitions 1 --topic mykafka

```

运行一个消息生产者，指定topic为刚刚创建的主题
```
bin/kafka-console-producer.sh --broker-list localhost:9092 --topic mykafka
```

运行一个消费者，指定同样的主题
```
bin/kafka-console-consumer.sh --zookeeper zookeeper:2181 --topic mykafka --from-beginning
```