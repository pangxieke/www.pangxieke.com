---
title: 通过hooks实现Git自动发布
date: 2017.11.1 19:00:00
category: linux
id: server-auto-release-by-git-hook
---

在服务器上，使用Git作为代码仓库，但是每次同步代码后，需要手动去修改Nginx对应的代码仓库，这样十分繁琐。

希望能够`git push`后，代码就能够直接上线。
其实这可以通过git触发器实现。

## 建立目标仓库
```
cd /var/www
mkdir blog
chown git:git blog -R
cd blog
git init
git remote add origin /opt/git/blog.git
git pull origin master
```

## 建立触发器
在git仓库hooks目录下增加文件`post-receive`

```
vi post-receive
```
增加如下内容
```
#!/bin/sh
DEPLOY_PATH=/var/www/blog

unset  GIT_DIR #这条命令很重要
cd $DEPLOY_PATH
git reset --hard
git pull origin master
#chown www:www -R $DEPLOY_PATH
```
增加执行权限
```
chmod +x post-receive
```
注意整个文件的用户，及用户组

## 测试
先手动执行`post-receive`,测试是否能够正常使用
```
./post-receive
```

测试通过后，再真实提交`git push`,测试触发器是否正常执行。
- 触发器是否执行
- 执行后是否达到目的

## 可能错误

### 触发器权限
触发器需要执行权限，才能保证有post后能够触发

### 目标仓库权限不足
git可能没有目标仓库权限，无法在目标仓库`pull`代码

**相关链接** [搭建Git服务器](http://www.pangxieke.com/linux/linux-bulid-git-server.html)



