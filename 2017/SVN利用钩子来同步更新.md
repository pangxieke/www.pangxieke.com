---
title: SVN利用钩子来同步更新
id: 447
categories:
  - linux
date: 2015-02-27 20:35:07
tags:
---

我的SVN仓库路径为/opt/svn

创建代码仓库/opt/svn/pangxieke
mkdir /pangxieke
cd /pangxieke
svnadmin create pangxieke

进入hooks目录，创建脚本文件post-commit
cd /opt/svn/pangxieke/hooks
vi post-commit

加入如下代码，意思是让web目录执行svn的update命令
#!/bin/bash
export LANG=en_US.UTF-8
SVN=/usr/bin/svn
WEB=/www/pangxieke
${SVN} update ${WEB} --username XXX--password XXX

编辑完脚本之后 修改权限 chmod +x post-commit

注意：
copy时 #!/bin/bash 不要丢失，会报错。#!/bin/sh 说明是执行shell命令 
export LANG=zh_CN.GBK 是为了解决svn post commit 中文乱码，设置本地化编码,如本地系统为GBK编码,SVN默认是UTF-8编码,如果不设置将会出现错误,而执行不成功,错误标识为svn: Can't convert string from native encoding to 'UTF-8' 

/usr/bin/svn update --username XXX --password XXX /var/www/myproject 执行更新操作 
如果提示:post-commit hook failed (exit code 255) with no output赋予post-commit文件可执行权限 
如果您的默认编码就是UTF-8的，要上传中文文件，先将文件另存为UTF-8格式在提交 