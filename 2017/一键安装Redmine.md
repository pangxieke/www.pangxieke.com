---
title: 一键安装Redmine
tags:
  - redmine
id: 970
categories:
  - linux
date: 2016-04-13 23:22:59
---

## 1.简介

对于一个新手，按照官方文档来安装redmine，非常复杂。
BitNami提供redmine的一键安装程序，简单、易用、方便。

## 2.安装

BitNami提供redmine的一键安装程序，地址：http://bitnami.org/stack/redmine.不仅仅是windows的有一键安装程序，linux也有一键安装程序。

我的安装环境是windows 7，下载地址https://bitnami.com/redirect/to/98612/bitnami-redmine-3.2.1-0-windows-installer.exe

跟大部分windows安装程序一样，一路默认“下一步”即可。需要注意的是，创建管理员账号的时候，需要将用户名称和密码记录下来，此用户即为redmine安装好后的管理员账号

注意：如果已经安装了apache和mysql，端口号就不能用80端口和3306端口，会提示端口已经被占用。我使用的82端口和3307端口[![readme成功](/images/2016/04/readme成功.jpg)](/images/2016/04/readme成功.jpg)

安装完后会自动打开浏览器，点击“Access BitNami Redmine Stack”即可访问redmine。
输入管理员账号和密码，即可登录redmine。

## 3. 集成git

### 3.1\. 新建版本库

登录redmine，添加用户，新建一个project，新建完成后，在配置-&gt;版本库中选择git，然后在”Path to .git repository”中写入git的路径即可（注意：需要包括.git所在的路径）。
[![readme_git](/images/2016/04/readme_git.jpg)](/images/2016/04/readme_git.jpg)

标准安装的redmine只能访问本地git版本库，不能通过git协议访问git版本库，我们可以通过git clone --mirror克隆镜像到本地的方法来解决。

### 3.2\. 浏览版本库

添加完版本库后，即可在通过edmine在web上浏览版本库，选择项目 &gt; 版本库，等待一下，让Redmine处理下Repository 的信息，然后就可以看到版本库了.
[![gti](/images/2016/04/gti.jpg)](/images/2016/04/gti.jpg)

注意：当配置完版本库第一次访问时, Redmine将抓取版本库中已经存在的所有提交信
息, 并存入数据库。所以如果你的版本库特别大, 那么该过程将会很长。

### 3.3\. git提交和redmine问题关联

## 要实现在gi代码提交的同时关闭问题状态，实现提交跟问题建立关联，需要以管理员身份登陆，依次选择“管理” &gt; “配置” &gt; “版本库”，按下图所示设置，设置完点击保存。
[![git2](/images/2016/04/git2.jpg)](/images/2016/04/git2.jpg)
回到项目中，在项目里新建一个问题，新建完后，如下图所示：
[![git3](/images/2016/04/git3.jpg)](/images/2016/04/git3.jpg)
修改代码，git提交信息中只要包含有“closes #1”字眼的，就会自动关闭问题。如下图 3?5所示，在这里还可以看到某个bug对应改动的代码是什么。
[![git4](/images/2016/04/git4.jpg)](/images/2016/04/git4.jpg)
4\. 其他

我添加了一个大的git版本库（将git://git.kernel.org/pub/scm/git/git.git克隆到本地），想试试性能如何，结果让我很失望，添加完版本库后，去浏览版本库，一直很卡，发现CPU都99%，都被ruby占用了。