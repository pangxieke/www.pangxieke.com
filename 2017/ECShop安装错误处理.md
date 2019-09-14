---
title: ECShop安装错误处理
tags:
  - ecshop
id: 639
categories:
  - php
date: 2015-04-10 21:06:22
---

今天安装ECShop，2.7.3版本，我的php为5.4.16，安装过程中出现一些错误，多为php版本兼容问题。
1.错误提示：Strict Standards: Only variables should be passed by reference in ***\cls_template.php on line 418

解决办法：

打开cls_template.php文件中发现下面这段代码：

```
$tag_sel = array_shift(explode(' ', $tag));
```
我的PHP版本是5.4.16，PHP5.3以上默认只能传递具体的变量，而不能通过函数返回值传递，所以这段代码中的explode就得移出来重新赋值了
```
$tagArr = explode(' ', $tag);
$tag_sel = array_shift($tagArr);
```
这样之后顶部的报错没掉了，左侧和底部的报错还需要去ecshop的后台又上角点击清除缓存才能去除。

2.错误提示：`Deprecated: Assigning the return value of new by reference is deprecated in……`

解决方法：

找到错误代码的位置 去掉 & 

改前：`$helper =& new GK3NewsShowHelper(); `

改后：`$helper = new GK3NewsShowHelper(); `

3.错误提示：`trict standards: Non-static method cls_image::gd_version() should not be called statically in ***\www\includes\lib_base.php on line346`

解决办法：
这个错误的的处理是修改文件：`E:\SiteAll\ZBPHP.COM\www\includes\cls_image.php` 第693行，把

`“function gd_version()”` 改成`“static function gd_version()”` 即可。

新版php要求更严格，类的静态方法，前面必须有static修饰。不能直接写`public function fName()`，前面必须加上static，否则报错

4.错误提示:`Strict standards: Declaration of phpbb::set_cookie() should be compatible with integrate::set_cookie($username = '', $remember = NULL) 
in D:\php\ECShop\upload\includes\modules\integrates\phpbb.php on line 232`
 因为class phpbb extends integrate 
 `phpbb.php` 110行   `function set_cookie ($username="")`
 `integrate.php` 565行，`function set_cookie($username='', $remember= null )`

 解决办法， `phpbb.php` 110行函数增加第二个参数，即改为 `function set_cookie($username='', $remember= null )`
 新版php类的继承extends，子类的方法，如果父类也有，那么必须参数一致，否则也会报错

5.错误提示：`Strict standards: Redefining already defined constructor for class `

错误原因：
PHP 类，有两种构造函数，一种是跟类同名的函数，为旧版写法。一种是 __construct()，为新版写法。从PHP5.4开始，对这两个函数出现的顺序做了最严格的定义，必须是 __construct() 在前，同名函数在后

解决方法：
调换一下两个函数的前后位置即可。

6.Strict standards: mktime(): You should be using the time() function instead in D:\php\ECShop\upload\admin\sms_url.php on line 31

错误原因：php5.1后，使用mktime()报错
php手册mktime()函数，明确写明：
As of PHP 5.1, when called with no arguments, mktime() throws an E_STRICT notice: use the time() function instead.

解决方法：
使用time()代替