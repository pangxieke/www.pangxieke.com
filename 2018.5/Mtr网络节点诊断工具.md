---
title: Mtr网络节点诊断工具 
id: linux-network-tool-mtr
category: linux
date: 2018.5.23 21:39:00
---

一般判断网络连通性用ping 和tracert.
ping的话可以来判断丢包率，tracert可以用来跟踪路由.

在Linux中有一个更好的网络连通性判断工具，它可以结合ping nslookup tracert 来判断网络的相关特性,这个命令就是mtr

官网: [http://www.bitwizard.nl/mtr/](http://www.bitwizard.nl/mtr/)
Github: [https://github.com/traviscross/mtr](https://github.com/traviscross/mtr)

## 官方说明
    mtr combines the functionality of the 'traceroute' and 'ping' programs in a single network diagnostic tool.

	As mtr starts, it investigates the network connection between the host mtr runs on and a user-specified destination host.  
	After it determines the address of each network hop between the machines, 
	it sends a sequence of ICMP ECHO requests to each one to determine the quality of the link to each machine.  
	As it does this, it prints running statistics about each machine.

## 使用
```
mtr baidu.com
```
可以查看到不同的网络节点，有时候网络不通，能够找到阻塞点。

## 安装
### Linux 安装
```
yum -y install mtr
```

### MacOS安装
```
brew install mtr
```
提示已经安装成功
运行mtr出现提示
```
-bash: mtr: command not found
```
解决方法：
```
alias mtr=/usr/local/sbin/mtr
```
然后运行还是会出现问题`mtr: unable to get raw sockets.`
需要添加权限
```
sudo chown root mtr
sudo chmod u+s mtr
```

### 源码编译安装
[https://github.com/traviscross/mtr](https://github.com/traviscross/mtr)

```
git clone https://github.com/traviscross/mtr.git

cd mtr

./bootstrap.sh && ./configure && make

# 测试是否成功
sudo ./mtr <host>

make install
```

### Windows安装
Windows安装需要[**Cygwin**]( https://cygwin.com/install.html)