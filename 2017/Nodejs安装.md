---
title: Nodejs安装v8.7版本
id: 1197
categories:
  - share
date: 2016-12-27 19:43:02
tags:
---

[![20161227193803](/images/2016/12/20161227193803.png)](/images/2016/12/20161227193803.png)

下载网址：[http://nodejs.cn/download/](http://nodejs.cn/download/)
Github地址：[https://github.com/nodejs/node](https://github.com/nodejs/node)

## 下载Linux编译好的文件

此安装的是网友维护的版本

```php
wget https://npm.taobao.org/mirrors/node/v8.7.0/node-v8.7.0-linux-x64.tar.gz

tar -xzvf node-v8.7.0-linux-x64.tar.gz

mv node-v8.7.0-linux-x64 /usr/local/node

# 建立node软连
ln -s /usr/local/node/bin/node /usr/bin/node
建立npm软连
ln -s /usr/local/node/bin/npm /usr/bin/npm
#测试
node -v
npm -v
```

## yum安装
此是安装的v0.10版本，是官方版本

```php
curl --silent --location https://rpm.nodesource.com/setup | bash -
yum -y install nodejs
```

## Window安装

下载[https://nodejs.org/dist/v6.2.0/node-v6.2.0-x64.msi](https://nodejs.org/dist/v6.2.0/node-v6.2.0-x64.msi)，安装。
安装完后打开cmd命令行工具
```php
node -v
```

## 简单服务Demo

```php
# file name demo.js
var http = require('http');
http.createServer(function(request, response){
    response.writeHead(200,{'Content-Type':'text-html;chaset=utf-8'});
    if(request.url != '/favicon.ico'){
        console.log('access');
        response.write('hello, world');
        response.end('');
    }

}).listen(8000);
console.log('server running at http://localhost:8000');
```
运行
```php
node demo.js
```

使用浏览器访问http://localhost:8000，浏览器显示hello, world，同时后台显示access。
此实例能显示nodejs的异步应用十分方便。