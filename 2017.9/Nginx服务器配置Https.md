---
title: Nginx服务器配置Https
date: 2017.9.28
category: linux
id: 1339
---

## SSL 证书和Https
### SSL证书
SSL 证书是一种数字证书，它使用 Secure Socket Layer 协议在浏览器和 Web 服务器之间建立一条安全通道，从而实现：
1、数据信息在客户端和服务器之间的加密传输，保证双方传递信息的安全性，不可被第三方窃听；
2、用户可以通过服务器证书验证他所访问的网站是否真实可靠。

### Https
HTTPS 是以安全为目标的 HTTP 通道，即 HTTP 下加入 SSL 加密层。HTTPS 不同于 HTTP 的端口，HTTP默认端口为80，HTTPS默认端口为443。

## 生成证书
```
# 生成一个RSA密钥 
openssl genrsa -des3 -out server.key 1024

# 拷贝一个不需要输入密码的密钥文件
openssl rsa -in server.key -out server_nopass.key

# 生成一个证书请求
#会提示输入省份、城市、域名信息等，重要的是，email一定要是你的域名后缀的
openssl req -new -key server.key -out server.csr 

# 自己签发证书
openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt

```

## 配置Nginx
移动证书文件
```
cp server.crt  /etc/nginx/conf.d/
cp server.csr /etc/nginx/conf.d/
cp server.key /etc/nginx/conf.d/

```
配置nginx
```
server {
    server_name www.pangxieke.com;
    listen 443;
    ssl on;
    ssl_certificate /etc/nginx/conf.d/server.crt;
    ssl_certificate_key /etc/nginx/conf.d/server_nopass.key;
    # 若ssl_certificate_key使用server.key，则每次启动Nginx服务器都要求输入key的密码。
    index index.html index.htm index.php;
    root /www/hexo/pangxieke;
    location / {
         index index.php index.html index.htm;
    }
```

## 测试
启Nginx后即可通过https访问网站了。

自行颁发的SSL证书能够实现加密传输功能，但浏览器并不信任，会出现以下提示
![](/images/2017/09/https.png)

## 更多配置
有一些开发框架会根据 `$_SERVER['HTTPS']` 这个 PHP 变量是否为 on 来判断当前的访问请求是否是使用 https。为此我们需要在 Nginx 配置文件中添加一句来设置这个变量。
添加
```
fastcgi_param HTTPS on
```
即为这样
```
server {
    ...
    listen 443;
    location \.php$ {
        ...
        include fastcgi_params;
        fastcgi_param HTTPS on; # 多加这一句
    }
}
```