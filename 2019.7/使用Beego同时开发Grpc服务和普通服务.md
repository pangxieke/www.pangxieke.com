---
title: 使用Beego同时开发Grpc服务和普通服务
id: beego-grpc-server
category: go
date: 2019.7.31 19:30:00
---

Beego开发Grpc服务，同时提供Grpc和普通http服务

在Beego项目中，一般都是直接开发应用，监听一个端口，使用一种协议。

当如果我们想使用Grpc，放在同一个代码库中时，这时候需要监听2个服务。

## 示例

```

package main

import (
	"context"
	"github.com/astaxie/beego"
	"github.com/astaxie/beego/logs"
	"google.golang.org/grpc"
	"google.golang.org/grpc/reflection"
	pb "grpc_demo/test"
	"net"

)

func main(){
	end := make(chan bool, 1)
	go RunGrpc()

	beego.Run()
	<-end
}

func RunGrpc(){
	list, err := net.Listen("tcp", ":8081")
	logs.Info("grpc:8081")
	if err != nil {
		logs.Info("grpc err=%s", err)
	}

	s := grpc.NewServer()
	pb.RegisterGreeterServer(s, &HelloServer{})
	reflection.Register(s)
	if err := s.Serve(list); err != nil {
		logs.Info("failed to serve: %v", err)
	}
}

type HelloServer struct{}

// SayHello implements helloworld.GreeterServer
func (s *HelloServer) SayHello(ctx context.Context, in *pb.HelloRequest) (*pb.HelloReply, error) {

	return &pb.HelloReply{Message: "Hello " + in.Name}, nil
}
```

启动后，可以看到同时监听2个端口

```
2019/07/30 19:11:22.506 [I]  grpc:8081
2019/07/30 19:11:22.533 [I]  http server Running on http://:8080
```



