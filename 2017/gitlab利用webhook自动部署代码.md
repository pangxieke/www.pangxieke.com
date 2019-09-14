---
title: gitlab利用webhook自动部署代码
id: 1252
categories:
  - linux
date: 2017-04-18 18:52:18
tags:
---

项目使用了gitlab托管，以前部署代码都是登录服务器，然后git pull代码。这样每次需要发布代码，都需要登录，太繁琐。

很早就了解到gitlab支持webhook，能够自动同步代码。今天尝试搭建成功了。

## 原理介绍

1、配置gitlab当push动作的时候，访问服务器上的一个链接比如pangxieke.com/tb.php

2、tb.php里面写着一行代码，会让服务器git pull相应项目的代码到web目录。

3、pull结束，代码就在web目录了，我们只要重新访问网站就可以了。

核心就是push的时候，gitlab会调用服务器上的脚本，服务器上的脚本就会从git重新拉取项目文件。同时还需要加入安全性的设计。

## 配置ssh密钥

先在服务端生成一对你的SSH密钥，因为之后服务器要用ssh方式免账号密码从gitlab上pull代码。用ssh-keygen在服务器上生成密钥，或者你已经有密钥了就跳过这一步。

因为项目配置的nginx和php的用户为www,所以代码执行时以www用户，所以需要www用户的ssh密钥

### 服务器添加www的密钥

修改www用户，允许登录，项目配置完成后，再修改回nologin

```php
;www:x:22:22:www:/var/www:/usr/sbin/nologin
www:x:22:22:www:/var/www:/bin/bash

su www 
cat ~/.ssh/id_rsa.pub

//如果没有 
ssh-keygen

```

### gitlab中配置ssh密钥

有了密钥之后，复制你的公钥，在你的gitlab profile个人资料里，找到SSH的目录，粘贴保存进去就可以了。这样gitlab上就有了你web服务器的公钥了，就可以正常SSH了。

也可以使用部署密钥。不同的项目，可以共用相同部署密钥，但记得在对应项目设置中启动此密钥
[![gitlab](/images/2017/04/gitlab.png)](/images/2017/04/gitlab.png)

## 服务器脚本

先使用www用户调用sh脚本，如果成功，下一步是通过php执行脚本。
这里我们先使用www用户测试，需要用www用户登录服务器测试。
```php
su www
```
如果不能成功，可能是设置了www用户为nologin，使用上述方法开启即可。

`/home/www` 下放了1个sh脚本， 内容如下
```php
#!/bin/bash
cd /home/www/www.pangxieke.com
/usr/bin/git pull origin master

//或者 强制pull
git fetch --all
git reset --hard origin/master
```

然后使用www用户执行此脚本，测试是否能够获取代码。如果成功后，下一步就是通过钩子执行此脚本

## php执行脚本

php通过exec函数执行脚本代码tb.php。需要调用exec函数，如果发现不成功，有可能php.ini配置中禁用了exec函数，重新开启即可

```php
//作为接口传输的时候认证的密钥
$valid_token = '87ea722e507383fb651ff0515b588b';
//调用接口被允许的ip地址

$client_token = $_GET['token'];
$project = $_GET['project']; //多个项目
$client_ip = $_SERVER['REMOTE_ADDR'];
$fs = fopen('./auto_hook.log', 'a');
fwrite($fs, 'Request on ['.date("Y-m-d H:i:s").'] from ['.$client_ip.']'.PHP_EOL);
if ($client_token !== $valid_token)
{
    echo "error 10001";
    fwrite($fs, "Invalid token [{$client_token}]".PHP_EOL);
    exit(0);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
fwrite($fs, 'Data: '.print_r($data, true).PHP_EOL);
//这里也可以执行自定义的脚本文件update.sh，脚本内容可以自己定义。

if($project == 'pangxieke'){
    $res = exec("/home/www/tb.sh", $result);
}else if($project == 'pangxieke2'){
    $res = exec("/home/www/tb2.sh", $result);
}

fwrite($fs, 'Data: '.print_r($result, true).PHP_EOL);
fwrite($fs, '======================================================================='.PHP_EOL);
$fs and fclose($fs);
var_dump($result);
```

然后通过浏览器访问测试

www.pangxieke.com/tb.php?token=87ea722e507383fb651ff0515b588b&project=pangxieke

如果访问后，能够返回git信息，就是代表成功。这样就可以配置gitlab的触发器

如果失败，可以查看auto_hook.log日志

为支持多个项目 配置了project参数,可以部署多个项目。只需要在gitlab钩子中配置时，使用不同的project参数。
```php
www.pangxieke.com/tb.php?token=87ea722e507383fb651ff0515b588b&project=pangxieke
www.pangxieke.com/tb.php?token=87ea722e507383fb651ff0515b588b&project=pangxieke2
```

## gitlab钩子设置

[![gitlab_webhook](/images/2017/04/gitlab_webhook.png)](/images/2017/04/gitlab_webhook.png)

然后就可以push代码到仓库，然后查看代码是否部署成功

**部署完成记得修改www用户为nologin**