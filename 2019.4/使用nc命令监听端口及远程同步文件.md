---
title: 使用nc命令监听端口及远程同步文件
id: command-nc-listen-in-server
date: 2019-4-10 20:30:00
category: linux
---

nc是netcat的简写，有着网络界的瑞士军刀美誉。因为它短小精悍、功能实用，被设计为一个简单、可靠的网络工具。

## nc的作用
- 实现任意TCP/UDP端口的侦听，nc可以作为server以TCP或UDP方式侦听指定端口
- 端口的扫描，nc可以作为client发起TCP或UDP连接
- 机器之间传输文件
- 机器之间网络测速   

## 常用参数
### -l
用于指定nc将处于侦听模式。指定该参数，则意味着nc被当作server，侦听并接受连接，而非向其它地址发起连接。

### -p `<port>`
老版本的nc可能需要在端口号前加-p参数

### -s
指定发送数据的源IP地址，适用于多网卡机 

### -u
 指定nc使用UDP协议，默认为TCP
### -v
输出交互或出错信息，新手调试时尤为有用
### -w
超时秒数，后面跟数字 
### -z
表示zero，表示扫描时不发送任何数据

## 常用用法

### 启动tcp端口监听
```
nc -l 1234
```
启动端口监听，监听1234端口

测试
```
telnet localhost 1234
```
响应
```
Trying 127.0.0.1...
Connected to localhost.
Escape character is '^]'.
```

### 端口探测
nc命令作为客户端工具进行端口探测
```
nc -vz -w 2 localhost 1234
```
-v可视化，-z扫描时不发送数据，-w超时几秒，后面跟数字
响应
```
found 0 associations
found 1 connections:
     1:	flags=82<CONNECTED,PREFERRED>
	outif lo0
	src 127.0.0.1 port 52255
	dst 127.0.0.1 port 1234
	rank info not available
	TCP aux info available

Connection to localhost port 1234 [tcp/search-agent] succeeded!
```

### 连续端口扫描
启动3个端口，&使其后台运行
```
nc -l 1234 &
nc -l 1235 &
nc -l 1236 &
```
扫描1234 到1237端口
```
nc -vzw2 localhost 1234-1237
```
响应
1234，1235，1236成功，1237失败

### 启动udp监听
启动一个udp的端口监听
```
nc -ul 1230
```
udp的端口无法在客户端使用telnet去测试，我们可以使用nc命令去扫描
因为telnet是运行于tcp协议的
测试
```
nc -vuz localhost 1230
```
u表示udp端口，v表示可视化输出，z表示扫描时不发送数据

### 实用nc传输文件和目录
#### 传输文件
A 服务器接受，启动接受，侦听1234端口，数据写入log.txt文件
```
nc -l 1234 > log.txt
```

B服务器发送
```
nc localhost 1234 < index.html
```
B服务器发送完毕，A自动退出侦听。

#### 传输目录
需要结合其他命令，如tar
管道后面必须是-

A服务器启动接收
```
nc -l 1234 | tar xfvz -
```

B服务器发送
```
tar cfz - ./* | nc localhost 1234
```