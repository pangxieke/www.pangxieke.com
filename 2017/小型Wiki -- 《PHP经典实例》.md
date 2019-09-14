---
title: 小型Wiki -- 《PHP经典实例》
tags:
  - wiki
id: 198
categories:
  - php
date: 2014-09-08 00:19:12
---

```php
<?php
/**
*   网站的Wiki系统(用户可以编辑其中每一个页面的网站)
*   这个小型Wiki熊需要一个扩展库php Markdown的支持，
*   以便处理方便、简介的Markdow语法和HTML直接的转换
*   载PHP Markdown扩展库
*   在 http://www.michelf.com/projects/php-markdown
* <a href="http://www.michelf.com/projects/php-markdown" title="PHP Markdown扩展库">PHP Markdown扩展库</a>
*/
 
//引入markdown扩展库
require_once './Markdown1.0.2/markdown.php';
 
//保存wiki页面的文件夹，确保web服务器可以写入
define('PAGEDIR', dirname(__FILE__) . '/pages');
 
//取得页面名称，或者使用默认页面
$page = isset($_GET['page']) ? $_GET['page'] : 'Home';
 
//决定做什么：显示一个编辑表单、保存编辑表单、或者显示一个页面
//显示请求编辑的表单
if(isset($_GET['edit'])){
pageHeader($page);
edit($page);
pageFooter($page,false);
}else if(isset($_POST['edit'])){    //保存编辑表单的内容
file_put_contents(pageToFile($_POST['page']), $_POST['contents']);
 
//重定向到编辑过页面的常规视图
header('Location:http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']
. '?page=' . urlencode($_POST['page']));
 
exit;
}else{      //显示一个页面
pageHeader($page);
 
//如果页面存在，显示该页面，并在页脚中显示一个 Edit的链接
if( is_readable(pageToFile($page)) ){
//从保存的文件中取得页面的内容
$text = file_get_contents(pageToFile($page));
 
//转换Markdown语法 （使用markdown.php中Markdown()函数）
$text = MarkDown($text);
 
//生成指向其他wiki页面的空连接
$text = wikiLinks($text);
 
//显示页面
echo $text;
 
//显示页脚
pageFooter($page, true);
}else{
//如果页面不存在，显示一个编辑表单，以及不带 Edit链接的页脚
edit($page, true);
pageFooter($page, false);
 
}
 
}
 
//页面的页眉
function pageHeader($page){
?>
<html>
<head>
<title>Wiki:<?php echo htmlentities($page) ?></title>
</head>
<body>
<h1><?php echo htmlentities($page) ?></h1>
<hr>
<?php
}
 
//页面的页脚，包含 last modified 时间戳，
//一个可选的 Edit链接和一个返回Wiki首页的链接
function pageFooter($page, $displayEditLink){
$timestamp = @filemtime(pageToFile($page));
 
if($timestamp){
$lastModified = strftime('%c', $timestamp);
}else{
$lastModifed = 'Never';
}
 
if($displayEditLink){
$editLink = ' - <a href=&quot;?page=' . urlencode($page)
. '&edit=true&quot;>Edit</a>';
}else{
$editLink = '';
}
?>
<hr/>
<em>Last Modified:<?php echo $lastModified; ?> </em>
<?php echo $editLink; ?>
- <a href=&quot;<?php echo $_SERVER['SCRIPT_NAME']; ?>&quot;>Home</a>
</body>
</html>
<?php
}
 
//显示一个编辑表单，如果页面存在，在表单中包含其内容
function edit($page, $isNew = false){
if ($isNew) {
$contents = '';
?>
<p>
<b>
This page doesn't exit yet.
</b>
To create it, enter its contents below and click the
<b>
Save
</b>
button.
</p>
<?php
} else {
$contents = file_get_contents(pageToFile($page));
}
?>
<form method=&quot;post&quot; action=&quot;<?php echo htmlentities($_SERVER['SCRIPT_NAME']);?>&quot;>
<input type=&quot;hidden&quot; name=&quot;edit&quot; value=&quot;true&quot; />
<input type=&quot;hidden&quot; name=&quot;page&quot; value=&quot;<?php echo htmlentities($page); ?>&quot; />
<textarea name=&quot;contents&quot; rows=&quot;20&quot; cols=&quot;60&quot;>
<?php echo htmlentities($contents);?>
</textarea>
 
<br />
<input type=&quot;submit&quot; value=&quot;Save&quot; />
</form>
<?php
}
 
// 将提交的页眉转换为一个文件名，使用md5()函数避免$page中的淘气字符可能导致的安全问题
function pageTofile($page){
return PAGEDIR . '/' . md5($page);
}
 
//把页面中诸如 [something] 之类的文本转化成 Wikeyem页面中&quot;something&quot;这样的HTML链接
function wikiLinks($page){
if ( @preg_match_all('/\[([^\]]+?]/', $page, $matches, PREG_SET_ORDER) ){
foreach($matches as $match){
$page = str_replace(
$match[0],
'<a href=&quot;' . $_SERVER['SCRIPT_NAME']
. '?page' . urlencode($match[1]) . '&quot;>'
. htmlentities($match[1]) . '</a>',
$page
);
}
}
 
return $page;
}

```
[PHP Markdown扩展库](http://www.michelf.com/projects/php-markdown "PHP Markdown扩展库")
[file]https://littoral.michelf.ca/code/php-markdown/php-markdown-1.0.2.zip[/file]