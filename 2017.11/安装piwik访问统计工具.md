---
title: 安装piwik访问统计工具
category: linux
date: 2017.11.7 19:00:00
id: web-analytics-tool-of-piwik
---

Piwik是一个PHP和MySQL的开放源代码的Web统计软件，可以代替Google Analytics。而且支持多语言。
通过这个开源产品，可以搭建自己的私有统计平台。
其统计基于javaScript的脚本，将该脚本插入到 <body> 里头的页面，就可以获取到数据。

Piwik 的安装方式超级简单，可以通过页面指示，一键安装。

[百度百科](https://baike.baidu.com/item/piwik/1440455?fr=aladdin)

## 相关资料
官网: [https://piwik.org/](https://piwik.org/)

在线Demo: [demo.piwik.org](http://demo.piwik.org)

Github地址: [https://github.com/piwik/piwik](https://github.com/piwik/piwik)

## 环境要求
- PHP5.5.9以上
- Mysql 5.5以上
- PHP需要安装PDO或者Mysqli扩展
 
## 获取代码
```
git clone https://github.com/piwik/piwik.git
cd piwik
composer update
```
composer时可能会出错，需要修改`php.ini`
`php.ini`文件中
`disable_functions`去除`proc_open`,`proc_get_status`,`shell_exec`
重启php服务`service php-fpm restart`

## 配置nginx
配置Nginx服务，配置域名，方便后面访问页面，直接安装
例如
```
server{
        listen 80;
        server_name piwik.pangxieke.com;
        index index.html index.htm index.php;

        root /var/www/piwik;
        include enable-php.conf;
}
```

## 一键安装
访问配置好的域名，如`piwik.pangxieke.com`,
会显示安装页面

![](/images/2017/11/20171106182710.png)

#### 选择安装语言
![](/images/2017/11/lanuage.png)

#### 安装环境检测
可能会遇到环境错误

![](/images/2017/11/shell.png)
此时需要修改`php.ini`

#### 配置数据库

![](/images/2017/11/20171106182641.png)

#### 配置站点跟踪代码，也可以稍后在后台配置

#### 配置后台账号

## 后台
![](/images/2017/11/dashboard.png)
后台首页的布局可以随意拖动，或者添加栏目(小工具)

添加小工具，可以把常用的图表都直接在首页上列出来

统计图表基于flash，有动态的现实效果，还能随意切换，列表，柱形，饼型。

主要功能
- 访客分析
- 页面分析
- 来源分析
- 目标分析

如有兴趣，可以访问官方在线Demo: [demo.piwik.org](http://demo.piwik.org)
