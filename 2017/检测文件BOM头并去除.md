---
title: 检测文件BOM头并去除
tags:
  - bom
id: 768
categories:
  - php
date: 2015-08-18 22:49:33
---

大家在使用notePadd++ 或者 EditPlus 打开文件时，经常因为编码问题，导致乱码，尤其是UTF-8的BOM头，下面就提供一个方法检测并去除BOM头。

```php
/** 
* 本函数用于检测文件是否含有BOM头 
*  
* @param string $filename  要检测的文件名称 
* @return boolean 
*/  
function checkBOM($filename){ 
   header('content-type:text/html;charset=utf-8');
   if(!file_exists($filename)) exit('请输入正确的文件路径名称!');  
   $content = '';  
   $charset = array();  
   $content = @file_get_contents($filename);  
   $charset[1] = substr($content, 0, 1);  
   $charset[2] = substr($content, 1, 1);  
   $charset[3] = substr($content, 2, 1);  
 
   //判断是否含有BOM头  
 
   if(ord($charset[1]) == 239 && ord($charset[2])==187 && ord($charset[3])==191){  
       $content = substr($content,3);  
       @file_put_contents($filename, $content); 
       return true;
   } 
   return false; 
}
```