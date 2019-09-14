---
title: 自己编译、创建php扩展
id: 1278
categories:
  - php
date: 2017-08-08 10:18:05
tags:
---

## 第一步：生成代码

PHP为我们提供了生成基本代码的工具 ext_skel。这个工具在PHP源代码的./ext目录下
```php
lnmp1.3/src/php-7.0.7/ext/ext_skel --extname=say
```
extname参数的值就是扩展名称。执行ext_skel命令后，这样在当前目录下会生成一个与扩展名一样的目录
此时会有提示信息
[![](/images/2017/08/20170808101346.png)](/images/2017/08/20170808101346.png)

## 第二步，修改config.m4配置文件

 config.m4的作用就是配合phpize工具生成configure文件。configure文件是用于环境检测的。检测扩展编译运行所需的环境是否满足。现在我们开始修改config.m4文件。

```php
 vi ext/say/config.m4 
```
 打开，config.m4文件后，你会发现这样一段文字。
```php
 dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(say, for say support,
dnl Make sure that the comment is aligned:
dnl [  --with-say             Include say support])

dnl Otherwise use enable:

dnl PHP_ARG_ENABLE(say, whether to enable say support,
dnl Make sure that the comment is aligned:
dnl [  --enable-say           Enable say support])

```

 其中，dnl 是注释符号。上面的代码说，如果你所编写的扩展如果依赖其它的扩展或者lib库，需要去掉PHP_ARG_WITH相关代码的注释。否则，去掉 PHP_ARG_ENABLE 相关代码段的注释。我们编写的扩展不需要依赖其他的扩展和lib库。因此，我们去掉PHP_ARG_ENABLE前面的注释。去掉注释后的代码如下：
```php
dnl If your extension references something external, use with:

 dnl PHP_ARG_WITH(say, for say support,
 dnl Make sure that the comment is aligned:
 dnl [  --with-say             Include say support])

 dnl Otherwise use enable:

 PHP_ARG_ENABLE(say, whether to enable say support,
 Make sure that the comment is aligned:
 [  --enable-say           Enable say support])
```

## 第三步，代码实现

修改say.c文件。实现say方法。
找到PHP_FUNCTION(confirm_say_compiled)，在其上面增加如下代码：

```php
PHP_FUNCTION(say)
{
        zend_string *strg;
        strg = strpprintf(0, &quot;hello word&quot;);
        RETURN_STR(strg);
}
```

找到 PHP_FE(confirm_say_compiled, 在上面增加如下代码：
```php
PHP_FE(say, NULL)
```
修改后的代码如下：
```php
const zend_function_entry say_functions[] = {
     PHP_FE(say, NULL)       /* For testing, remove later. */
     PHP_FE(confirm_say_compiled,    NULL)       /* For testing, remove later. */
     PHP_FE_END  /* Must be the last line in say_functions[] */
 };
 /* }}} */
```

## 第四步，编译安装

 编译扩展的步骤如下：
 ```php
 phpize
 #或者/usr/local/php/bin/phpize
./configure
#或者./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-php-config=/usr/local/php/bin/php-config
make &amp;&amp; make install

```

修改php.ini文件，增加如下代码：
```php
extension = say.so
```
然后执行，php -m 命令，查看模块。在输出的内容中，你会看到say字样

## 第五步，调用测试

```php
&lt;?php
echo say();
?&gt;
```
执行
```php
 php ./test.php
 hello word
```
在扩展中实现一个say方法，调用say方法后，输出 hello word

## 可能错误

make时，可能提示错误，需要开启proc_open函数
需要修改php.ini中 disable_functions,去除proc_open

参考原文 http://www.bo56.com/php7扩展开发之hello-word