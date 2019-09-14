---
title: Go获取当前时间戳
date: 2019-5-13
category: go
id: get-current-timestamp-by-go
---

在Go语言上，go语言的time.Now()返回的是当地时区时间，直接用：
```
time.Now().Format("2006-01-02 15:04:05")
```
输出的是当地时区时间`2019-05-13 18:00:17`。

go语言没有全局设置时区，每次输出时间需要调用一个In()函数改变时区：
```
var cstSh, _ = time.LoadLocation("Asia/Shanghai") //上海
fmt.Println("SH : ", time.Now().In(cstSh).Format("2006-01-02 15:04:05"))
```
在windows系统上，没有安装go语言环境的情况下，time.LoadLocation会加载失败。

## time.FixedZone
```
var cstZone = time.FixedZone("CST", 8*3600)       // 东八
fmt.Println("SH : ", time.Now().In(cstZone).Format("2006-01-02 15:04:05"))
```
最好的办法是用time.FixedZone

## 获取当前时间戳
```
fmt.Println(time.Now().Format("2006-01-02 15:04:05"))
//2019-05-13 18:02:22

var cstZone = time.FixedZone("CST", 8*3600)       // 东八
fmt.Println("SH : ", time.Now().In(cstZone).Format("2006-01-02 15:04:05"))
//SH :  2019-05-13 18:02:22

cstZone = time.FixedZone("CST", 0)       // 时区
//上架时间，当日 TODO
currentDay , _:= time.Parse("2006/01/02 15:04:05", time.Now().In(cstZone).Format("2006/01/02 15:04:05"))
currTime :=currentDay.Unix()
fmt.Println(currTime)
//1557741831
```