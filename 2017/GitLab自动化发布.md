---
title: GitLab自动化发布
id: 919
categories:
  - linux
date: 2016-03-11 17:24:16
tags: gitlab
---

钩子(hooks)
Git是在特定事件发生之前或之后执行特定脚本代码功能（从概念上类比，就与监听事件、触发器之类的东西类似）。
Git Hooks就是那些在Git执行特定事件（如commit、push、receive等）后触发运行的脚本。
gitlab的web hooks跟git hook类似。也是当项目发生提交代码、提交tag等动作会自动去调用url，这个url可以是更新代码。或者其他操作。

配置目的：
由于系统属于后台接口系统，开发提交完git仓库后要实时的部署到测试环境，这时候就需要用到gitlab的web hooks自动更新部署了。
客户端：要自动更新的测试服务器IP：192.168.1.2
服务端：Gitlab服务器IP：192.168.1.1
Gitlab Version: 7.13.0.pre
GitLab-Shell Version: 2.6.3

1、在客户端上面配置apache配置文件，为web hooks添加一个接口访问
```
#vim /usr/local/apache/conf/httpd.conf
listen 81
ServerAdmin localhost
DocumentRoot “/www/gitlab_web”
<Directory “/www/gitlab_web”>
Options -Indexes +FollowSymLinks
AllowOverride None
Order allow,deny
Allow from all
RewriteEngine on
SSH Keys –>add ssh key)
#su – webuser
#ssh-keygen -t rsa
# 由于项目以前有配秘钥，直接复制~/.ssh/authorized_keys中的公钥到gitlab
进入项目目录
#cd /path/project
初始化git仓库
#git clone git@192.168.1.1:test/test_api.git
```

3、在客户端上面添加接口文件
`#vim /www/gitlab_web/index.php`
```
<?php
//作为接口传输的时候认证的密钥 
$valid_token = 'd49dfa762268687eb2ca59498ce852'; 
//调用接口被允许的ip地址 
$valid_ip = array('192.168.1.1','10.17.10.175','112.112.112.112'); 
$client_token = $_GET['token']; $client_ip = $_SERVER['REMOTE_ADDR']; 
$fs = fopen('./auto_hook.log', 'a'); 
fwrite($fs, 'Request on ['.date("Y-m-d H:i:s").'] from ['.$client_ip.']'.PHP_EOL); 
if ($client_token !== $valid_token) {    
 	 echo "error 10001";     
 	 fwrite($fs, "Invalid token [{$client_token}]".PHP_EOL);     
 	 exit(0); 
} 
if ( ! in_array($client_ip, $valid_ip)) {     
	echo "error 10002";     
	fwrite($fs, "Invalid ip [{$client_ip}]".PHP_EOL);    
	 exit(0); 
 } 
 $json = file_get_contents('php://input'); 
 $data = json_decode($json, true); 
 fwrite($fs, 'Data: '.print_r($data, true).PHP_EOL); 
 fwrite($fs, '======================================================================='.PHP_EOL); 
 $fs and fclose($fs); 
 //这里也可以执行自定义的脚本文件update.sh，脚本内容可以自己定义。 
 //exec("/bin/sh /root/updategit.sh"); 
 exec("cd  /path/project;/usr/bin/git pull"); 

```
4、访问接口，测试接口是否成功
例如 `http://192.168.1.2:81/?token=d49dfa7622681425fbcbdd687eb2ca59498ce852 `

5、查看客户端日志
 ```
 #cat /www/gitlab_web/auto_hook.log 
 ======================================================================= 
 Request on [2015-07-03 14:05:02] from [112.122.112.112] Data:  
 =======================================================================
 ```

6、在服务端gitlab服务器上面添加`web hooks admin area--->projects->test/edit->WEB Hooks->add WEB Hooks`

7、提交修改代码到gitlab仓库，然后查看日志、查看测试环境是否更新
`#cat /www/gitlab_web/auto_hook.log`

注意事项：
1、配置完成后。调用接口的时候没有自动更新到测试环境。可以使用apache的运行用户测试命令是否可以执行成功
```
#su - webuser
#cd /path/project
#git pull
```

2、如果apache的用户无法执行命令或者无法更新git代码请检查一下apache用户的shell。

3、自己尝试好久，还没有完全弄通过，问题还是出现在php执行exec命令上

[http://fighter.blog.51cto.com/1318618/1670667/](http://fighter.blog.51cto.com/1318618/1670667/ "http://fighter.blog.51cto.com/1318618/1670667/")
[自動化更新版本：使用 Gitlab Web Hook](http://blog.ycnets.com/2013/10/19/automatic-update-version-with-gitlab-web-hook/#disqus_thread "自動化更新版本：使用 Gitlab Web Hook")