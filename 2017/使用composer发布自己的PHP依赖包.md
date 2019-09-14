---
title: 使用composer发布自己的PHP依赖包
id: 1313
categories:
  - php
date: 2017-09-05 21:33:14
tags:
---

## 目标

一直使用composer包管理器，直接使用别人的代码十分方便。但想把自己的一些代码也共享出来。所以想提交自己的包给别人使用。

## 流程

1\. 安装composer

2\. 项目发布到github

3\. 包发布到packagist

4\. 设置packagist包自动同步

### 1\. 安装composer

参考http://www.phpcomposer.com/

### 2\. 项目发布github

参考相关文章

### 3\. 包发布到packagist

1\. 访问https://packagist.org，注册登录，可以使用github账号登录

2\. submit ,此时如果有相同名字的包，会提示，确认

[![](/images/2017/09/packagist.png)](/images/2017/09/packagist.png)

[![](/images/2017/09/packagist_info.png)](/images/2017/09/packagist_info.png)

### 4\. packagist包自动同步

假如你每次更新了项目，还需要到packagist点击update，十分麻烦。所以这里最好设置一个自动同步，当然packagist提供了api的方式来操作，这个也是挺麻烦。packagist和github已经打通了，可以直接在github上设置就行了。ok

在github上打开你的项目，点击setting 》 Installed integrations 》Add Service 选择 packagist，然后填写user、token、domain，token你可以从你的packagist个人页面找到