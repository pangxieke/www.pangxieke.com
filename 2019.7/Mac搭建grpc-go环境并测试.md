---
title: Mac搭建grpc-go环境并测试
id: mac-install-grpc-of-go
category: go
date: 2019-7-30 20:30:00
---

## GRPC简介
GRPC 一开始由 google 开发，是一款语言中立、平台中立、开源的远程过程调用(RPC)系统。

在 GRPC 里客户端应用可以像调用本地对象一样直接调用另一台不同的机器上服务端应用的方法，使得您能够更容易地创建分布式应用和服务。
与许多 RPC 系统类似，gRPC 也是基于以下理念：定义一个服务，指定其能够被远程调用的方法（包含参数和返回类型）。
在服务端实现这个接口，并运行一个 gRPC 服务器来处理客户端调用。在客户端拥有一个存根能够像服务端一样的方法。

GRPC使用ProtoBuf来定义服务，ProtoBuf是由Google开发的一种数据序列化协议（类似于XML、JSON、hessian）。ProtoBuf能够将数据进行序列化，并广泛应用在数据存储、通信协议等方面。压缩和传输效率高，语法简单，表达力强。

## 安装protoc
protoc是Protobuf 的编译器
```
brew tap grpc/grpc
brew install --with-plugins grpc
```
安装成功后查看
```
protoc --version
```
显示版本
```
libprotoc 3.7.1
```
可以查看到路径为`/usr/local/bin/protoc`

也可以使用源码编译安装
[https://github.com/protocolbuffers/protobuf](https://github.com/protocolbuffers/protobuf)

## 安装protoc-gen-go
将proto文件编译成go代码时，需要protoc-gen-go插件
go get 命令可以借助代码管理工具通过远程拉取或更新代码包及其依赖包，并自动完成编译和安装.
```
// gRPC运行时接口编解码支持库
go get -u github.com/golang/protobuf/proto
// 从 Proto文件(gRPC接口描述文件) 生成 go文件 的编译器插件
go get -u github.com/golang/protobuf/protoc-gen-go
```
此时查看GOBIN目录，可以看到`protoc-gen-go`执行文件

我试过多次，无法自动编译。只能手动编译。
手动编译后，copy文件到GOBIN下，或者其他`bin`文件下
```
go get  github.com/golang/protobuf/protoc-gen-go
cd $GOPATH/src/github.com/golang/protobuf/protoc-gen-go
go install
copy protoc-gen-go $GOBIN/
# 或者放在 /usr/local/bin/下。在$GOBIN下，始终无法生效
```

## 编译proto文件为go文件
新建`helloworld.proto`文件
```
syntax = "proto3";

option go_package = "test";

package helloworld;

// The greeting service definition.
service Greeter {
  // Sends a greeting
  rpc SayHello (HelloRequest) returns (HelloReply) {}
}

// The request message containing the user's name.
message HelloRequest {
  string name = 1;
}

// The response message containing the greetings
message HelloReply {
  string message = 1;
}
```

 ```
 mkdir test
 // protoc --go_out=plugins=grpc:{输出目录}  {proto文件}
protoc --go_out=plugins=grpc:./test/ helloworld.proto 
 ```
 此时在`test`目录下会生成`helloworld.pb.go`文件
  
  ### 编译可能错误
  `--go_out: protoc-gen-go: Plugin failed with status code 1.`
  此为未找到`protoc-gen-go`文件，重新编译。
  我将文件放在`$GOPATH/bin`下没有生效，后来放在`/usr/local/bin`才能正常起作用
  
  ## GRPC服务端
  服务端代码`server.go`
```
package main

import (
	"context"
	"log"
	"net"

	"google.golang.org/grpc"
	pb "./test"
	"google.golang.org/grpc/reflection"
)

const (
	port = ":50051"
)

// server is used to implement helloworld.GreeterServer.
type server struct{}

// SayHello implements helloworld.GreeterServer
func (s *server) SayHello(ctx context.Context, in *pb.HelloRequest) (*pb.HelloReply, error) {
	return &pb.HelloReply{Message: "Hello " + in.Name}, nil
}

func main() {
	lis, err := net.Listen("tcp", port)
	if err != nil {
		log.Fatalf("failed to listen: %v", err)
	}
	s := grpc.NewServer()
	pb.RegisterGreeterServer(s, &server{})

	// Register reflection service on gRPC server.
	reflection.Register(s)
	if err := s.Serve(lis); err != nil {
		log.Fatalf("failed to serve: %v", err)
	}
}
  ```
  
## 客户端
客户端`client.go`
```
package main

import (
	"context"
	"log"
	"os"
	"time"

	pb "./test"
	"google.golang.org/grpc"
)

const (
	address     = "localhost:50051"
	defaultName = "pangxieke"
)

func main() {
	// Set up a connection to the server.
	conn, err := grpc.Dial(address, grpc.WithInsecure())
	if err != nil {
		log.Fatalf("did not connect: %v", err)
	}
	defer conn.Close()
	c := pb.NewGreeterClient(conn)

	// Contact the server and print out its response.
	name := defaultName
	if len(os.Args) > 1 {
		name = os.Args[1]
	}
	ctx, cancel := context.WithTimeout(context.Background(), time.Second)
	defer cancel()
	r, err := c.SayHello(ctx, &pb.HelloRequest{Name: name})
	if err != nil {
		log.Fatalf("could not greet: %v", err)
	}
	log.Printf("Greeting: %s", r.Message)
}

  ```
  
## 服务测试
启动服务端
```
go run server.go
```
在另一个终端启动客户端
```
go run client.go
// 2019/07/30 10:54:28 Greeting: Hello pangxieke
```
  
## 可能出现问题
### implement错误
  ```
  cannot use &server literal (type *server) as type test.GreeterServer in argument to test.RegisterGreeterServer:
        *server does not implement test.GreeterServer (wrong type for SayHello method)
                have SayHello("golang.org/x/net/context".Context, *test.HelloRequest) (*test.HelloReply, error)
                want SayHello("context".Context, *test.HelloRequest) (*test.HelloReply, error)
```
为继承`interface`错误，注意`interface`中类型与server中类型是否一致，如`content`包的路径

  ```
  context "golang.org/x/net/context"
  ```
  还是
  ```
  context "context"
  ```
  
### go get google.golang.org/grpc失败
官方安装命令：go get google.golang.org/grpc
然而一般都会失败报错：
```
https fetch failed: Get https://google.golang.org/grpc?go-get=1: net/http: TLS handshake timeout
```
这个代码已经转移到github上面了，但是代码里面的包依赖还是没有修改
手动从github下载
```
git clone https://github.com/grpc/grpc-go.git $GOPATH/src/google.golang.org/grpc
git clone https://github.com/golang/net.git $GOPATH/src/golang.org/x/net
git clone https://github.com/golang/text.git $GOPATH/src/golang.org/x/text
go get -u -v github.com/golang/protobuf/{proto,protoc-gen-go}
git clone https://github.com/google/go-genproto.git $GOPATH/src/google.golang.org/genproto
cd $GOPATH/src/
go install google.golang.org/grpc
```
 