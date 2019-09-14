---
title: Ajax缓存解决办法
id: 307
categories:
  - share
date: 2014-09-24 21:48:47
tags:
---

Ajax缓存解决办法

转载一篇文章,在做聊天室的过程中困惑我很久的一个问题.呵呵,太感谢作者了.原文如下:

项目有时要用一些Ajax的效果，因为比较简单，也就没有去用什么Ajax.net之类的东西，手写代码也就实现了。、

第二天，有人向我报告错误；说是只有第一次读取的值正常，后面的值都不正常；我调试了一下 ，确实有这样的问题，查出是因为AJAX缓存的问题：解决办法有如下几种:

1、在服务端加 header("Cache-Control: no-cache, must-revalidate");(如php中)

2、在ajax发送请求前加上 anyAjaxObj.setRequestHeader("If-Modified-Since","0");

3、在ajax发送请求前加上 anyAjaxObj.setRequestHeader("Cache-Control","no-cache");

4、在 Ajax 的 URL 参数后加上 "?fresh=" + Math.random(); //当然这里参数 fresh 可以任意取了

5、第五种方法和第四种类似，在 URL 参数后加上 "?timestamp=" + new Date().getTime();

6、用POST替代GET：不推荐

1、加个随机数
      xmlHttp.open("GET", "ajax.asp?now=" + new Date().getTime(), true);

2、在要异步获取的asp页面中写一段禁止缓存的代码：
      Response.Buffer =True
      Response.ExpiresAbsolute =Now() - 1
      Response.Expires=0
      Response.CacheControl="no-cache"

3、在ajax发送请求前加上xmlHTTP.setRequestHeader("If-Modified-Since","0");可以禁止缓存
      xmlHTTP.open("get", URL, true); 
      xmlHTTP.onreadystatechange = callHTML; 
      xmlHTTP.setRequestHeader("If-Modified-Since","0"); 
      xmlHTTP.send();

另一个作者写到:

AJAX的缓存是由浏览器维持的，对于发向服务器的某个url，ajax仅在第一次请求时与服务器交互信息，之后的请求中，ajax不再向服务器提交请求，而是直接从缓存中提取数据。

有些情况下，我们需要每一次都从服务器得到更新后数据。思路是让每次请求的url都不同，而又不影响正常应用：在url之后加入随机内容。
e.g.
url=url+"&"+Math.random();

Key points:
1.每次请求的url都不一样（ajax的缓存便不起作用）
2.不影响正常应用（最基本的）

----------------
方法二：（未经证实）
在JSP中禁止缓存
response.addHeader("Cache-Control", "no-cache");
response.addHeader("Expires", "Thu, 01 Jan 1970 00:00:01 GMT"); 

HTTP:
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT">
<META HTTP-EQUIV="expires" CONTENT="0">

另一个作者写到:

我们都知道，ajax能 提高页面载入的速度的主要原因是通过ajax减少了重复数据的载入，真正做到按需获取，既然如此，我们在写ajax程序的时候不妨送佛送到西，在客户端再 做一次缓存,进一步提高数据载入速度。那就是在载入数据的同时将数据缓存在浏览器内存中，一旦数据被载入，只要页面未刷新，该数据就永远的缓存在内存中， 当用户再次查看该数据时，则不需要从服务器上去获取数据，极大的降低了服务器的负载和提高了用户的体验。