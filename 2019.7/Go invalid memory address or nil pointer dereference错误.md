---
title: Go invalid memory address or nil pointer dereference错误
id: go-invalid-memory-address-or-nil-pointer-dereference
tags: go
date: 2019-7-11 20:40:00
category: go
---

Go 指针声明后赋值，出现 panic: runtime error: invalid memory address or nil pointer dereference
这种是内存地址错误。

首先我们要了解指针，指针地址
在 Go 中 * 代表取指针地址中存的值，& 代表取一个值的地址
对于指针，我们一定要明白指针储存的是一个值的地址，但本身这个指针也需要地址来储存

## 错误示例
```
package main

import "fmt"

func main() {
	var i *int

	fmt.Println(&i, i)

	*i = 1
	fmt.Println(&i, i, *i)
}
```
错误提示
```
0xc00009a008 <nil>

panic: runtime error: invalid memory address or nil pointer dereference
[signal SIGSEGV: segmentation violation code=0x1 addr=0x0 pc=0x1092f9c]
```

初始化指针，指针变量的地址为`0xc00009a008`, 指针值为nil
此时，i为nil，系统没有给`*i`分配地址，相当于给一个nil地址赋值，肯定会出错

解决办法是，预先分配一个内存地址给到指针变量

## new初始化内存地址
```
package main

import "fmt"

func main() {
	var i *int

	fmt.Println(&i, i) // 0xc000094010 <nil>

	i = new(int)
	fmt.Println(&i, i, *i) //0xc000094010 0xc0000a2010 0
}
```
- i 为指针变量，它的内存地址为`0xc000094010`
- i的值为`0xc0000a2010`
- 内存地址`0xc0000a2010`值为0

