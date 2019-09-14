---
title: hexo增加百度联盟广告
categories: share
id: 1332
date: 2017.9.12
description: hexo配置百度联盟广告，使用的`next`主题，找了很久也没有发现如何设置，只能自己配置百度联盟广告
tags: hexo
---

博客使用的是hexo搭建，使用的`next`主题，但是配置文件中，没有百度联盟广告的配置项，只能自己搭建了

### 一、获取广告JS代码
我使用的是百度广告，登录[百度联盟](http://union.baidu.com)，代码位管理，创建代码位，得到JS代码，如下：

```
<script type="text/javascript">
    /*580*90 创建于 2017/9/12*/
    var cpro_id = "u3092140";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
```

### 二、hexo添加百度广告
#### 新建baidu_union.swig文件
在路径`\themes\next\layout\_macro`中添加`baidu_union.swig`文件，其内容为：
```
{% if theme.baidu_union.enabled %}
<script type="text/javascript">
    /*580*90 创建于 2017/9/12*/
    var cpro_id = "u3092140";
</script>
<script type="text/javascript" src="http://cpro.baidustatic.com/cpro/ui/c.js"></script>
{% endif %}
```
#### 修改 post.swig 文件
在`\themes\next\layout\_macro\post.swig`中，`post-body`之后，`post-footer`之前添加如下代码：
```
<div>
  {% if not is_index %}
	{% include 'baidu_union.swig' %}
  {% endif %}
</div>
```

#### 主题配置文件增加控制字段
在主题配置文件 `_config.yml`中添加以下字段开启此功能：
```
baidu_union: 
     enabled: true
```
完成以上设置之后，在每篇文章之后都会百度联盟广告。

#### 效果
配置完成后，发布，能够看到如下对应效果
