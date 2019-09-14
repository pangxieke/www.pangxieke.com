---
title: git配置两个SSH-Key
id: 908
categories:
  - linux
date: 2016-02-24 18:19:56
tags: git
---

我们在日常工作中会遇到公司有个gitlab，还有些自己的一些项目放在github上。这样就导致我们要配置不同的ssh-key对应不同的环境。下面我们来看看具体的操作：

1，生成一个公司用的SSH-Key

```php
$ ssh-keygen -t rsa -C "youremail@yourcompany.com" -f ~/.ssh/id_rsa
```

在~/.ssh/目录会生成id_rsa和id_rsa.pub私钥和公钥。 我们将id_rsa.pub中的内容粘帖到公司gitlab服务器的SSH-key的配置中。

2，生成一个github用的SSH-Key

```php
$ ssh-keygen -t rsa -C "youremail@your.com" -f ~/.ssh/id_rsa_github
```

在~/.ssh/目录会生成id_rsa_github和id_rsa_github私钥和公钥。 我们将id_rsa_github中的内容粘帖到github服务器的SSH-key的配置中。

3，添加私钥

```php
$ ssh-add ~/.ssh/id_rsa $ ssh-add ~/.ssh/id_rsa_github
```

如果执行ssh-add时提示`"Could not open a connection to your authentication agent"`，可以现执行命令：

```php
$ ssh-agent bash
```

然后再运行`ssh-add`命令。

```php
# 可以通过 ssh-add -l 来确私钥列表
$ ssh-add -l
# 可以通过 ssh-add -D 来清空私钥列表
$ ssh-add -D
```

4，修改配置文件

在 `~/.ssh` 目录下新建一个`config`文件

```php
vi config
```

添加内容：

```php
# gitlab
Host gitlab.com
    HostName gitlab.com
    PreferredAuthentications publickey
    IdentityFile ~/.ssh/id_rsa
# github
Host github.com
    HostName github.com
    PreferredAuthentications publickey
    IdentityFile ~/.ssh/id_rsa_github
```

5.测试

```php
$ ssh -T git@github.com
```

输出

`Hi stefzhlg! You've successfully authenticated, but GitHub does not provide shell access.`
就表示成功的连上github了.也可以试试链接公司的gitlab.

6.配置以ip地址的远程仓库
在120.24.63.25上配置了一个代码库
此时需要修改`config`文件
vi config文件
```
Host 120.24.63.25
    User git
    PreferredAuthentications publickey
    IdentityFile ~/.ssh/id_rsa_120
```
需要指定User为git