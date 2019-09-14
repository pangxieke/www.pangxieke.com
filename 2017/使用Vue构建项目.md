---
title: 使用Vue构建项目
id: 1323
categories:
  - share
date: 2017-09-06 19:34:10
tags:
---

[![](/images/2017/09/vue-info.png)](/images/2017/09/vue-info.png)

##  一、简介

Vue 是一个前端框架，特点是方便数据绑定和组件化

官网 https://cn.vuejs.org/

###  数据绑定

比如你改变一个输入框 Input 标签的值，会自动同步更新到页面上其他绑定该输入框的组件的值

[![](/images/2017/09/vue_bind.png)](/images/2017/09/vue_bind.png)

###  组件化

页面上小到一个按钮都可以是一个单独的文件.vue，这些小组件直接可以像乐高积木一样通过互相引用而组装起来

[![](/images/2017/09/vue_zhujian.jpg)](/images/2017/09/vue_zhujian.jpg)

##  二、简单Demo

```php
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Vue 测试实例-螃蟹壳</title>
	<script src="https://cdn.bootcss.com/vue/2.4.2/vue.min.js"></script>
</head>
<body>
	<div id="app">
		<p>{{ message }}</p>
	</div>

	<script>
		new Vue({
		  el: '#app',
		  data: {
		    message: 'Hello pangxieke!'
		  }
		})
	</script>
</body>
</html>
```

##  三、vue构建项目

###  安装Node.js 和npm

安装后测试
```php
node -v
npm -v
```

###  安装脚手架

```php
 npm install ---global vue-cli
 vue init webpack my-project

cd my-project/
npm install
npm run dev
```

### 测试

访问http://localhost:8080/#/
出现如下页面，表示项目创建成功
[![](/images/2017/09/vue.png)](/images/2017/09/vue.png)