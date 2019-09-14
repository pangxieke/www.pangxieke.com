---
title: Go实现聊天室Demo 
date: 2017.10.16 21:16:00
categories: go
id: 1343
---

## 知识点
   通过Go实现了简要聊天室，主要应用了如下知识：

    1.代码中同时包括了服务器和客户端的内容

    2.客户端包括了两部分内容，一部分是chatSend函数，接受用户的输入；
	另一部分是connect到server，接受相关信息；

    3.server由三个部分组成。
    第一部分就是不停地accept各个客户端；
	第二个就是为每一个客户端创立Handler函数，接受客户端发来的信息；
	第三个就是echoHandler函数，它的作用就是将从某一用户接受过来的信息广播给其他所有的客户端，就是这么简单。
	
## Demo
文件名`chat.go`
```
package main

import (
    "fmt"
    "os"
    "net"
)

func main() {
    if len(os.Args) != 3 {
        fmt.Println("Wrong pare")
        os.Exit(0)
    }

    if os.Args[1] == "server" && len(os.Args) == 3 {
        StartServer(os.Args[2])
    }

    if os.Args[1] == "client" && len(os.Args) == 3 {
        StartClient(os.Args[2])
    }
}

// 检查错误
func checkError(err error, info string)(res bool){
    if(err != nil){
        fmt.Println(info + " " + err.Error())
        return false
    }
    return true
}

// 服务器接收线程
func Handler(conn net.Conn, messages chan string){
    fmt.Println("connection is commected from ...", conn.RemoteAddr().String())

    buf := make([]byte, 1024)
    for{
        length, err := conn.Read(buf)
        if(checkError(err, "Connection") == false){
            conn.Close()
            break
        }
        if length > 0 {
            buf[length] = 0
        }
        reciveStr := string(buf[0: length])
        messages <- reciveStr
    }
}

// 服务器发送线程
func echoHandler(conns *map[string]net.Conn, messages chan string){
    for{
        msg := <- messages
        fmt.Println(msg)

        for key, value := range * conns {
            fmt.Println("connection is conneted from ...", key)
            _, err := value.Write([]byte(msg))
            if(err != nil){
                fmt.Println(err.Error())
                delete(*conns, key)
            }
        }
    }

}

// 启动服务器
func StartServer(port string){
    service := ":" + port
    tcpAddr, err := net.ResolveTCPAddr("tcp4", service)
    checkError(err, "ResolveTCPAddr")

    l, err := net.ListenTCP("tcp", tcpAddr)
    checkError(err, "ListenTCP")
    conns := make(map[string]net.Conn)
    messages := make(chan string, 10)

    //广播服务启动
    go echoHandler(&conns, messages)

    for {
        fmt.Println("Listening ...")
        conn, err := l.Accept()
        checkError(err, "Accept")
        fmt.Println("Accepting ...")

        conns[conn.RemoteAddr().String()] = conn
        //启动新线程
        go Handler(conn, messages)
    }
}

//客户端发送线程
func chatSend(conn net.Conn){
    var input string
    username := conn.LocalAddr().String()

    for{
        fmt.Scanln(&input)
        if input == "/quit"{
            fmt.Println("Bye bye ...")
            conn.Close()
            os.Exit(0)
        }

        lens, err := conn.Write([]byte(username + "Say :::" + input))

        fmt.Println(lens)
        if(err != nil){
            fmt.Println(err.Error())
            conn.Close()
            break
        }
    }
}

//客户端启动程序
func StartClient(tcpaddr string){
    tcpAddr, err := net.ResolveTCPAddr("tcp4", tcpaddr)
    checkError(err, "ResolveTCPAddr")

    conn, err := net.DialTCP("tcp", nil, tcpAddr)
    checkError(err, "DialTCP")

    //启动客户端发送线程
    go chatSend(conn)

    //客户端轮询
    buf := make([]byte, 1024)
    for{
        length, err := conn.Read(buf)
        if(checkError(err, "Connection") == false){
            conn.Close()
            fmt.Println("Server is dead ... Bye bye")
            os.Exit(0)
        }

        fmt.Println(string(buf[0: length]))
    }
}
```

## 测试
启动服务端
```
go run chat.go server 9090
```
返回 
```
Listening ...
```

同时启动客户端
```
go run chat.go client :9090
```
此时服务端 提示
```
connection is commected from ... 127.0.0.1:50756
```

也可以同时再开启一个客户端
实现2个客户端通话

![](/images/2017/10/go_chat.png)