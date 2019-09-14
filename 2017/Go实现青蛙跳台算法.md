---
title: Go实现青蛙跳台算法
id: 1307
categories:
  - go
date: 2017-09-01 10:48:04
tags:
---

## 问题

一只青蛙一次可以跳 上1级台阶，也可以跳上2级。求该青蛙跳上一个n级的台阶总共需要多少种跳法。

## 思路

首先考虑n等于0、1、2时的特殊情况，f(0) = 0 f(1) = 1 f(2) = 2
其次，当n=3时，青蛙的第一跳有两种情况：跳1级台阶或者跳两级台阶
假如跳一级，那么 剩下的两级台阶就是f(2)；假如跳两级，那么剩下的一级台阶就是f(1)，因此f(3)=f(2)+f(1)
当n = 4时，f(4) = f(3) +f(2)
以此类推...........可以联想到斐波拉契数列（Fibonacci数列）

## 方法一，递归

```php
package main

import (
    "fmt"
    "time"
)

func main() {
	t1 := time.Now()
	a := jump(42);
	elapsed := time.Since(t1)//运行时间 

	t2 := time.Now()
	b := jump2(42);
	
	elapsed2 := time.Since(t2)
	fmt.Println(a);
	fmt.Println(elapsed);	// 2.0183411s

	fmt.Println(b);
	fmt.Println(elapsed2);	// 0s
}

/**
 * 递归
 */
func jump(n int) int {
	var tmp int
	if n == 1 || n ==2{
		tmp = n
	}else{
		tmp = jump(n-1) + jump(n-2)
	}
	return tmp
}

```

## 方法一，迭代

```php
/**
 * 迭代
 */
func jump2(n int) int {
	var tmp int
	if n == 1 || n ==2{
		tmp = n
	}

	a := 1
	b := 2
	for i:=3; i &lt;= n; i++ {
		tmp = a + b
		a = b
		b = tmp
	}
	return tmp
}
```