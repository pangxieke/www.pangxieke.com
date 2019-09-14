---
title: 一种PHP文件加密的破解
tags:
  - 加密
id: 295
categories:
  - php
date: 2014-09-18 23:31:13
---

<span style="font-size: 18pt;">**一种php文件加密方法的破解**</span>

　　文件加密方式，变量混淆＋字符串加密 　　文件原始内容 ：
文件加密方式，变量混淆＋字符串加密
文件原始内容 ：

```php

$OOO0O0O00=__FILE__;
$OOO000000=urldecode（'%74%68%36%73%62%65%68%71%6c%61%34%63%6f%5f%73%61%64%66%70%6e%72'）;
 
$OO00O0000=164;
$OOO0000O0=$OOO000000{4}.$OOO000000{9}.$OOO000000{3}.$OOO000000{5};
 
$OOO0000O0.=$OOO000000{2}.$OOO000000{10}.$OOO000000{13}.$OOO000000{16};
 
$OOO0000O0.=$OOO0000O0{3}.$OOO000000{11}.$OOO000000{12}.$OOO0000O0{7}.$OOO000000{5};
 
 
$O0O0000O0='OOO0000O0';
eval（（$$O0O0000O0（'JE9PME9PMDAwMD0kT09PMDAE3fS4kT09PMDAwAwezEyfS4kT09PMDAwMDAwezE4fS4kT09PMDAwMDAwezV9LiRPT08wMDAwMDB7MTl9O2lmKCEwKSRPMDAwTzBPMDA9JE9PME9PMDAwMCgkT09PME8wTzAwLCdyYicpOyRPTzBPTzAwME89JE9PTzAwMDAwMHsxN30uJE9PTzAwMDAwMHsyMH0uJE9PTzAwMDAwMHs1fS4kT09PMDAwMDAwezl9LiRPT08wMDAwMDB7MTZ9OyRPTzBPTzAwTzA9JE9PTzAwMDAwMHsxNH0uJE9PTzAwMDAwMHswfS4kT09PMDAwMDAwezIwfS4kT09PMDAwMDAwezB9LiRPT08wMDAwMDB7MjB9OyRPTzBPTzAwME8oJE8wMDBPME8wMCwxMjYxKTskT08wME8wME8wPSgkT09PMDAwME8wKCRPTzBPTzAwTzAoJE9PME9PMDAwTygkTzAwME8wTzAwLDcwMCksJ0VudGVyeW91d2toUkhZS05XT1VUQWFCYkNjRGRGZkdnSWlKakxsTW1QcFFxU3NWdlh4WnowMTIzNDU2Nzg5Ky89JywnQUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVphYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ejAxMjM0NTY3ODkrLycpKSk7ZXZhbCgkT08wME8wME8wKTs='）））;
return;
```
这是一段php代码，后面跟了一串加密过的字符串。
很显然，开头的这几行代码是执行解密的，或者是解密的前秦工作。
进行分析：
```php
$OOO0O0O00=__FILE__;//本文件路径和文件名
//字符串用于下面构造新的字符串
$OOO000000=urldecode（'%74%68%36%73%62%65%68%71%6c%61%34%63%6f%5f%73%61%64%66%70%6e%72'）;
//下面几行构造字符串base64_decode
$OOO0000O0=$OOO000000{4}.$OOO000000{9}.$OOO000000{3}.$OOO000000{5};
$OOO0000O0.=$OOO000000{2}.$OOO000000{10}.$OOO000000{13}.$OOO000000{16};
$OOO0000O0.=$OOO0000O0{3}.$OOO000000{11}.$OOO000000{12}.$OOO0000O0{7}.$OOO000000{5};
//下面通过base64 decode生成一段读取自身文件的代码，先读取了若干字节，丢弃了，
//分析可能是头部执行初步解密的PHP代码，接着又读取700字节，进行字符串变换 base64_decode之后，
//得到一段继续读取文件解密的代码，经分析发现，第二次读取的700 字节中包含一版权声明的代码。
//第三次读取文件后经过解密，得到了原始代码 。解密过程分析完毕，下面开始写破解算法，
function crack（$src, $dst） {
$content = file_get_contents（$src）;
$pos = strpos（$content, '?&gt;'）;
//删除读取文件的代码
$code = substr（$content, $pos + 3）;
//删除解码代码
$code = substr（$code, 700）;
//解码目标代码
$cracked = base64_decode（strtr（$code, 'EnteryouwkhRHYKNWOUTAaBbCcDdFfGgPpQqSsVvXxZz0123456789+/=', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklvwxyz0123456789+/'））;
//写入目标文件
file_put_contents（$dst, "<!--?php " . $cracked . " ?-->"）;
log_info（"解码文件：$src 至 $dst 完成"）;
}
```
使用该函数对加密的文件进行解密，打开解密的文件 ，格式化代码，原始代码完善呈现！

[原文 http://www.hackbase.com/tech/2011-06-13/64113.html](http://www.hackbase.com/tech/2011-06-13/64113.html)