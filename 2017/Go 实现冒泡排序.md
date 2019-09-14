---
title: Go 实现冒泡排序
tags:
  - go
id: 1312
categories:
  - go
date: 2017-09-04 09:38:45
---

通过Go语言实现冒泡排序

## 代码

```php
package main 

import (
	"fmt"
)

func main() {
	fmt.Println("hello")

	s := []int{6, 3, 1, 7, 5, 8, 9}

	fmt.Println(s)
	bubble(s)
	fmt.Println(s)
}

/**
 * 排序算法
 */
func bubble(slice [] int){
	leng := len(slice)

	for i:=0; i < leng - 1; i++{
		for j:=i+1; j < leng; j++ {
			if slice[j] > slice[i] {
				swop(slice, j, i)
			}
		}
	}
}

/**
 * 左右值交换
 */
func swop (slice [] int , i int, j int) {
	slice[i], slice[j] = slice[j], slice[i]
}
```