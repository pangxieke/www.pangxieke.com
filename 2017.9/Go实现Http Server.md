---
title: Go实现Http Server
date: 2017.9.29
category: go
id: 1340
---

## 前言
常用的Web Server是Nginx, Apache等。Go可以几行代码就可以创建Web Serve。

这里使用net.http包,十分简单就可以实现一个建议的http服务器。
从简单到复杂，分成3个版本。
参考官方[net/http](https://golang.org/pkg/net/http/)文档
![](/images/2017/09/golang.jpg)

## 版本1
文件`server.go`
```
package main

import (
	"net/http"
	"io"
)
	
func main() {
	http.HandleFunc("/", HelloServer)
	http.ListenAndServe(":5000", nil)
}

func HelloServer(w http.ResponseWriter, req * http.Request) {
	io.WriteString(w, "hello, world!")
}
```
运行 `go run server.go`
测试访问`localhost:5000`时，返回`hello, world`


## 版本2
对代码进行简单修改
```
package main

import (
	"net/http"
	"io"
)
	
func main() {
	http.HandleFunc("/", HelloServer)
	http.HandleFunc("/test", TestServer)
	http.ListenAndServe(":5000", nil)
}

func HelloServer(w http.ResponseWriter, req * http.Request) {
	io.WriteString(w, "hello, world!")
}

func TestServer(w http.ResponseWriter, req * http.Request) {
	io.WriteString(w, "Url: " + req.URL.Path)
}
```
访问`localhost:5000`时，返回`hello, world`
访问`localhost:5000/test`时，返回访问路径`Url:/test`

## 版本3
增加路由判断
```
package main

import (
	"net/http"
)
	
func main() {
	http.HandleFunc("/", handle)
	http.ListenAndServe(":5000", nil)
}

func handle(w http.ResponseWriter, req * http.Request) {
	w.Header().Set("Content-Type", "text/plain")
	
	url := req.URL.Path
	if url == "/" {
		w.Write([]byte("hello, world!.\n"))
	}else{
		w.Write([]byte("Url:" + url + ".\n"))
	}
}
```
通过`handle`方法，统一接受所有请求，针对不同Url，返回不同数据