---
title: SVN客户端切换登录用户
id: 599
categories:
  - linux
date: 2015-04-03 15:26:16
tags:
---

## 方案一：

windows环境:
1、在项目上右键，选择TortoiseSVN--&gt;settings,
2、在弹出的TortoiseSVN Settings页面中选择“Saved Data”选项，
3、然后点击“Authentication data”对应的“Clear”按钮，清除一下之前的认证信息就可以了。
下次，就会要求输入用户名和密码，OK！

linux环境：svn co --username xxxxx svn://www.yyy.com/aa/bb

## 方案二：

1、通过删除SVN客户端的账号配置文件
（1）找到我们使用的客户端配置文件，在window xp下面他们的位置在系统盘的 Documents and Settings\alex\Application Data\Subversion\auth\文件夹中，把里面的所有文件删除。
（2）使用SVN更新或提交，使得客户端与服务端进行通讯，这样就会SVN客户端就要求我们输入新的用户名密码，输入我们的用户名密码就可以替换掉旧的用户名密码。

2、通过修改SVN服务端账号配置文件，这部分需要SVN的管理员配合
（1）找到服务端账号配置文件，这个文件位于SVN服务器的安装路径 config文件夹，打开并编辑passwd文件，删除或注释需要被替换的账号
（2）在客户端使用SVN更新或提交，使得客户端与服务端进行通讯，这样就会SVN客户端就要求我们输入新的用户名密码，输入我们的用户名密码就可以替换掉旧的用户名密码。