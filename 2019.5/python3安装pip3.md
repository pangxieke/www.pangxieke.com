---
title: python3安装pip3
id: python3安装pip3
category: linux
tags: python
date: 2019-5-25 19:30:00
---

## python运行错误
python报错`ModuleNotFoundError: No module named 'httplib'`
缺乏'httplib'`包
`pip install --upgrade pip`
响应
```
Could not find a version that satisfies the requirement httplib (from versions: )
No matching distribution found for httplib
```
更新pip
```
pip install --upgrade pip
```
查看pip版本
`pip -V`
```
pip 19.1.1 from /usr/local/lib/python2.7/site-packages/pip (python 2.7)
```

## 更新pip为pip3
```
curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
# 注意如果python有多个版本，用python3
python3 get-pip.py
pip -V
//pip 19.1.1 from /usr/local/lib/python3.7/site-packages/pip (python 3.7)
```

## 错误解决
仍然报错`ModuleNotFoundError: No module named 'httplib'`
查找到stack overflow文章中指出
`You are running Python 2 code on Python 3. In Python 3, the module has been renamed to http.client.`
这是python2的代码，不能用python3运行
`You could try to run the 2to3 tool on your code, and try to have it translated automatically. References to httplib will automatically be rewritten to use http.client instead.`

参考文章[https://www.cnblogs.com/ace722/p/9697331.html](https://www.cnblogs.com/ace722/p/9697331.html)

[pip官网](https://pip.pypa.io/en/latest/installing/#id7)
