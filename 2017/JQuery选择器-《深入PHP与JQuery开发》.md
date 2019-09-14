---
title: JQuery选择器-《深入PHP与JQuery开发》
tags:
  - jquery
id: 184
categories:
  - share
date: 2014-09-01 23:51:42
---

一、基本选择器
基本选择器让开发中能够使用标签类型，类名，Id，或者他们的任意组合选择元素
1.标签类型选择元素
$("p");
2.class选择元素
$(".foo");
3.ID选择元素
$("#bar");
4.联合选择器
$("p.foo");
5.组合选择器
$(p.foo,#bar");只要元素匹配组合选择器中任意一个选择器，都会被选中

二、层次选择器
有时候仅使用元素标签、class或ID选择元素不能满足需求，很多场合需要访问一个元素内部的元素
一个元素的下一个元素，或者一个元素后的元素
1.后台元素
使用祖先元素后加空格再加后台元素的格式
$("body span"); 会找到body标签内的所有span,也包括&lt;p&gt;标签包裹的span
2.子元素 &gt;
子元素选择器是后代选择器的特殊形式，它值匹配直接子元素(最近一层的子元素),使用&gt;匹配
$("body&gt;span");
3.下一个兄弟元素 +
$(".foo+p");
4.兄弟元素 ~
返回同一个元素包裹的同一级的全部元素。类似于下一个元素，只是他返回起始元素之后的全部兄弟元素
$(".foo~p");

三、基本过滤器
1.选择第一个或者最后一个元素:first :last
$("p:last"); //Object[p#bar]
2.选择不匹配某个选择器的元素 not()
$("p:not(.foo)"); //Object[p, p, p#bar]
3.选择索引为奇数或偶数的元素even :odd
$("p:odd"); //Object[p.foo, p#bar]
4.选择特定索引的元素 eq()
$("p:eq(3)"); //Object[p#bar]

四、内容过滤器
有的能匹配包含特定文本的元素，有的负责匹配包含特定元素的元素
1.匹配包含特定文本的元素 contains() 注：区分大小写
$("p:contains(Another)");
2.匹配包含特定元素的元素:has
$("p:has(span)"); // Object[p, p#bar]
3.选择空元素:empty
找出不包含任何文本也不包含任何其它元素的空元素
$(":empty"); //Object[script ./jquery-...1.min.js]
4.选择父元素 :parent
只匹配那些拥有子元素的元素，不管他包含的是其它元素，还是文本内容
$("p:parent"); //Object[p, p.foo, p, p#bar]

五、可见性过滤器
1.可见:hidden
$("p:hidden"); //Object[]
2.隐藏:visible
$("p:visible"); //Object[p, p.foo, p, p#bar]

六、属性过滤器
利用元素的属性来选择元素也是一种非常重要的方法。
属性是位于标签内部用于对标签做进一步描述的东西(class, href)
1.根据属性及属性的值选择元素 []
$("[class=foo]"); //Object[p.foo, span.foo]
2.选择没有某个属性或者属性值不匹配的元素
$("p[class!=foo]"); //Object[p, p, p#bar]

七、子元素过滤器
子元素过滤器是:even, :odd, :eq()的一种代用品，区别在于这一套选择器起始索引为1而不是0
1.匹配奇数索引值/偶数索引值/特定索引值得元素
:nth-child(),4个参数even, odd, index, equation
这个过滤器的索引从1而不是0开始
$("p:nth-child(odd)");
2.选择第一个或最后一个子元素
:first-child, :last-child 和:first 和:last及其相似，
但区别在于返回的元素集合可能含有不止一个匹配元素
$("p span:last"); //Object[span.foo]
如果想匹配每个段落的最后一个span子元素，就应该用last-child,它会在每个段落，而不是整个DOM匹配
$("p span:last-child"); //Object[span, span.foo]

八、表单过滤器
1.按表单元素类型匹配
:button, :checkbox, :file, :image, :input, :password, :radio, :submit, :text
要选择全部单选按钮(&lt;input type="radio"&gt;)
$("input:radio");
2.匹配可用或禁用的表单元素
:enabled, :disabled
$(":disabled");
3.匹配选中或者未选中的表单元素
单选框和复选框有一个checked状态，而下拉列表(&lt;input type="select"&gt;)有一个selected状态
:checked, :selected
$(":checked"); //匹配选中的单选按钮