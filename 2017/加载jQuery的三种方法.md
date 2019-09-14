---
title: 加载jQuery的三种方法
tags:
  - jquery
  - 加载
id: 158
categories:
  - share
date: 2014-08-30 17:30:52
---

--《深入PHP与jQuery开发》

加载脚本文件时将要总是最先加载jquery库

&nbsp;

1.加载本地JQuery
&lt;script type="text/javascript" src="js/jquery-1.4.1.min.js"&gt;&lt;/script&gt;

&nbsp;

2.加载存放在Google服务器上的jQuery
使用这个方法的好处是，如果用户在访问你的站点时，浏览器可能已经缓存了这个库文件
（用户访问其他站点的时候加载了这一文件），那么就能有效的提高你的站点的初次访问速度
&lt;script type="text/javascript"&gt;
scr="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"&gt;
&lt;/script&gt;

&nbsp;

3.使用Google AJAX Libraries API加载jQuery
也可以通过Google Codel提供的名为AJAX Libraries API的服务加载jQuery
&lt;script type="text/javascript"&gt;
src="http://www.google.com/jsapi"&gt;
&lt;/script&gt;
&lt;script type="text/javascript"&gt;
google.load("jquery", "1.4.2");
&lt;/script&gt;

&nbsp;

4.选择刚好在body的结束标记&lt;/body&gt;之前加载Javascript可以有效的防止脚本阻塞其他元素的加载，如图片的显示也能防止页面元素完全加载之前JavaScript代码就开始运行，避免产生页面错误

5.jQuery函数($)
jQuery函数时jQuery的核心，也可以用它的别名$()代替jQuery()
它的工作方式基本是：先创建一个jQuery对象实例，然后对传递给该实例的参数表达式求值，最后根据这个值做出相应的相应
特别提示：有些JavaSript库也使用$()函数，可能发生名字冲突.
jQuery提供了一个解决方案：用jQuery.noConflict()方法主动让出$()别名