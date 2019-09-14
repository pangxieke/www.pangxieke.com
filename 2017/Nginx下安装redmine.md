---
title: Nginx下安装redmine
id: 1166
categories:
  - linux
date: 2016-12-19 11:02:59
tags:
---

[![1481508824](/images/2016/12/1481508824.jpg)](/images/2016/12/1481508824.jpg)
Redmine是一个不错的项目管理工具，是用RUBY开发的基于WEB的项目管理软件，提供项目管理、WIKI、新闻台等功能，集成版本管理系统GIT、SVN、CVS等等。通过WEB 形式把成员、任务、文档、讨论以及各种形式的资源组织在一起，推动项目的进度。

以前在Window下安装过，现在在Linux上安装使用。

Window下可一键安装，参考本站[**一键安装Redmine**](http://www.pangxieke.com/linux/970.html)

## 1.安装ruby环境，Redmine是基于Ruby on rails开发的

```php
\curl -L https://get.rvm.io | bash
source /etc/profile.d/rvm.sh

#查询版本
rvm list known
#可以看到有很多版本，这里安装2.2版本
rvm install 2.2
#查看
ruby -v
```

## 2.安装rails

```php
gem install rails
#（可能会遇到墙，如果遇到墙就换成淘宝源http://ruby.taobao.org/）

#查看版本
rails -v
```

## 3.下载redmine

官网 http://www.redmine.org/
```php
wget http://www.redmine.org/releases/redmine-2.6.0.tar.gz

tar zxvf redmine-2.6.0.tar.gz
```

## 4.依赖组件安装

```php
cd redmine-2.6.0

gem install bundler

bundle install --without development test rmagick #有墙，速度非常慢
```

## 5.配置redmine连接数据库

```php
#复制 config/database.yml.example 到 config/database.yml

cp config/database.yml.example  config/database.yml 

vi config/database.yml
#配置production对应的数据库，密码

```

## 6.创建一个session安装密钥

```php
rake generate_secret_token
```

## 7.生成初始化所有table

```php
RAILS_ENV=production rake db:migrate
```

## 8.装入默认的配置信息，输入zh（选择中文）

RAILS_ENV=production rake redmine:load_default_data

## 9.启动redmine

```php
ruby script/rails server webrick -e production
#提示 script/rails 不存在，使用bin/rails
ruby bin/rails server webrick -e production

#后台运行 -d
ruby bin/rails server webrick -e production -d
```

## 10.开机自动启动

```php
#编辑启动文件

vi /etc/rc.local

#最后一行或者适当的位置，加入一下内容。此处必须用绝对路径。注意根据实际redmine路径来填写。

/usr/local/rvm/rubies/ruby-1.9.3-p551/bin/ruby /root/redmine-2.6.0/script/rails server webrick -e production -d

```

## 11.配置Nginx模块

```php
#获取passenger路径
passenger-config --root
# /usr/local/rvm/gems/ruby-2.2.6/gems/passenger-5.0.30

#重新编译nginx，添加passenger模块
passenger-install-nginx-module

#命令passenger-install-nginx-module重新安装nginx。

#也可以编译安装
./configure --user=www --group=www --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module --with-http_gzip_static_module --with-ipv6 --add-module=/usr/local/rvm/gems/ruby-2.2.6/gems/passenger-5.0.30/ext/nginx/
```

## 12.nginx配置upstream

```php
upstream redmine {
    server 127.0.0.1:3000;
}

server {
    server_name wiki.pangxieke.com;
    root /usr/local/redmine;

    location / {
        try_files $uri @ruby;
    }

    location @ruby {
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Host $http_host;
        proxy_redirect off;
        proxy_read_timeout 300;
        proxy_pass http://redmine;
    }
}
```   

参考文献
[**http://www.redmine.org/projects/redmine/wiki/RedmineInstall**](http://www.redmine.org/projects/redmine/wiki/RedmineInstall)
[**Redmine官网http://www.redmine.org/**](http://www.redmine.org/)