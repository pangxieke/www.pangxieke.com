---
title: CentOS(Linux)搭建SVN服务器
id: 452
categories:
  - linux
date: 2015-02-27 20:32:10
tags:
---

网络软件项目中Linux服务器中的CentOS已经项目管理工具SVN是中级程序员必须掌握的基础技能.也是项目开发和管理的利器,工欲善其事必先利其器,现在我们开始构建我的项目吧.初期达到的效果是:CenOS服务器安装运行SVN,创建项目版本库,本地windows使用svn客户端更新提交代码,CentOS端使用命令行进行检出更新提交代码等.

CentOS(Linux)搭建SVN服务器和SVN的使用方法

第一步:在CentOS上面安装SVN,并启动SVN.
```php
//yum自动安装
yum update                  //更新一下yum版本库
yum install subversion      //安装svn

//判断是否安装成功
svn help                    //svn的帮助手册
svn --version               //看到svn的版本和基本信息 
svn --version --quiet       //看到svn的版本

rpm -ql subversion             //查看svn的安装位置

#查看服务是否正常起来：  
netstat -tunlp | grep svn  
tcp 0 0 0.0.0.0:3690 0.0.0.0:*   
EN 3970/svnserve  
#LISTEN 监听端口3690  

----------------------------------------
//若你之前已安装了svn或者想重新安装svn的话请看下面,判断是否成功请看上面
rpm -qa subversion          //检查是否安装了低版本的SVN
yum remove subversion       //卸载旧版本SVN
yum install subversion      //安装svn
```
第二步:创建代码库,并配置代码库(demo)
```php
mkdir -p /opt/svn/demo                  //新建一个项目目录
svnadmin create /opt/svn/demo           //创建SVN项目   

//执行上面的命令后,自动建立demo库,查看/opt/svn/demo 
//文件夹发现包含了conf, db,format,hooks, locks等文件,说明一个SVN库已经建立。

//进入上面生成的文件夹conf下，进行配置 
cd /opt/svn/demo/conf

//用户密码passwd配置
cd /opt/svn/demo/conf
vim passwd
//修改passwd为以下内容：

[users]
# harry = harryssecret
# sally = sallyssecret
dodobook=123456

//权限控制authz配置
cd /opt/svn/demo/conf
vim authz
//目的是设置哪些用户可以访问哪些目录，向authz文件追加以下内容：

//设置[/]代表根目录下所有的资源 
[/]
dodobook = rw

//服务svnserve.conf配置
cd /opt/svn/demo/conf
vim svnserve.conf

//追加开启以下内容：【采用默认配置. 以上语句都必须顶格写, 左侧不能留空格, 否则会出错.】
[general]
anon-access=none        #匿名访问的权限，可以是read,write,none,默认为read
auth-access=write       #使授权用户有写权限 
password-db=password    #密码数据库的路径 
authz-db=authz          #访问控制文件 
realm=/opt/svn/demo     #认证命名空间subversion会在认证提示里显示， 
```
第三步,启动svn连接
```php
svnserve -d -r /opt/svn/       //启动SVN 其他项目不换端口的话则不需要再启动

svnserve -d -r /opt/svn/other --listen-port 3391 //已经有svn在运行，可以换一个端口运行
//这样同一台服务器可以运行多个svnserver,请检查防火墙,建议用一个即可

ps -ef|grep svn|grep -v grep        //查看SVN进程

killall svnserve                    //停止SVN
svnserve -d -r /opt/svn/        // 启动SVN

/usr/bin/svnserve --daemon --pid-file=/var/run/svnserve.pid     //另外一种启动方式

```

注意：开放服务器端口 

svn默认端口是3690，你需要在防火墙上开放这个端口。 
```php
/sbin/iptables -A INPUT -i eth0 -p tcp --dport 3690 -j ACCEPT 

/sbin/service iptables save 
```

第四步:本地环境(windows)下测试
最直接的可以使用浏览器 https://111.111.111.234/opt/svn/demo 直接访问. 以Web方式【http协议】访问，一般还要安装配置Apache

最常规的是本地安装TortoiseSVN,连接地址为 svn://111.111.111.234/demo,第一次的时候SVN检出,键入地址,自动生成svn本地地址目录.接下来就可以使用常规的更新和提交了.建议每次提交之前先更新.另外office的文件系统需要本地关闭之后才能提交正确.关于TortoiseSVN的语言包和下载版本,请根据你的操作系统等进行系在.使用说明也很多.择日再详述.

第五部:其实做本地项目到第四步就可以止步了.
当需要部署代码项目的正式环境的时候,在第四部的时候检出代码,就是干净的代码,部署到环境上,即为发布.

但是小项目在开发过程中,需要多人协作,也可能CentOS上面的环境也是一个svn的用户.当本地用户提交了代码,就需要去服务器上面更新一下svn的代码了.(运维高手有构建系统能自动同步到线上.)

[原文](http://www.dodobook.net/linux/1171 "原文")