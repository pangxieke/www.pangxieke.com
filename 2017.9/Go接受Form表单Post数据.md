---
title: Go接受Form表单数据
id: 1341
categories: go
date: 2017.9.29 21:12:00
description: 使用Go接受Form表单数据，展示Form表单，使用Post提交数据
---

用了大半天时间，尝试Go接收Form表单数据，终于成功。
使用的package及知识点主要为`net/http`, `htmp/template`

## 代码
```
package main

import (
	"net/http"
	"fmt"
	"html/template"
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
		http.HandleFunc("/login", login) 
	}
}

func login(w http.ResponseWriter, r *http.Request) {
    fmt.Println("method:", r.Method) //获取请求的方法
    r.ParseForm()
    
    if r.Method == "GET" {
        t, _ := template.ParseFiles("login.gtpl")
        t.Execute(w, nil)
    } else {
        //请求的是登陆数据，那么执行登陆的逻辑判断
        fmt.Println("username:", r.Form["username"])
        fmt.Println("pssword:", r.Form["password"])
    }
}

```

## 登录模板`login.gtpl`
```
<html>
    <head>
    <title>login</title>
    </head>
    <body>
        <form action="/login" method="post">
            用户名:<input type="text" name="username"><br>
            密码:<input type="password" name="password">
            <input type="submit" value="登陆">
        </form>
    </body>
</html>
```
