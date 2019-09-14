---
title: centos下安装Mattermost
id: 1274
categories:
  - linux
date: 2017-06-28 19:48:05
tags:
---

Mattermost 是一个 Slack 的开源替代品。

Mattermost 采用 Go 语言开发，这是一个开源的团队通讯服务。为团队带来跨 PC 和移动设备的消息、文件分享，提供归档和搜索功能。
[![](/images/2017/06/mattermost.png)](/images/2017/06/mattermost.png)
一、下载代码

```php
wget https://releases.mattermost.com/3.10.0/mattermost-3.10.0-linux-amd64.tar.gz
tar -xvzf mattermost-3.10.0-linux-amd64.tar.gz
cd mattermost
sudo mv mattermost /home/www
sudo chown www:www /home/www/mattermost -R
```

二、Mysql配置

```php
create user 'mmuser'@'localhost' identified by 'mmuser-password';
create database mattermost;
grant all privileges on mattermost.* to 'mmuser'@'%';
```

三、mattermost配置

```php
vi /home/www/mattermost/config/config.json
#修改数据库连接
#Set "DriverName" to "mysql"
"DataSource": "mmuser:mmuser-password@tcp(localhost:3306)/mattermost?charset=utf8mb4,utf8\u0026readTimeout=30s\u0026writeTimeout=30s",
```

四、Nginx配置

```php
cd /usr/local/nginx/conf/vhost/
sudo vi mattermost.conf
```

下面是mattermost.conf 配置

```php
upstream backend {
   server localhost:8065;
}

proxy_cache_path /var/cache/nginx levels=1:2 keys_zone=mattermost_cache:10m max_size=3g inactive=120m use_temp_path=off;

server {
   listen 80;
   server_name    mattermost.pangxieke.com;

   location ~ /api/v[0-9]+/(users/)?websocket$ {
       proxy_set_header Upgrade $http_upgrade;
       proxy_set_header Connection &quot;upgrade&quot;;
       client_max_body_size 50M;
       proxy_set_header Host $http_host;
       proxy_set_header X-Real-IP $remote_addr;
       proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
       proxy_set_header X-Forwarded-Proto $scheme;
       proxy_set_header X-Frame-Options SAMEORIGIN;
       proxy_buffers 256 16k;
       proxy_buffer_size 16k;
       proxy_read_timeout 600s;
       proxy_pass http://backend;
   }

   location / {
       client_max_body_size 50M;
       proxy_set_header Connection &quot;&quot;;
       proxy_set_header Host $http_host;
       proxy_set_header X-Real-IP $remote_addr;
       proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
       proxy_set_header X-Forwarded-Proto $scheme;
       proxy_set_header X-Frame-Options SAMEORIGIN;
       proxy_buffers 256 16k;
       proxy_buffer_size 16k;
       proxy_read_timeout 600s;
       proxy_cache mattermost_cache;
       proxy_cache_revalidate on;
       proxy_cache_min_uses 2;
       proxy_cache_use_stale timeout;
       proxy_cache_lock on;
       proxy_pass http://backend;
   }
}
```

五、启动服务

```php
sudo service nginx restart
cd /home/www/mattermost/bin
./platform
```

六、访问测试

访问mattermost.pangxieke.com

或者http://xxx.xxx.xxx.xxx:8685