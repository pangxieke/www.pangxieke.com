---
title: 'Apache与Nginx的优缺点比较 '
id: 940
categories:
  - linux
date: 2016-03-28 23:36:09
tags: nginx
---

Apache与Nginx的优缺点比较
## 1、优缺点对比
nginx相对于apache的优点：

*   轻量级，同样起web 服务，比apache 占用更少的内存及资源
*   抗并发，nginx 处理请求是异步非阻塞的，而apache 则是阻塞型的，在高并发下nginx 能保持低资源低消耗高性能
*   高度模块化的设计，编写模块相对简单 社区活跃，各种高性能模块出品迅速啊

apache 相对于nginx 的优点：

*   rewrite ，比nginx 的rewrite 强大
*   模块超多，基本想到的都可以找到
*   少bug ，nginx 的bug 相对较多

*   超稳定
存在就是理由，一般来说，需要性能的web 服务，用nginx 。如果不需要性能只求稳定，那就apache 吧。后者的各种功能模块实现得比前者，例如ssl 的模块就比前者好，可配置项多。这里要注意一点，epoll(freebsd 上是 kqueue )网络IO 模型是nginx 处理性能高的根本理由，但并不是所有的情况下都是epoll 大获全胜的，如果本身提供静态服务的就只有寥寥几个文件，apache 的select 模型或许比epoll 更高性能。当然，这只是根据网络IO 模型的原理作的一个假设，真正的应用还是需要实测了再说的。

## 2. Nginx更高并发
作为 Web 服务器：相比 Apache，Nginx 使用更少的资源，支持更多的并发连接，体现更高的效率，这点使 Nginx 尤其受到虚拟主机提供商的欢迎。在高连接并发的情况下，Nginx是Apache服务器不错的替代品: Nginx在美国是做虚拟主机生意的老板们经常选择的软件平台之一. 能够支持高达 50,000 个并发连接数的响应, 感谢Nginx为我们选择了 epoll and kqueue 作为开发模型.

Nginx作为负载均衡服务器: Nginx 既可以在内部直接支持 Rails 和 PHP 程序对外进行服务, 也可以支持作为 HTTP代理 服务器对外进行服务. Nginx采用C进行编写, 不论是系统资源开销还是CPU使用效率都比 Perlbal 要好很多.

作为邮件代理服务器: Nginx 同时也是一个非常优秀的邮件代理服务器（最早开发这个产品的目的之一也是作为邮件代理服务器）, Last.fm 描述了成功并且美妙的使用经验.
Nginx 是一个安装非常的简单 , 配置文件非常简洁（还能够支持perl语法）, Bugs 非常少的服务器: Nginx 启动特别容易, 并且几乎可以做到7*24不间断运行，即使运行数个月也不需要重新启动. 你还能够不间断服务的情况下进行软件版本的升级 .

## 3. Nginx 配置简洁, Apache 复杂
Nginx 静态处理性能比 Apache 高 3倍以上
Apache 对 PHP 支持比较简单，Nginx 需要配合其他后端用
Apache 的组件比 Nginx 多
现在 Nginx 才是 Web 服务器的首选

## 4. 多进程
最核心的区别在于apache是同步多进程模型，一个连接对应一个进程；nginx是异步的，多个连接（万级别）可以对应一个进程

## 5. Nginx 处理静态文件好
nginx处理静态文件好,耗费内存少.但无疑apache仍然是目前的主流,有很多丰富的特性.所以还需要搭配着来.当然如果能确定nginx就适合需求,那么使用nginx会是更经济的方式.

## 6. Nginx 负载高
从个人过往的使用情况来看，nginx的负载能力比apache高很多。最新的服务器也改用nginx了。而且nginx改完配置能-t测试一下配置有没有问题，apache重启的时候发现配置出错了，会很崩溃，改的时候都会非常小心翼翼现在看有好多集群站，前端nginx抗并发，后端apache集群，配合的也不错。

## 其他
1. nginx处理动态请求是鸡肋，一般动态请求要apache去做，nginx只适合静态和反向。

2. nginx是很不错的前端服务器，负载性能很好，在老奔上开nginx，用webbench模拟10000个静态文件请求毫不吃力。apache对php等语言的支持很好，此外apache有强大的支持网路，发展时间相对nginx更久，bug少但是apache有先天不支持多核心处理负载鸡肋的缺点，建议使用nginx做前端，后端用apache。大型网站建议用nginx自代的集群功能

3. Nginx优于apache的主要两点：1.Nginx本身就是一个反向代理服务器 2.Nginx支持7层负载均衡；其他的当然，Nginx可能会比apache支持更高的并发，但是根据NetCraft的统计，2011年4月的统计数据，Apache依然占有62.71%，而Nginx是7.35%，因此总得来说，Aapche依然是大部分公司的首先，因为其成熟的技术和开发社区已经也是非常不错的性能。

4. 你对web server的需求决定你的选择。大部分情况下nginx都优于APACHE，比如说静态文件处理、PHP-CGI的支持、反向代理功能、前端Cache、维持连接等等。在Apache+PHP（prefork）模式下，如果PHP处理慢或者前端压力很大的情况下，很容易出现Apache进程数飙升，从而拒绝服务的现象。

5. 可以看一下nginx lua模块：https://github.com/chaoslaw...apache比nginx多的模块，可直接用lua实现apache是最流行的，why？大多数人懒得更新到nginx或者学新事物

6. 对于nginx，我喜欢它配置文件写的很简洁，正则配置让很多事情变得简单运行效率高，占用资源少，代理功能强大，很适合做前端响应服务器

7. Apache在处理动态有优势，Nginx并发性比较好，CPU内存占用低，如果rewrite频繁，那还是Apache吧