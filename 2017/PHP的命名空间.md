---
title: PHP的命名空间
id: 280
categories:
  - php
date: 2014-09-16 21:41:35
tags: namespace
---

### PHP的命名空间

PHP的命名空间（namespace）是php5.3之后才有的。这个概念在C#中已经很早就有了，php中的namespace其实和c#的概念是一样的。
为什么php中要使用namespace？

假设如果不使用namespace，那么每个类在一个项目中的名字就必须是固定的。因为php在new的时候不管是调用autoload还是调用已加载过的类，都存在一个类名对应的文件。所以在没有namespace的时候，我们会想各种命名规则来区分不同的类，比如project1_school1_class1_Student或者project2_school_class_Student。

引入namespace之后就可以将这个有效规避了，一个namespace就相当于对应一个文件路径，查找这个类的时候，就会去对应的文件路径查找类定义文件了。
namespace的定义和使用

### 定义

```php	
namespace Myproject;
 
//或者
namespace Myproject {};
 
//使用：
 
use Myproject/School;
 
use Myproject/School as School1;   // 别名
```

命名空间是运行时解析的。use就相当于一种声明，并不解析和加载。比如下面这个例子：

`test.php`

```php
use my\name;
require_once("/home/yejianfeng/handcode/test/namespace1.php");
$a = new my\name\A();
$a->Print1();

```

`namespace1.php`

```php
namespace my\name;
class A {
    public function Print1(){
            echo 11;
    }
}
```

虽然require_once在use下面，也是可以正常运行的，因为程序只有在new my\name\A()的时候才去加载命名空间my\name
全局类和命名空间类

如果要new一个全局类使用 new \A()

如果要new一个命名空间类，使用new my\namespace\A()
命名空间的顺序

自从有了命名空间之后，最容易出错的该是使用类的时候，这个类的寻找路径是什么样的了。

如果能弄清楚manual中的这个例子就能全部弄清楚寻找顺序了。

```php	
<?php
namespace A;
use B\D, C\E as F;
  
// 函数调用
  
foo();      // 首先尝试调用定义在命名空间"A"中的函数foo()
            // 再尝试调用全局函数 "foo"
  
\foo();     // 调用全局空间函数 "foo"
  
my\foo();   // 调用定义在命名空间"A\my"中函数 "foo"
  
F();        // 首先尝试调用定义在命名空间"A"中的函数 "F"
            // 再尝试调用全局函数 "F"
  
// 类引用
  
new B();    // 创建命名空间 "A" 中定义的类 "B" 的一个对象
            // 如果未找到，则尝试自动装载类 "A\B"
  
new D();    // 使用导入规则，创建命名空间 "B" 中定义的类 "D" 的一个对象
            // 如果未找到，则尝试自动装载类 "B\D"
  
new F();    // 使用导入规则，创建命名空间 "C" 中定义的类 "E" 的一个对象
            // 如果未找到，则尝试自动装载类 "C\E"
  
new \B();   // 创建定义在全局空间中的类 "B" 的一个对象
            // 如果未发现，则尝试自动装载类 "B"
  
new \D();   // 创建定义在全局空间中的类 "D" 的一个对象
            // 如果未发现，则尝试自动装载类 "D"
  
new \F();   // 创建定义在全局空间中的类 "F" 的一个对象
            // 如果未发现，则尝试自动装载类 "F"
  
// 调用另一个命名空间中的静态方法或命名空间函数
  
B\foo();    // 调用命名空间 "A\B" 中函数 "foo"
  
B::foo();   // 调用命名空间 "A" 中定义的类 "B" 的 "foo" 方法
            // 如果未找到类 "A\B" ，则尝试自动装载类 "A\B"
  
D::foo();   // 使用导入规则，调用命名空间 "B" 中定义的类 "D" 的 "foo" 方法
            // 如果类 "B\D" 未找到，则尝试自动装载类 "B\D"
  
\B\foo();   // 调用命名空间 "B" 中的函数 "foo"
  
\B::foo();  // 调用全局空间中的类 "B" 的 "foo" 方法
            // 如果类 "B" 未找到，则尝试自动装载类 "B"
  
// 当前命名空间中的静态方法或函数
  
A\B::foo();   // 调用命名空间 "A\A" 中定义的类 "B" 的 "foo" 方法
              // 如果类 "A\A\B" 未找到，则尝试自动装载类 "A\A\B"
  
\A\B::foo();  // 调用命名空间 "A\B" 中定义的类 "B" 的 "foo" 方法
              // 如果类 "A\B" 未找到，则尝试自动装载类 "A\B"
?>
```
