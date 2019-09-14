---
title: JS事件首页有效次页无效
id: 986
categories:
  - linux
date: 2016-04-19 21:52:22
tags: javascript
---

今天使用JS写一个click事件，发现首页有效次页无效。页面使用Jquery的datatable的AJAX分页。

对表格中的一行数据点击修改时，弹出修改框。使用class绑定。

```php
$('.editbtn').on('click',function(){
    alert("hello");
});
```

表格列表第一页能够正常点击，但是发现在ajax翻页到第二页时，点击无效。思考好久都没有解决。

最终同事帮忙解决。解决方式如下

```php
//tbl_content为table上的ID
$('#tbl_content').on('click',function(e){
    var e = e || event;
    if( $(e.target).hasClass('editbtn') ){
        alert("hello");
    }
});
```

此时促使想根据详细的了解js的事件。查询到了下面的资料

## 1、冒泡型事件：

事件按照从最特定的事件目标到最不特定的事件目标(document对象)的顺序触发。
`IE 5.5: div -> body -> document`

`IE 6.0: div -> body -> html -> document`

`Mozilla 1.0: div -> body -> html -> document -> window`

## 2、捕获型事件(event capturing)：

事件从最不精确的对象(document 对象)开始触发，然后到最精确(也可以在窗口级别捕获事件，不过必须由开发人员特别指定)。

## 3、DOM事件流：

同时支持两种事件模型：捕获型事件和冒泡型事件，但是，捕获型事件先发生。两种事件流会触及DOM中的所有对象，从document对象开始，也在document对象结束。
DOM事件模型最独特的性质是，文本节点也触发事件(在IE中不会)。

[![事件冒泡](/images/2016/04/事件冒泡.jpg)](/images/2016/04/事件冒泡.jpg)

### 阻止冒泡方法

```php
<!DOCTYPE html>
<meta charset="utf-8">
<head>
    <title>test</title>
 
    <script src="http://code.jquery.com/jquery-latest.js"></script>
 
    <script type="text/javascript">
 
        $(function(){
 
            $('#clickMe').click(function(){
 
                alert('hello');
                //方法1
                return false;
            });
            $('body').click(function(){
                alert('baby');
            });
 
        });
 
    </script>
</head>
<body>
    <div style="width:100px;height:100px;background-color:orange;">
        <button type="button" id="button2">click me</button>
        <button id="clickMe">click</button>
    </div>
</body>
</html>
```

事件冒泡现象：点击 `“id=clickMe” `的button,会先后出现“hello” 和 “baby” 两个弹出框。

分析：当点击 `“id=clickMe”` 的button时，触发了绑定在button 和 button 父元素及body的点击事件，所以先后弹出两个框，出现所谓的冒泡现象。

事件捕获现象：点击没有绑定点击事件的div和 `“id=button2”`的button, 都会弹出 “baby” 的对话框。 在实际的项目中，我们要阻止事件冒泡和事件捕获现象。

### 阻止事件冒泡方法：

#### 法1：当前点击事件中return false;

```php
$('#clickMe').click(function(){
    alert('hello');
    return false;
 
});
```

#### 法2：

```php
$(function(){
    $('#clickMe').click(function(event){
        alert('hello');
        var e = window.event || event;
        if ( e.stopPropagation ){ //如果提供了事件对象，则这是一个非IE浏览器
            e.stopPropagation();
        }else{
            //兼容IE的方式来取消事件冒泡
            window.event.cancelBubble = true;
        }
    });
});
```