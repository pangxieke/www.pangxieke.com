---
title: OpenCart index.php分析
id: 1005
categories:
  - php
date: 2016-05-18 20:48:20
tags: opencart
---

[![](/images/2016/05/opencart.png)](/images/2016/05/opencart.png)
OpenCart 是一套比较简单的MVC架构的php开源电子商务程序.

OpenCart 首先将前台和后台完全分离开来，后台文件结构在admin/目录下； 而前台在catalog/目录，入口文件`index.php`在根目录下。各自都有一个可以做不同设置的配置文件`config.php`，在这里设置一些目录路径常量、数据库信息等。
下面以OpenCart前台为例看看文件结构和MVC模式：单一入口，`index.php` 为入口文件

OpenCart基于MVC(+L)架构，在原始的OpenCart项目中，网站的主页是

```catalog/controller/common/home.php```

opencart程序结构：

admin 后台管理目录
  -controller 程序逻辑控制目录
  -model 程序模型目录
  -view 程序模板目录
  -language 语言包目录
  - index.php 管理后台入口
  - 
catalog 程序逻辑，模型，试图目录
  -controller 程序逻辑控制目录
  -model 程序模型目录
  -view 程序模板目录
  -language 语言包目录
sysytem 程序主要文件目录
image 图片目录
index.php 网站入口

`index.php` 入口文件分析

1.载入配置文件，安装。

载入配置文件`config.php`
检测是否新安装，是则跳转到 `install/index.php`
载入启动类(/system/startup.php)（程序引擎system/engine / 核心类system/library / helper类system/helper）
载入应用程序模块类（自动预加载的常用模块类，如customer 和tax 等）

2.启动引擎 `Engine`

引入`engine/registry.php` 实例化下面的类并设置
实例化加载器`engine/loader.php`，配置类`library/config.php`，数据库`library/db.php` 以及`library/url.php`
获取商店Store和商店设置Settings，定义错误类

3.处理请求 `request/response`

实例化`library/request.php`，`library/response.php`，`library/cache.php`, `library/sesshion.php`
语言检测，设置语言类library/language.php
其他常用预加载模块类的实例化和引入，如library/document.php，customer，tax等

4.前台控制器 `/engine/front.php`

addPreAction1: dispatch之前判断是否处于Maintenance Mode
addPreAction2: dispatch之前执行SEO url设置 `catalog/controller/common/seo_url.php`
request获取route 变量传递给Action，然后由前台控制器的dispatch方法处理该Action。
然后Dispatch 开始引入MVC 架构，整个过程由 Controller 贯穿：

5.Dispatch
Dispatch 请求的Action

6.Pre_Action
预处理action 作为子 Action

7.Action
继承自基础控制器类`/engine/controller.php`的类方法

8.默认index()方法
通过Load Model(`engine/loader.php`)和相应的Model通信如`catalog/model/catalog/category.php`

9.Model
Model：类方法执行sql语句，从数据库(`library/db.php`)中查询或操作数据，没有引入pdo等数据库抽象类

10.Controller：返回结果保存到`$this->data[]`数组
Controller 处理业务逻辑

11.View：`$this->template`指定模板
View 负责内容呈现方式和样式。

12.Controller：`$this->render`接受`$this->data[]` 和`$this->template`
传递回response

13..Controller：`$this-response->setOutput()`
最后由response 输出