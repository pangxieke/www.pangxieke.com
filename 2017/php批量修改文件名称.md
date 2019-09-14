---
title: php批量修改文件名称
id: 897
categories:
  - php
date: 2016-01-26 15:25:32
tags:
---

程序功能：利用PHP目录和文件函数遍历用户给出目录的所有的文件和文件夹，修改文件名称；
```php
function fRename($dirname){
    if(!is_dir($dirname)){
        echo "{$dirname}不是一个有效的目录！";
        exit();
    }
    $handle = opendir($dirname);
    while(($fn = readdir($handle))!==false){
        if($fn!='.'&&$fn!='..'){
            $curDir = $dirname.'/'.$fn;
            if(is_dir($curDir)){
                fRename($curDir);
            }else{
                $path = pathinfo($curDir);
                $newname = $path['dirname'].'/'.rand(0,100).'.'.$path['extension'];
                rename($curDir,$newname);    
                echo $curDir.'---'.$newname."<br>";    
            }
        }
    }
}
```