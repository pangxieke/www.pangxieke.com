---
title: API接口通过设置Access-Control-Allow-Origin实现跨域访问
id: 1304
categories:
  - share
date: 2017-08-23 18:34:24
tags:
---

例如：客户端的域名是www.client.com,而请求的域名是www.server.com
如果直接使用ajax或者Api访问，会有以下错误

```php
XMLHttpRequest cannot load http://www.server.com/server.PHP.
No 'Access-Control-Allow-Origin' header is present on the requested resource.
Origin 'http://www.client.com' is therefore not allowed access.
```

## 解决方法

在被请求的Response header中加入

```php
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
```

Access-Control-Allow-Origin:*表示允许任何域名跨域访问

如果需要指定某域名才允许跨域访问，只需把
Access-Control-Allow-Origin:*改为Access-Control-Allow-Origin:允许的域名

例如：
header('Access-Control-Allow-Origin:http://www.client.com');