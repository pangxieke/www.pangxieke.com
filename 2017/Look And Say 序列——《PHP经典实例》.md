---
title: Look And Say 序列——《PHP经典实例》
tags:
  - look
id: 145
categories:
  - php
date: 2014-08-29 22:42:36
---
“Look and Say”序列是J.H.Conway发明的一个著名的整数序列


```

function lookandsay($s){
//将保存返回值的变量初始化为空字符串
$r='';

//$m用于保存我们要查找的字符，同时初始化首字符
$m=$s[0];

//用来保存我们找到的$m的数目，初始化为1,如不指定，没法开始统计
$n = 1;

for($i=1,$j=strlen($s);$i<$j;$i++){
//如果这个字符与上一个字符相同
if($s[$i]==$m){
//这个字符的数目加1
$n++;
}else{
//否则，把数目和这个字符追加到返回值
$r.=$n.$m;
//把要找的设置成当前的字符
$m=$s[$i];
//并把数目重置为1
$n=1;
}
}
//返回构建好的字符吕以及最终的数目和字符
return $r.$n.$m;
}

for ($i=0,$s=1;$i<10;$i++){
$s=lookandsay($s);
print "$s<br/>\n";
}

```


第一个数字是：1。

看着第一个数字你可以说1个1，那么第二个数字就是：11。

看着第二个数字你可以说2个1，即第三个数字是：21。

看着第三个数字你可以说1个2,1个1，即第四个数字是：1211。

看着第四个数字你可以说1个1,1个2,2个1，即第五个数字是：111221。

根据详细的说明可以参见：<a href="http://en.wikipedia.org/wiki/Look-and-say_sequence">http://en.wikipedia.org/wiki/Look-and-say_sequence</a>