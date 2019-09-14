---
title: OpenCart入门及MVC分析
id: 1008
categories:
  - php
date: 2016-05-19 19:50:27
tags: opencart
---

![](/images/2016/05/opencart.png)
最近一直在用opencart做一个项目，研究各文档，了解了一下opencart的MVC结构。

## 一、OpenCart类

opencart中的类均可通过$this->library_name在 Controller, Model 以及 Views层调用 。而这些类的文件位置在 `/system/library/`下. 比如,要获取当前购物车中的产品，你需要使用 Cart 类,文件路径为 `/system/library/cart.php` ，你可以用 `$this->cart->etProducts()`来调用。

常用的类
```php
customer.php - 用户
user.php - 管理员
cart.php - 购物车
config.php - 设置
url.php - URL函数
OpenCart路由
```
OpenCart的框架根据URL请求中的route=aaa/bbb/ccc参数部分来确定载入的页面，大多数的路由仅仅包含两个部分（aaa/bbb），少数会包含3个部分（aaa/bbb/ccc），第一部分aaa 通常为包含控制器controller或者模板template文件的文件夹。第二部分则为文件的名称，并且省去了后缀名.php 或 .tpl，第三部分则为控制器名称。

## 二、OpenCart语言文件

opencart的语言文件被放置在文件夹 `/catalog/language/your-language` 下，通常公共的语言存放在不同的`your-language.php`文件中，而一些特定页面的语言则需要route路由，比如，搜索页面的URL为`product/search`,因此，你能在`catalog/language/english/product/search.php`文件中写入相应的内容。

在控制器中加载语言文件可以使用下面的代码：
```php
$this->language->load('product/search');
```
接着你可以使用函数 get 来获取语言文件内的文本值，比如：
```php
$some_variable = $this->language->get('heading_title');
```
在语言文件中的变量使用前缀 $_ 命名，在文件 `/catalog/language/english/product/search.php` 中你能够找到类似下面的赋值:
```php
$_['heading_title'] = 'Search';
```
全局性的公共语言文件 `english/english.php` 被自动加载， 我们可以不需要使用 `$this->language->load` 方法来调用该语言文件。

## 三、OpenCart控制器

控制器依据路由 route 来装载，控制器的文件放在`/catalog/controller/`中，继续上面的例子，搜索页面 `/product/search.php` 也存在这个文件夹里， 文件的后缀使用了 .php。

打开控制器文件，你会发现控制器以驼峰方式命名，控制器的名称为ControllerProductSearch，Controller后面是次级文件夹和文件的名称组合。控制器内的方法被申明为public才能够通过路由访问，如果为private则不行。默认情况下，一个标准的路由包括两部分(aaa/bbb above),index()方法默认被调用。

如果路由中使用了第三部分(ccc )，它将代替默认的方法而被调用。比如`account/return/insert` 将装载控制器文件`/catalog/controller/account/return.php`和里面的类，并且会尝试调用insert()方法。

## 四、OpenCart模型Model

OpenCart中的模型层Model被放置在`/catalog/model/`文件夹中，你可以在控制器通过如下代码调用

```php
$this->load->model('xxx/yyy');
```

它将装载 xxx目录下名为 `yyy.php`的文件。接下来你可以使用下面的代码调用model中的方法

```php
$this->model_xxx_yyy
```

并且这种调用的方式只能在申明为public 控制器类中的方法。比如，重新设置一张图片的尺寸，你能够使用tool/image内的model，然后调用model中的resize 方法：

```php
$this->load->model('tool/image');
$this->model_tool_image->resize('image.png', 300, 200);
```

## 五、OpenCart的控制器

在OpenCart的视图View层中使用控制器Controller层内定义的变量
为了从controller层传递值到视图，你需要使用$this-&gt;data 定义变量，本质上就是键值对 key =&gt; value，比

```php
//控制器中定义
$this->data['example_var'] = 123;
```

这和php函数extract() 类似（将数组的键-值转化为变量-值形式），所以键 'example_var'在视图中将转变为$example_var变量。

```php
//视图中
<html>
    <?php echo $example_var;?>
</html>
```

## 六、OpenCart 主题themes

主题目录分为前台和后台，前台主题由templates、css、image以及js文件夹构成，被放置在`/catalog/view/theme/your-theme`。

后台目录则放在 `/admin/view/template/` (opencart不允许更改后台的主题。)。如果当前设置主题中的模板文件不可用，那么默认的文件夹模板将被用做后备，这意味着主题能够使用尽量少的文件，同时也能保证功能都可以使用。

## 七、OpenCart 视图views

和语言文件与模型model一样，视图文件和路由也没联系。在`/catalog/view/theme/your-theme/template/`中，默认的主题default将被调用。

以上面的search页面为例，product/search.tpl是该页面的模板。如果路由部分包含三个部分，那么视图也将以包括三个部分aaa/bbb_ccc.tpl来命名，但这不是强制性的。在后台中，比如产品列表页面，就被命名为 catalog/product_list.tpl。产品编辑页面则被命名为 catalog/product_form.tpl。

模板文件其实也是另一种php文件，只不过后缀名为.tpl，它将在控制器中运行。因此，所有在控制器运行的代码都能在模板文件中运行（除非必要，否则不建议这样做）。

## 八、OpenCart 数据库对象

Opencart中通常这样运行mysql语句：

```php
$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "table`");
```

DB_PREFIX是数据库中数据表的前缀。$result将返回SELECT语句请求的资源:

```php
//以关联数组的形式返回第一行数据
$result->row
 
//将结果以数组形式返回，使用foreach语句可以获取每一行数据
$result->rows
 
//返回结果的行数
$result->num_rows
 
// $this->db对象的一些额外方法
 
//返回更新、插入等语句影响的行数
$this->db->countAffected
 
//返回最后递增的id值，<a href="http://php.net/mysql_insert_id">mysql_insert_id()函数</a>
$this->db->getLastId
 
//转义插入数据，<a href="http://php.net/mysql_real_escape_string">mysql_real_escape_string()函数</a>
$this->db->escape()
```

## 九、OpenCart中的预定义变量

OpenCart 定义了一些变量来代替标准的php全部变量： `$_GET`, `$_POST`, `$_SESSION`, `$_COOKIE`, `$_FILES`, `$_REQUEST` 及`$_SERVER`

`$_SESSION` 使用 `$this->session->data` 修改，与关联数组`$_SESSION`的方法类似。
所有其他的变量使用`$this->request`获取（已经考虑到了php设置中的magic quotes enabled/disabled），

```php
$_GET => $this->request->get
$_POST => $this->request->post
$_COOKIE => $this->request->cookie
$_FILES => $this->request->files
$_REQUEST => $this->request->request
$_SERVER => $this->request->server

```