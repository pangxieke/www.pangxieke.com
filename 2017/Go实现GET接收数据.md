---
title: Go实现GET接收数据
tags:
  - go
id: 1311
categories:
  - go
date: 2017-09-03 10:07:44
---

## 目的

go语言实现处理表单输入, 接受Get参数, 打印获取到数据

## 实现

主要使用net/http包实现,使用net/http包中ListenAndServe
```php
func ListenAndServe(addr string, handler Handler) error
```

监听TCP网络地址addr然后调用具有handler的Serve去处理连接请求.通常情况下Handler是nil,使用默认的DefaultServeMux

## Code

```php
package main

import(
	"fmt"
	"net/http"
	"log"
	"strings"
)

func main() {
	fmt.Println("hello, world")

    http.HandleFunc("/", sayHelloName)
	err := http.ListenAndServe(":9090", nil)
	if err != nil{
		log.Fatal("ListenAndServer:", err)
	}
}

func sayHelloName(w http.ResponseWriter, r *http.Request){
	//解析url传递的参数
	r.ParseForm()
	//服务端打印信息
	fmt.Println(r.Form)
	fmt.Println("method:", r.Method)
	fmt.Println("path", r.URL.Path)
	fmt.Println("Scheme", r.URL.Scheme)
	fmt.Println(r.Form["url_long"])

	fmt.Fprintf(w, "hello, world ")

	if(r.Form != nil){

		for k, v := range r.Form {
			fmt.Println("key: ", k)
			//join()数组拼接成字符串
			fmt.Println("val: ", strings.Join(v, ""))
			fmt.Fprintf(w,  strings.Join(v, ""))
		}
	}
}
```

## 测试

访问http://localhost:9090/?username=pangxieke
返回
```php
hello, world pangxieke
```