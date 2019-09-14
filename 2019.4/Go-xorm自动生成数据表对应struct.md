---
title: Go-xorm自动生成数据表对应struct
category: go
id: generate-struct-of-table-by-xorm
date: 2019-4-11 21:00:00
---

使用 golang 操作数据库的同学都会遇到一个问题 —— 根据数据表结构创建对应的 struct 模型。
因为 golang 的使用首字母控制可见范围，我们经常要设计 struct 字段名和数据库字段名的对应关系。
这是一个非常繁琐的过程。今天，记录一种自动生成代码的方法 —— xorm 工具。

## 关于 xorm
xorm是一个简单而强大的Go语言ORM库. 通过它可以使数据库操作非常简便。
官网[http://www.xorm.io/](http://www.xorm.io/)
电子书[XORM操作指南](https://www.kancloud.cn/kancloud/xorm-manual-zh-cn/56013)

### 常用命令
xorm 是一组数据库操作命令的工具，包含如下命令：
```
reverse 反转一个数据库结构，生成代码
shell 通用的数据库操作客户端，可对数据库结构和数据操作
dump Dump数据库中所有结构和数据到标准输出
source 从标注输入中执行SQL文件
driver 列出所有支持的数据库驱动
```

## 编译xorm工具
下载数据库驱动
```
go get github.com/go-sql-driver/mysql  //MyMysql
go get github.com/ziutek/mymysql/godrv  //MyMysql
go get github.com/lib/pq  //Postgres
go get github.com/mattn/go-sqlite3  //SQLite
```
下载Xorm工具
```
go get github.com/go-xorm/cmd/xorm
```
编译工具
到GOPATH\src\github.com\go-xorm\cmd\xorm 目录下，执行
```
go build
```
当前目录产生`xorm`文件，window下产生`xorm.exe`文件

### 可能错误提示
```
package cloud.google.com/go/civil: unrecognized import path "cloud.google.com/go/civil" 
```
src下，首先创建目录：`cloud.google.com/go/civil`
cd到这里去执行git clone https://github.com/googleapis/google-cloud-go
把civil下的.go文件cp 到cloud.google.com/go/civil下面
```
mkdir -p cloud.google.com/go/civil
cd cloud.google.com/go/civil
git clone https://github.com/googleapis/google-cloud-go
//mv google-cloud-go/civil/.* cloud.google.com/go/civil
```

## 使用xorm
### xorm命令
查询帮助信息
```
./xorm help reverse
```
得到帮助信息
```
usage: xorm reverse [-s] driverName datasourceName tmplPath [generatedPath] [tableFilterReg]

according database's tables and columns to generate codes for Go, C++ and etc.

    -s                Generated one go file for every table
    driverName        Database driver name, now supported four: mysql mymysql sqlite3 postgres
    datasourceName    Database connection uri, for detail infomation please visit driver's project page
    tmplPath          Template dir for generated. the default templates dir has provide 1 template
    generatedPath     This parameter is optional, if blank, the default value is model, then will
                      generated all codes in model dir
    tableFilterReg    Table name filter regexp
```
参数 -s 表示为每张表创建一个单独文件
接下来的参数依次是：驱动，数据源，模板目录（在源码的 /cmd/xorm/templates/goxorm 可根据需求定制），生成目录，表格过滤条件。

### 生成struct
```
./xorm reverse mysql root:password@$"@"tcp(host:3306)"/yzm_test?charset=utf8 templates/goxorm
```

目录参数省略，会在当前目录建立一个 `models` 目录, 该目录有生成的go文件
内容如下
```
package model

type TestModel struct {
    Id            int    `json:"id" xorm:"not null pk autoincr INT(11)"`
    VpsName       string `json:"vps_name" xorm:"VARCHAR(30)"`
    VpsIp         string `json:"vps_ip" xorm:"CHAR(15)"`
    VpsPrivateIp  string `json:"vps_private_ip" xorm:"CHAR(50)"`
    VpsCpu        int    `json:"vps_cpu" xorm:"INT(11)"`
    VpsMem        int    `json:"vps_mem" xorm:"INT(11)"`
    VpsDisk       int    `json:"vps_disk" xorm:"INT(11)"`
    VpsStatus     string `json:"vps_status" xorm:"VARCHAR(255)"`
    LastHeartTime int    `json:"last_heart_time" xorm:"INT(11)"`
    CreateTime    int    `json:"create_time" xorm:"INT(11)"`
    LastTime      int    `json:"last_time" xorm:"INT(11)"`
}
```

### 可能错误
```
reverse.go:196 default addr for network '（127.0.0.1:3306）' unknown
```
原因是数据库连接没有指定tcp协议
`checked on the go-mysql-driver source code, on file dsn.go:86, the error only occurred when the network type is ""`
```
<username>:<password>@<network-type>(<host>:<port>)/<dbname>
```
相应文档[https://stackoverflow.com/questions/52808454/beego-orm-mysql-default-addr-for-network-unknown](https://stackoverflow.com/questions/52808454/beego-orm-mysql-default-addr-for-network-unknown)
