---
title: PHP实现青蛙跳台阶算法
id: 1306
categories:
  - php
date: 2017-08-31 21:38:59
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
function jump($n)
{
	if($n == 1){
		return 1;
	}elseif($n == 2){
		return 2;
	}else{
		return jump($n - 1) + jump($n-2);
	}
}
$time1 = time();
echo jump(42);
$time2 = time();
echo $time2 - $time1;
```

## 方法二、迭代

```php
function jump2($n)
{

	if($n == 1){
		return 1;
	}elseif($n == 2){
		return 2;
	}

	$a = 1;
	$b = 2;
	$temp = 0;

	for($i = 3; $i &lt;= $n; $i++){

		$temp = $a + $b;
		$a = $b;
		$b = $temp;
	}
	return $temp;
}
$time1 = time();
echo jump2(42);
$time2 = time();
echo $time2 - $time1;
```

## 总结

方法二时间复杂度明显更小