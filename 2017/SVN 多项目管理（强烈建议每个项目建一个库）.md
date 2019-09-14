---
title: SVN 多项目管理（强烈建议每个项目建一个库）
id: 443
categories:
  - linux
date: 2015-02-27 20:35:18
tags:
---

Subversion的目录结构是很自由的，所有的规划都必须是你自己规定，考虑一个 subversion仓库的目录树，你可以把任何一个目录认定为一个项目，你可以只checkout这个目录下的所有文件进行编码，跟CVS不同，CVS显式指定一个个module。所以你可以在一个仓库内保存 多个项目，也可以一个仓库保存一个项目而使用多个仓库。我个人比较喜欢第二种，因为 Subversion的每次commit都会导致整个仓库 版本号增加一个，会使得 多个项目的 版本号出现断层。而且如果 多个项目参与人不同，就必须使用apache2进行细粒度的权限控制，不是太方便。一个仓库一个项目，显得更优雅一些。

以下是我研究出的仓库规划。

在server端，新建一个目录用来存放所有的仓库。比如c:\svnrepos。然后在这个目录下建立每个项目独立

svnadmin create /opt/svn/pangxieke
svnadmin create /opt/svn/pangxieke2

使用 svnserve -d -r /opt/svn 启动。这样你的项目的url是：
svn://IP/pangxieke
svn://IP/pangxieke2

Linux下检出(checkout)
svn  checkout  http://路径(目录或文件的全路径)　[本地目录全路径] --username　用户名
svn  checkout  svn://路径(目录或文件的全路径)　[本地目录全路径]  --username　用户名

例如：svn checkout svn://127.0.0.1/pangxieke pangxieke

在Window下，
检出路径 svn://Ip/pangxieke/