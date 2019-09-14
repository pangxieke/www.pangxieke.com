---
title: php7新特性
id: 882
categories:
  - php
date: 2015-12-05 18:00:35
tags:
---

PHP7修复了大量BUG，新增了功能和语法糖。这些改动涉及到了核心包、GD库、PDO、ZIP、ZLIB等熟悉和不熟悉的核心功能与扩展包。

PHP7移除了已经被废弃的函数，如mysql_系列函数在PHP5.5被废弃，在PHP7被删除。

PHP7的性能高于HHVM。并且是PHP5.6的两倍。

http://php.net/archive/2015.php#id2015-12-03-1

### PHP7特性

2015年12月3号, PHP开发团队宣布PHP 7.0.0即将上市。本次发布标志着新的重要的PHP 7系列的开始。

PHP 7.0.0附带了一个新版本的Zend引擎中，无数的改进和新功能，如

性能改善：PHP 7高达两倍快的PHP 5.6

显著减少内存使用

抽象语法树

一致的64位支持

改进的异常层次结构

许多转化为异常致命错误

安全随机数发生器

删除旧的和不支持的SAPIs和扩展

空合并运算符（？）

返回和标量类型声明

匿名类

零成本断言

这是下一个主要版本的PHP。它的发布是近两年的发展征程的结果。这是核心团队的一个非常特殊的成就。而且，它是许多活跃的社区成员难以置信努力的结果。事实上，这是一个新的PHP一代的崛起与巨大潜力。

恭喜大家，这是一个壮观的PHP的世界！

感谢感谢所有的贡献者和支持者！

### 一、PHP7的前世今生

以下摘自并修改与鸟哥微信
`
PHP7开始于2014年春节，因为基于PHP-5.5的Opcache JIT因为无法得到期望而搁置了,并且让鸟哥等人认识到, 基础部分还不够好, 并不能很好的支持JIT, 所以开始了重构项目,希望通过得到30%以上的提升。随后发现性能提升比我们想象的还要大，于是定名为PHP NG项目。

经过发起投票, 绝大部分人都支持了PHP NG项目, 并决定以PHP NG为基础, 开发新版的PHP。社区曾开发过PHP6，后来PHP6的特性在PHP5.5，5.6等版本都逐渐实现，所以PHP6被搁置。经过社区投票，新项目命名为PHP7。

在这近两年的时间里，各种新特性的加入, 性能的持续提升，很多以前不合理的地方改进等等, 都加入到了PHP7, 让PHP7越来越丰满. 从最底层的ZVAL的改变, 到标量类型提示, 从最初的30%的性能提升, 到现在超过100%的性能飞跃, 每一处变化都让人值得期待. 然后经过几次不情愿的跳票, 终于, 到今天, 这一切都将呈现于你面前。 `

### 二、安装

安装：我们编译了核心包以及PDO，GD，mysqli，Zip等
```php

./configure --prefix=/usr/local/php7 --enable-fpm --with-zlib --enable-mbstring --with-openssl 
--with-mysql --with-mysqli --with-mysql-sock --with-gd --enable-gd-native-ttf  --enable-pdo 
--with-pdo-mysql --with-gettext --with-curl --with-pdo-mysql --enable-sockets --enable-bcmath 
--enable-xml --with-bz2 --enable-zip

make

sudo make install

```

三、测试

测试版本：

旧版PHP 5.5.29，新版 PHP 7.0.0

1、测试用例一： 

生成五十万个数组，并查询五十万次key是否存在
```php
<?php
 
    $a = array();
 
    for($i=0;$i<500000;$i++){
 
        $a[$i] = $i;
 
    }
 
    foreach($a as $i)
 
    {
 
        array_key_exists($i, $a);
 
}
 
?>
```
测试结果如下：
```php

?  time php test.php
 
php test.php 
 
0.60s user
 
0.05s system
 
98% cpu
 
0.667 total
 
?  time /usr/local/php7/bin/php test.php
 
/usr/local/php7/bin/php test.php
 
0.05s user
 
0.02s system
 
92% cpu
 
0.073 total

``` 

PHP7速度是PHP5.5的9倍

2、测试用例二：

生成五十万个数组，并查询五十万次value是否存在
```php
<?php
 
    $a = array();
 
    for($i=0;$i<10000;$i++){
 
        $a[$i] = $i;
 
    }
 
    foreach($a as $i)
 
    {
 
        array_search($i, $a);
 
}
 
?>
```
```
?  time php test.php

php test.php

0.79s user

0.01s system

99% cpu

0.809 total

?  time /usr/local/php7/bin/php test.php

/usr/local/php7/bin/php test.php 

0.08s user

0.01s system

97% cpu

0.091 total
```

PHP7速度是PHP5.5的8.7倍

3、测试用例三：

示例与结果摘自鸟哥博客。以Wordpress为基础，测试PHP7和HHVM3.2。用Apache的ab测试工具。100个并发, 10000个请求。测试前都会用100个请求预热。

PHP7结果如下：
```php
Concurrency Level:      100

Time taken for tests:   38.726 seconds

Complete requests:      10000

Failed requests:        0

Write errors:           0

Total transferred:      89290000 bytes

HTML transferred:       86900000 bytes

Requests per second:    258.22 [#/sec] (mean)

Time per request:       387.260 [ms] (mean)

Time per request:       3.873 [ms] (mean, across all concurrent requests)

Transfer rate:          2251.64 [Kbytes/sec] received

HHVM-3.2

HHVM结果如下：

Document Path:          /wordpress/

Document Length:        8690 bytes

Concurrency Level:      100

Time taken for tests:   43.296 seconds

Complete requests:      10000

Failed requests:        0

Write errors:           0

Total transferred:      89260000 bytes

HTML transferred:       86900000 bytes

Requests per second:    230.97 [#/sec] (mean)

Time per request:       432.957 [ms] (mean)

Time per request:       4.330 [ms] (mean, across all concurrent requests)

Transfer rate:          2013.31 [Kbytes/sec] received

PHP7 – 258.22 QPS HHVM – 230.97 QPS
```

四、新特性

1、标量类型声明

有两种模式: 强制 (默认) 和 严格模式。 现在可以使用下列类型参数（无论用强制模式还是严格模式）： 字符串(string), 整数 (int), 浮点数 (float), 以及布尔值 (bool)。它们扩充了PHP5中引入的其他类型：类名，接口，数组和 回调类型。在旧版中，函数的参数申明只能是(Array $arr)、(CLassName $obj)等，基本类型比如Int，String等是不能够被申明的
```php
<?php

function check(int $bool){

    var_dump($bool);

}

check(1);

check(true);

?>
```
若无强制类型转换，会输入int(1)bool(true)。转换后会输出bool(true) bool(true)

2、返回值类型声明

PHP 7 增加了对返回类型声明的支持。返回类型声明指明了函数返回值的类型。可用的类型与参数声明中可用的类型相同。
```php
<?php
 
function arraysSum(array ...$arrays): array
 
{
 
    return array_map(function(array $array): int {
 
        return array_sum($array);
 
    }, $arrays);
 
}
 
print_r(arraysSum([1,2,3], [4,5,6], [7,8,9]));
 
以上例程会输出：
 
Array
 
(
 
    [0] => 6
 
    [1] => 15
 
    [2] => 24
 
)
```
3、null合并运算符

项目中存在大量同时使用三元表达式和 isset()的情况，新增了null合并运算符 (??) 这个语法糖。如果变量存在且值不为NULL， 它就会返回自身的值，否则返回它的第二个操作数。

旧版：`isset($_GET[‘id']) ? $_GET[id] : err;`

新版：`$_GET['id'] ?? 'err';`

4、太空船操作符（组合比较符）

太空船操作符用于比较两个表达式。当$a大于、等于或小于$b时它分别返回-1、0或1。 比较的原则是沿用 PHP 的常规比较规则进行的。
```php
<?php
 
// Integers
 
echo 1 <=> 1; // 0
 
echo 1 <=> 2; // -1
 
echo 2 <=> 1; // 1
 
// Floats
 
echo 1.5 <=> 1.5; // 0
 
echo 1.5 <=> 2.5; // -1
 
echo 2.5 <=> 1.5; // 1
 
// Strings
 
echo "a" <=> "a"; // 0
 
echo "a" <=> "b"; // -1
 
echo "b" <=> "a"; // 1
 
?>
```
5、通过define()定义常量数组
```php
<?php
 
define('ANIMALS', ['dog', 'cat', 'bird']);
 
echo ANIMALS[1]; // outputs "cat"
 
?>
```
6、匿名类

现在支持通过new class 来实例化一个匿名类，这可以用来替代一些“用后即焚”的完整类定义。
```php
<?php
 
interface Logger {
 
    public function log(string $msg);
 
}
 
class Application {
 
    private $logger;
 
    public function getLogger(): Logger {
 
        return $this->logger;
 
    }
 
    public function setLogger(Logger $logger) {
 
        $this->logger = $logger;
 
    }
 
}
 
$app = new Application;
 
$app->setLogger(new class implements Logger {
 
    public function log(string $msg) {
 
        echo $msg;
 
    }
 
});
 
var_dump($app->getLogger());
```
7、Unicode codepoint 转译语法

这接受一个以16进制形式的 Unicode codepoint，并打印出一个双引号或heredoc包围的 UTF-8 编码格式的字符串。 可以接受任何有效的 codepoint，并且开头的 0 是可以省略的。
```php
<?php
 
 echo “\u{9876}”
 
?>
 ```
旧版输出：\u{9876}

新版输入：顶

8、Closure::call()

Closure::call() 现在有着更好的性能，简短干练的暂时绑定一个方法到对象上闭包并调用它。
```php
<?php
 
class Test{public $name = "lixuan";}
 
  
 
//PHP7和PHP5.6都可以
 
$getNameFunc = function(){return $this->name;};
 
$name = $getNameFunc->bindTo(new Test, 'Test');
 
echo $name();
 
//PHP7可以,PHP5.6报错
 
$getX = function() {return $this->name;};
 
echo $getX->call(new Test);
```
9、为unserialize()提供过滤

这个特性旨在提供更安全的方式解包不可靠的数据。它通过白名单的方式来防止潜在的代码注入。
```php
<?php
 
//将所有对象分为__PHP_Incomplete_Class对象
 
$data = unserialize($foo, ["allowed_classes" => false]);
 
//将所有对象分为__PHP_Incomplete_Class 对象 除了ClassName1和ClassName2
 
$data = unserialize($foo, ["allowed_classes" => ["ClassName1", "ClassName2"]);
 
//默认行为，和 unserialize($foo)相同
 
$data = unserialize($foo, ["allowed_classes" => true]);
```
10、IntlChar

新增加的 IntlChar 类旨在暴露出更多的 ICU 功能。这个类自身定义了许多静态方法用于操作多字符集的 unicode 字符。Intl是Pecl扩展，使用前需要编译进PHP中，也可apt-get/yum/port install php5-intl
```php
<?php
 
printf('%x', IntlChar::CODEPOINT_MAX);
 
echo IntlChar::charName('@');
 
var_dump(IntlChar::ispunct('!'));
 
?>
··· 
以上例程会输出：
 
10ffff
 
COMMERCIAL AT
 
bool(true)

11、预期

预期是向后兼用并增强之前的 assert() 的方法。 它使得在生产环境中启用断言为零成本，并且提供当断言失败时抛出特定异常的能力。 老版本的API出于兼容目的将继续被维护，assert()现在是一个语言结构，它允许第一个参数是一个表达式，而不仅仅是一个待计算的 string或一个待测试的boolean。
```php
<?php
 
ini_set('assert.exception', 1);
 
class CustomError extends AssertionError {}
 
assert(false, new CustomError('Some error message'));
 
?>
```
以上例程会输出：

`Fatal error: Uncaught CustomError: Some error message`

12、Group use declarations

从同一 namespace 导入的类、函数和常量现在可以通过单个 use 语句 一次性导入了。
```php
<?php
 
//PHP7之前
 
use some\namespace\ClassA;
 
use some\namespace\ClassB;
 
use some\namespace\ClassC as C;
 
use function some\namespace\fn_a;
 
use function some\namespace\fn_b;
 
use function some\namespace\fn_c;
 
use const some\namespace\ConstA;
 
use const some\namespace\ConstB;
 
use const some\namespace\ConstC;
 
// PHP7之后
 
use some\namespace\{ClassA, ClassB, ClassC as C};
 
use function some\namespace\{fn_a, fn_b, fn_c};
 
use const some\namespace\{ConstA, ConstB, ConstC};
 
?>
```
13、intdiv()

接收两个参数作为被除数和除数，返回他们相除结果的整数部分。
```php
<?php
 
var_dump(intdiv(7, 2));
 
?>
 ```
输出int(3)

14、CSPRNG

新增两个函数: `random_bytes()` and `random_int()`.可以加密的生产被保护的整数和字符串。我这蹩脚的翻译，总之随机数变得安全了。

andom_bytes — 加密生存被保护的伪随机字符串

random_int —加密生存被保护的伪随机整数

15、`preg_replace_callback_array()`

新增了一个函数`preg_replace_callback_array()`，使用该函数可以使得在使用`preg_replace_callback()`函数时代码变得更加优雅。在PHP7之前，回调函数会调用每一个正则表达式，回调函数在部分分支上是被污染了。

16、Session options

现在，session_start()函数可以接收一个数组作为参数，可以覆盖php.ini中session的配置项。

比如，把cache_limiter设置为私有的，同时在阅读完session后立即关闭。
```php
<?php
 
session_start([
 
    'cache_limiter' => 'private',
 
    'read_and_close' => true,
 
]);
 
?>
```
17、生成器的返回值

在PHP5.5引入生成器的概念。生成器函数每执行一次就得到一个yield标识的值。在PHP7中，当生成器迭代完成后，可以获取该生成器函数的返回值。通过Generator::getReturn()得到。
```php
<?php
 
function generator() {
 
    yield 1;
 
    yield 2;
 
    yield 3;
 
    return "a";
 
}
 
$generatorClass = ("generator")();
 
foreach ($generatorClass as $val) {
 
    echo $val.” “;
 
}
 
echo $generatorClass->getReturn();
 
?>
 ```
输出为：1 2 3 a

18、生成器中引入其他生成器

在生成器中可以引入另一个或几个生成器，只需要写yield from functionName1
```php
<?php
 
function generator1(){
 
    yield 1;
 
    yield 2;
 
    yield from generator2();
 
    yield from generator3();
 
}
 
function generator2(){
 
    yield 3;
 
    yield 4;
 
}
 
function generator3(){
 
    yield 5;
 
    yield 6;
 
}
 
foreach (generator1() as $val){
 
    echo $val, " ";
 
}
 
?>
```
输出：1 2 3 4 5 6

五、不兼容性

1、foreach不再改变内部数组指针

在PHP7之前，当数组通过 foreach 迭代时，数组指针会移动。现在开始，不再如此，见下面代码。
```php
<?php
 
$array = [0, 1, 2];
 
foreach ($array as &$val) {
 
    var_dump(current($array));
 
}
 
?>
 ```
PHP5输出：
 
int(1)
 
int(2)
 
bool(false)
 
PHP7输出：
 
int(0)
 
int(0)
 
int(0)

2、foreach通过引用遍历时，有更好的迭代特性

当使用引用遍历数组时，现在 foreach 在迭代中能更好的跟踪变化。例如，在迭代中添加一个迭代值到数组中，参考下面的代码：
```php
<?php
 
$array = [0];
 
foreach ($array as &$val) {
 
    var_dump($val);
 
    $array[1] = 1;
 
}
 
?>
```

PHP5输出：
 
int(0)
 
PHP7输出：
 
int(0)
 
int(1)

3、十六进制字符串不再被认为是数字

含十六进制字符串不再被认为是数字
```php
<?php
 
var_dump("0x123" == "291");
 
var_dump(is_numeric("0x123"));
 
var_dump("0xe" + "0x1");
 
var_dump(substr("foo", "0x1"));
 
?>
```

PHP5输出：
 
bool(true)
 
bool(true)
 
int(15)
 
string(2) "oo"
 
PHP7输出：
 
bool(false)
 
bool(false)
 
int(0)
 
Notice: A non well formed numeric value encountered in /tmp/test.php on line 5
 
string(3) "foo"

4、PHP7中被移除的函数

被移除的函数列表如下：

`call_user_func()`和 `call_user_func_array()`从PHP 4.1.0开始被废弃。

已废弃的 `mcrypt_generic_end()` 函数已被移除，请使用`mcrypt_generic_deinit()`代替。

已废弃的 `mcrypt_ecb()`, `mcrypt_cbc()`, `mcrypt_cfb()` 和 `mcrypt_ofb()` 函数已被移除。

`set_magic_quotes_runtime()`, 和它的别名 `magic_quotes_runtime()`已被移除. 它们在PHP 5.3.0中已经被废弃,并且 在in PHP 5.4.0也由于魔术引号的废弃而失去功能。

已废弃的 `set_socket_blocking()` 函数已被移除，请使用`stream_set_blocking()`代替。

`dl()`在 PHP-FPM 不再可用，在 CLI 和 embed SAPIs 中仍可用。

GD库中下列函数被移除：`imagepsbbox()、imagepsencodefont()、imagepsextendfont()、imagepsfreefont()、imagepsloadfont()、imagepsslantfont()、imagepstext()`

在配置文件php.ini中，`always_populate_raw_post_data、asp_tags、xsl.security_prefs`被移除了。

5、new 操作符创建的对象不能以引用方式赋值给变量

new 操作符创建的对象不能以引用方式赋值给变量
```php
<?php
 
class C {}
 
$c =& new C;
 
?>
 ```
PHP5输出：
 
Deprecated: Assigning the return value of new by reference is deprecated in /tmp/test.php on line 3
 
PHP7输出：
 
Parse error: syntax error, unexpected 'new' (T_NEW) in /tmp/test.php on line 3

6、移除了 ASP 和 script PHP 标签

使用类似 ASP 的标签，以及 script 标签来区分 PHP 代码的方式被移除。 受到影响的标签有：`<% %>`、`<%= %>`、`<script language="php"> </script>`

7、从不匹配的上下文发起调用

在不匹配的上下文中以静态方式调用非静态方法， 在 PHP 5.6 中已经废弃， 但是在 PHP 7.0 中， 会导致被调用方法中未定义 $this 变量，以及此行为已经废弃的警告。
```php
<?php
 
class A {
 
    public function test() { var_dump($this); }
 
}
 
// 注意：并没有从类 A 继承
 
class B {
 
    public function callNonStaticMethodOfA() { A::test(); }
 
}
 
(new B)->callNonStaticMethodOfA();
 
?>
 ```
PHP5输出：
```
Deprecated: Non-static method A::test() should not be called statically, 
assuming $this from incompatible context in /tmp/test.php on line 8
 
object(B)#1 (0) {
 
}
```
PHP7输出：
``` 
Deprecated: Non-static method A::test() should not be called statically in /tmp/test.php on line 8
 
Notice: Undefined variable: this in /tmp/test.php on line 3
 
NULL
```
8、在数值溢出的时候，内部函数将会失败

将浮点数转换为整数的时候，如果浮点数值太大，导致无法以整数表达的情况下， 在之前的版本中，内部函数会直接将整数截断，并不会引发错误。 在 PHP 7.0 中，如果发生这种情况，会引发 E_WARNING 错误，并且返回 NULL。

9、JSON 扩展已经被 JSOND 取代

JSON 扩展已经被 JSOND 扩展取代。 对于数值的处理，有以下两点需要注意的： 第一，数值不能以点号（.）结束 （例如，数值 34\. 必须写作 34.0 或 34）。 第二，如果使用科学计数法表示数值，e 前面必须不是点号（.） （例如，3.e3 必须写作 3.0e3 或 3e3）。

10、INI 文件中 `#` 注释格式被移除

在配置文件INI文件中，不再支持以 # 开始的注释行， 请使用 ;（分号）来表示注释。 此变更适用于 php.ini 以及用 parse_ini_file() 和 parse_ini_string() 函数来处理的文件。

11、$HTTP_RAW_POST_DATA 被移除

不再提供 $HTTP_RAW_POST_DATA 变量。 请使用 php://input 作为替代。

12、yield 变更为右联接运算符

在使用 yield 关键字的时候，不再需要括号， 并且它变更为右联接操作符，其运算符优先级介于 print 和 => 之间。 这可能导致现有代码的行为发生改变。可以通过使用括号来消除歧义。
```php
<?php
 
echo yield -1;
 
// 在之前版本中会被解释为：
 
echo (yield) - 1;
 
// 现在，它将被解释为：
 
echo yield (-1);
 
  
 
yield $foo or die;
 
// 在之前版本中会被解释为：
 
yield ($foo or die);
 
// 现在，它将被解释为：
 
(yield $foo) or die;
 
?>
```

PHP官方网站文档：http://php.net/manual/zh/migration70.php

可以浏览PHP5.6到PHP7时，新特性、新增函数、已经被移除的函数、不兼容性、新增的类和接口等内容。