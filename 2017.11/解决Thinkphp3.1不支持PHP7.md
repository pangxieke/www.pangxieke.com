---
title: 解决Thinkphp3.1不支持PHP7
id: let-thinkphp3.1-support-php7
date: 2017.11.13 18:30
category: php
tag: php7
---

服务器重装了系统。PHP版本升级为PHP7，结果发现以前用Thinkphp写的一个项目无法运行了。
thinkphp版本使用3.1版。切换为PHP5.6版本时，可以正常使用。
切换为PHP7时，页面显示空白。
追踪框架核心代码，找到核心的模板文件，最终发现是preg_replace函数错误。

## 查找原因
先打开错误提示
编辑`index.php`
```
ini_set('display_errors', '1');
error_reporting(E_ALL);

define('APP_DEBUG', true); //修改
define('APP_ERROR_HANDLE',false);
```
多处提示
```
NOTIC: [2] preg_replace(): The /e modifier is no longer supported, use preg_replace_callback instead 
```
但这些都不是致命错误。一步一步断点调试，发现最终现象是
**页面变空白**
查看模板缓存,只显示如下信息
```
<?php if (!defined('THINK_PATH')) exit();?>
```
判断模板渲染出现异常，找到是模板解析出问题。
最终找到`Lib\Template\ThinkTemplate.class.php`中`parse()`方法。

## 错误原因
最终错误原因 **PHP7中删除了preg_replace()的/e参数**，其实这个参数在PHP5里就已经废除了，只不过没有删除，所以还能用。官方给出的建议是，用preg_replace_callback()代替preg_replace() /e。
当然不能直接删除`e`,然后替换成`用preg_replace_callback`, 需要修改回调方法

官方PHP文档
```
preg_replace_callback 
(PHP 4 >= 4.0.5, PHP 5, PHP 7)

preg_replace_callback — 执行一个正则表达式搜索并且使用一个回调进行替换

mixed preg_replace_callback ( mixed $pattern , callable $callback , mixed $subject [, int $limit = -1 [, int &$count ]] )
这个函数的行为除了 可以指定一个 callback 替代 replacement 进行替换 字符串的计算，其他方面等同于 preg_replace()。 
```

## 替换preg_replace函数
修改示例
原方法
```
$content    =   preg_replace('/'.$begin.'literal'.$end.'(.*?)'.$begin.'\/literal'.$end.'/eis',
"\$this->parseLiteral('\\1')",$content);
```
修改为
```
$content = preg_replace_callback('/'.$begin.'literal'.$end.'(.*?)'.$begin.'\/literal'.$end.'/is',
function ($match){$this->parseLiteral($match[1]);},$content);
```
说明
- 正则中，“/1”、“$1”表示第一个括号匹配的内容，“/2”、“$2”表示第二个括号匹配的内容，依此类推。
- 官方建议，preg_replace_callback()的回调使用匿名函数，参数$match为正则匹配的结果（数组），$match[1]表示第一个括号匹配的内容，依此类推。
- 若匿名函数需要使用外部变量，需要在定义函数时，使用use()传参。

## 全局修改
找到这个问题后，在网上查询到，有人已经解决此问题，将整个Thinphp3.1框架的所有错误都修改。并共享出来。
可以直接下载[修复后核心包](/images/2017/11/ThinkPHP_Repaire.zip)，替换掉，这样就解决了Thinkphp3.1不支持PHP问题。




参考文章[ThinkPHP3.1在PHP7下页面空白的解决方案](http://www.thinkphp.cn/topic/40115.html)

这篇文章中也有核心包下载地址[http://code.taobao.org/svn/share2016/trunk/ThinkPHP_Repaire.rar](http://code.taobao.org/svn/share2016/trunk/ThinkPHP_Repaire.rar)