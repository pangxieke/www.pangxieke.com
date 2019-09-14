---
title: Go并发访问变量，异常处理
id: goroutine-variable-concurrency-safety
category: go
date: 2019-7-5 20:00:00
---

## 竟险
竟险（竞争条件、Race Condition）是指多个协程（goroutine）同时访问共享数据，其结果取决于指令执行顺序的情况。
具体归纳为当有一个变量， 有一个 goroutine 会对它进行写操作， 其他 goroutine 对它进行读操作。 是否需要对这个变量进行加锁保护。

考虑如下售票程序。该程序模拟两个售票窗口，一个执行购票，一个执行退票。
```
package main

import (
	"fmt"
	"time"
)

var ticket = 200 // 总票数

// 退票
func refund() {
	ticket += 1
}

// 购票
func buy() {
	ticket -= 1
}

func main() {
	go buy()    // 购票协程
	go refund() // 退票协程

	time.Sleep(time.Microsecond * 1) //等待上面两个协程结束
	fmt.Println(ticket)              // 输出结果是什么？
}
```
考虑到一共200张票，买了一张，卖了一张，应该还是剩余200张票。事实却不总是这样
```
199
```
或者
```
200
```
多次运行结果不一定相同

## 异常原因
在计算机看来语句A和语句B并不是一条不可分割的语句，而是两条语句：
```
A1: … = tickCount - 1 
A2: tickCount = … 
B1: … = tickCount + 1 
B2: tickCount = …
```
它们的实际执行顺序有如下四种可能：

- A1->A2->B1->B2 结果为200
- B1->B2->A1->A2 结果为200
- B1->A1->A2->B2 结果为201
- A1->B1->B2->A2 结果为199

第三种和第四种执行顺序产生了意想不到的结果。原因在于两个协程同时访问并修改了共享变量（tickCount），而语句之间的顺序无法保证，导致意外的情况发生，这便是竟险。

我们可以添加调试语句测试
```
package main

import (
	"fmt"
	"time"
)

var ticket=200

func refund(){
	fmt.Println("refund before:", ticket)
	ticket += 1
	fmt.Println("refund after:", ticket)
}

func buy(){
	fmt.Println("buy before:", ticket)
	ticket -= 1
	fmt.Println("buy after:", ticket)
}

func main(){
	go buy()
	go refund()

	time.Sleep(time.Microsecond*1)
	fmt.Println("result:",ticket)
}
```
就可以发现代码运行的规律

## 解决方案
竟险显然不是我们想要的结果。那么如何规避竟险呢?有三种方式：
1. 禁止修改共享变量。
2. 限制在同一个协程中访问共享变量。
3. 利用互斥。

下面分别来看看这三种方式。

## 方法一：禁止修改共享变量
可以通过禁止修改共享变量来达到规避竟险的目的
看如下代码
```
package main

var config = map[string]string{}

func loadConfig(key string) string { /*...*/ }

// 惰性加载
func getConfig(key string) string {
    value, ok := config[key]
    if !ok {
        value = loadConfig(key)
        config[key] = value
    }
    return value
}

func main() {

    go func() {
        user := getConfig("userName") // A 修改共享变量的值，发生竟险
        // ...
    }()

    go func() {
        address := getConfig("address") // B 修改共享变量的值，发生竟险
        // ...
    }()

    // ...
}
```
注意该例中`getConfig()`为惰性加载，也就是在需要加载时再加载，这样便在语句A和语句B中发生了竟险，两条语句同时修改了共享变量config。
如果修改为提前加载所有配置，则可规避竟险
```
package main

// 提前加载所有配置
var config = map[string]string{
    "userName": loadConfig("userName"),
    "address":  loadConfig("address"),
}

func loadConfig(key string) string { /*...*/ }

func getConfig(key string) string {
    return config[key]
}

func main() {

    go func() {
        user := getConfig("userName")  // 访问共享变量，但不修改其值，不发生竟险
        // ...
    }()

    go func() {
        address := getConfig("address")  // 访问共享变量，但不修改其值，不发生竟险
        // ...
    }()

    // ...
}
```
种方式仅仅可以用于协程不需要修改共享变量的情况。这显然满足不了我们的所有需求。在很多情况下协程必须修改共享变量。

## 方案二：限制在同一个协程中访问共享变量
将共享变量的读写放到一个 goroutine 中，其它 goroutine 通过 channel 进行读写操作
```
package main

import (
    "fmt"
    "sync"
)

var tickCount = 200            // 总票数
var ch = make(chan int, 10)    // 用来控制tickCount的同步，10表示模拟10个售/退票窗口
var n sync.WaitGroup           // 用来等待购票和售票动作完成
var done = make(chan struct{}) // 用来等待监听协程退出

// 购票
func buy() {
    ch <- -1
}

// 退票
func refund() {
    ch <- 1
}

func main() {

    // 监听协程
    go func() {
        for amount := range ch {
            tickCount += amount
            n.Done() // 每次调用Done()，n的计数减1
        }
        done <- struct{}{}  // 监听线程结束，发送消息
    }()

    n.Add(2)    // 因为要执行两个动作，所以使n的计数加2
    go buy()    // 购票协程
    go refund() // 退票协程

    n.Wait() // 等待购票和退票动作完成
             // Wait()会一直等待，直到n的计数为0

    close(ch) // 关闭管道

    <-done // 等待监听线程结束

    fmt.Println("tick count:", tickCount)
}
```
## 方案三：利用互斥锁
使用sync包中提供的互斥锁sync.Mutex。sync.Mutex是一个结构体，提供了Lock和Unlock两个方法，Lock用来锁定，Unlock用来解锁。 利用互斥锁，上面的程序变得更简单了：
```
package main

import (
    "fmt"
    "sync"
)

var (
    tickCount = 200 // 总票数
    mu        sync.Mutex  // 互斥锁
    n         sync.WaitGroup
)

// 购票
func buy() {
    defer n.Done()  // 计数减1
    mu.Lock()
    defer mu.Unlock()  // 用defer保证函数返回时解锁
    tickCount += 1
}

// 退票
func refund() {
    defer n.Done()  // 计数减1
    mu.Lock()
    defer mu.Unlock()  // 用defer保证函数返回时解锁
    tickCount -= 1
}

func main() {

    n.Add(2)    // 有两个动作，所以计数加2
    go buy()    // 购票协程
    go refund() // 退票协程

    n.Wait() // 等待购票和退票动作完成
             // Wait一直阻塞，直到n的计数为0返回

    fmt.Println("tick count:", tickCount)
}
```

## 官方go内存模型
golang 官网上对于 go 内存模型的建议：
```
Advice
Programs that modify data being simultaneously accessed by multiple goroutines must serialize such access.

To serialize access, protect the data with channel operations or other synchronization primitives such as those in the sync and sync/atomic packages.

If you must read the rest of this document to understand the behavior of your program, you are being too clever. Don't be clever.
```
go语言编程中， 当有多个goroutine并发操作同一个变量时，除非是全都是只读操作， 否则就得【加锁】或者【使用channel】来保证并发安全。 不要觉得加锁麻烦，但是它能保证并发安全。

 一次加锁的耗时差不多是在几十纳秒， 而一次网络IO都是在毫秒级别以上的。
特别是在现在云计算时代， 大部分人一辈子都遇不到因为加锁成为性能瓶颈的应用场景。
  
参考文档[https://blog.csdn.net/u011304970/article/details/72672805](https://blog.csdn.net/u011304970/article/details/72672805)
参考文档[谈谈go语言编程的并发安全](https://studygolang.com/articles/2400)