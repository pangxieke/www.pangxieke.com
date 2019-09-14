---
title: 在APACHE服务器上的访问方式上去除index.php
id: 894
categories:
  - php
date: 2016-01-07 17:26:19
tags: apache
---

在apache 下 ，如何 去掉URL 里面的 index.php 
例如: 你原来的路径是： `localhost/index.php/index `
改变后的路径是: `localhost/index `

1.`httpd.conf`配置文件中加载了`mod_rewrite.so`模块 //在APACHE里面去配置 
```
#LoadModule rewrite_module modules/mod_rewrite.so #把前面的警号去掉 
```

2.在APACHE里面去配置 ，将里面的`AllowOverride None`都改为`AllowOverride All`

注意：修改之后一定要重启apache服务。 

3.确保URL_MODEL设置为2， (url重写模式)

在项目的配置文件里写 
```
return Array( 
‘URL_MODEL’ => ’2′, 
); 
```
4 `.htaccess`文件必须放到跟目录下 

这个文件里面加： 
```
RewriteEngine on 
RewriteCond %{REQUEST_FILENAME} !-d 
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteRule ^(.*)$ index.php/$1 [L] 
```
补充: windows 里面不能创建 `.htaccess` ， 下面我说下创建方法 
新建任何一个文件，然后打开， 点击另存为 (文件类型选择所有)，这样就可以创建了 