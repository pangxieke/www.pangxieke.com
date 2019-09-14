---
title: 开源资产管理软件 Snipe-IT
date: 2018.1.18 20:00
category: php
id: open-source-asset-management-snipe-it
---

## 需求
现在公司，办公设备的管理是基本需求。员工相应的笔记本，台式机，门禁卡都必须做好记录。一般公司都是有Execl管理。
但当公司人员庞大，办公地点多样，人员经常流动时，更新Execl表会是一个十分繁琐的工作。
而且很多公司有多个办公地点，多个分公司。需要集体公司同意管理，统一采购设备。

这时候需要专门的系统工具去管理资产。

## Snipe介绍

Snipe-it是一款开源的资产管理系统。在实际工作中，完全替代EXECL表格的资产管理。

支持多语言，方便公司全球化扩张。

官网[https://snipeitapp.com/](https://snipeitapp.com/)

## 在线Demo
官方提供在线Demo，可以先测试。
[https://demo.snipeitapp.com/](https://demo.snipeitapp.com/)

![](/images/2018/01/1516278697897.jpg)
测试账号 admin /password

## 安装
安装步骤
![](/images/2018/01/1516279561137.jpg)

### 安装文档
[https://snipe-it.readme.io](https://snipe-it.readme.io)
有详细的英文安装文档

### 获取代码

代码可以在官网下载https://snipeitapp.com/download/(https://snipeitapp.com/download/)

也可以在Github获取[https://github.com/snipe/snipe-it](https://github.com/snipe/snipe-it)

```
git clone https://github.com/snipe/snipe-it
```

### 配置
Snipe-it 是基于 Laravel 5.4开发
获取代码后，需要获取依赖包
```
composer update
cp .env.example .env

```
修改 `.env`文件
开启调试模式，配置数据库链接（需要先新建数据库）
```
APP_DEBUG=true
APP_KEY=202cb962ac59075b964b07152d234b70

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=snipe
DB_USERNAME=root
DB_PASSWORD=
```

通过浏览器访问项目，自动安装
项目Web入口路径为`Public`



第一步，会自动检测环境
第二布，会自动给数据库插入数据
第三布，配置后台登录账号
![](/images/2018/01/1516278640044.jpg)