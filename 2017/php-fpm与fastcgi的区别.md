---
title: php-fpm与fastcgi的区别
id: 995
categories:
  - linux
date: 2016-04-26 23:29:15
tags:
---

## 1、CGI 的作用

CGI 是为了保证 web server 传递过来的数据是标准格式的，方便 CGI 程序的编写者。

web server（比如说 nginx）只是内容的分发者。

如果请求 /index.html，那么 web server 会去文件系统中找到这个文件，发送给浏览器，这里分发的是静态数据。
如果现在请求的是 /index.php，根据配置文件，nginx 知道这个不是静态文件，需要去找 PHP 解析器来处理，那么他会把这个请求简单处理后交给 PHP 解析器。Nginx 会传哪些数据给 PHP 解析器呢？url、查询字符串、POST 数据、HTTP header 等等，CGI 就是规定要传哪些数据、以什么样的格式传递给后方处理这个请求的协议。
当 web server 收到 /index.php 这个请求后，会启动对应的 CGI 程序，这里就是 PHP 的解析器。接下来 PHP 解析器会解析 php.ini 文件，初始化执行环境，然后处理请求，再以规定 CGI 规定的格式返回处理后的结果，退出进程。web server 再把结果返回给浏览器。

CGI 是个协议，跟进程没关系。

## 2、fastCgi

fastCgi 是用来提高 CGI 程序性能的。

那么 CGI 程序的性能问题在哪呢？"PHP 解析器会解析 php.ini 文件，初始化执行环境"，就是这里了。标准的 CGI 对每个请求都会执行这些步骤，所以处理每个请求的时间会比较长。

那么 fastCgi 是怎么做的呢？首先，fastCgi 会先启一个 master，解析配置文件，初始化执行环境，然后再启动多个 worker。当请求过来时，master 会传递给一个 worker，然后立即可以接受下一个请求。这样就避免了重复的劳动，效率自然是高。而且当 worker 不够用时，master 可以根据配置预先启动几个 worker 等着；当然空闲 worker 太多时，也会停掉一些，这样就提高了性能，也节约了资源。这就是 fastCgi 对进程的管理。

## 3、PHP-FPM

PHP-FPM 是一个实现了 FastCgi 的程序，被 PHP 官方收录。

PHP 的解释器是 php-cgi，它只是个 CGI 程序，只能解析请求，返回结果，不会进程管理。所以就出现了一些能够调度 php-cgi 进程的程序，比如说由 lighthttpd 分离出来的 spawn-fcgi。PHP-FPM 也是这么个东西，在长时间的发展后，逐渐得到了大家的认可，也越来越流行。

## 4、其它

php-fpm是php内核的一个补丁？

以前是对的。因为最开始的时候php-fpm没有包含在PHP内核里面，要使用这个功能，需要找到与源码版本相同的php-fpm对内核打补丁，然后再编译。后来PHP内核集成了PHP-FPM之后就方便多了，使用--enalbe-fpm这个编译参数即可。

&nbsp;

修改了php.ini配置文件后，没办法平滑重启，所以就诞生了php-fpm？

是的，修改php.ini之后，php-cgi进程的确是没办法平滑重启的。php-fpm对此的处理机制是新的worker用新的配置，已经存在的worker处理完手上的活就可以歇着了，通过这种机制来平滑过度。

原文：[FastCgi 与 PHP-FPM 之间的区别](http://www.golaravel.com/post/difference-between-fastcgi-and-php-fpm/ "FastCgi 与 PHP-FPM 之间的区别")