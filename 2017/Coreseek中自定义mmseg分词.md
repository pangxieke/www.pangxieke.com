---
title: Coreseek中自定义mmseg分词
tags:
  - coreseek
  - mmseg
id: 673
categories:
  - linux
date: 2015-04-17 17:27:11
---

因为项目需要使用中文搜索，觉得使用coreseek实现，其中使用mmseg 分词库
但是mmseg原词库不适合电商项目，所以决定自定义分词库。

1.下载搜狗词库，例如有淘宝专用词库 http://pinyin.sogou.com/dict/detail/index/22416，得到一个.scel文件

2.将下载的文件是scel文件，转换为txt文件。这里使用“深蓝词库转换”软件，下载地址[http://down1.downxia.com/down/slckzh.rar?vspublic=1403b1e16f8f47d465dae7516ce3c1f9.exe](http://down1.downxia.com/down/slckzh.rar?vspublic=1403b1e16f8f47d465dae7516ce3c1f9.exe)
使用此软件，获取txt文件

3.将txt文件，转换为mmseg对应格式的txt文件。
在转换，可以查看mmseg 文件原本的`unigram.txt`文件

```php
head -10 /usr/local/mmseg3/etc/unigram.txt
```

查看mmseg原本的词库文件，我们可以看到格式如下，我们就是需要把新词库文件转化成这种格式

```php
爱宝疗  1
x:1
爱宝氏鱼肝油    1
x:1
艾贝    1
x:1
```

转换过程，其实也就是 文字后面加 制表符 + 1 换行 + x:1 + 换行“\t1\r\nx:1\r\n”。
有些编辑器就可以在实现匹配转换的功能。
本人使用php脚本来实现:原文件命名为1.txt，产生的新文件为words_new.txt。

```php
<?php
/**
 * 需要原文件命名为1.txt，多个文件命名为n.txt,同时修改$n，文件放在当前文件夹data下
 * 产生的新文件为words_new.txt
 */
//可能超时
set_time_limit(0);
 
$n = 28;    //多个文件连续命名
$arrNew = array();
for($i=1; $i<=$n; $i++){
    $filename = './data/' . $i . ".txt";
    $handle = fopen ($filename, "r");
    $content = fread ($handle, filesize ($filename));
 
    fclose ($handle);
 
    $arr1 = array();
    $content=trim($content);
    $arr1 = explode( "\r\n" ,$content );
     
    $arr1=array_flip(array_flip($arr1));
 
    foreach($arr1 as $key=>$value){
        //$value=dealchinese($value);
        if(!empty($value)){
            $arrNew[] = trim($value);
        }
    }
}
 
$res = array_unique($arrNew);
echo 'sum:' . count($res);
 
$words='';
foreach($res as $k=>$word){
    $words.=$word."\t1\r\nx:1\r\n";
}
 
file_put_contents('words_new.txt',$words,FILE_APPEND);
 
function dealChinese($str,$join=''){
    preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches); //将中文字符全部匹配出来
    $str = join($join, $matches[0]); //从匹配结果中重新组合
    return $str;
}
```

4.替换`uni.lib`文件
首先，使用 `/usr/local/mmseg3/bin/mmseg -u words_new.txt` 产生一个`words_new.txt.uni` 文件
然后将`words_new.txt.uni` 重命名为`uni.lib`

```php
/usr/local/mmseg3/bin/mmseg -u words_new.txt
rm uni.lib
mv words_new.txt.uni uni.lib
```

5.测试

```php
echo "做衣服" &gt; whatever.txt  #新建一个文件
/usr/local/mmseg3/bin/mmseg -d /usr/local/mmseg3/etc/ whatever.txt #测试新文件的分词
 
#得到下面的结果
做衣服/x
 
Word Splite took: 28 ms.
 
#如果没有新词典，可能得到的是这样的结果
 
做/x 衣服/x
 
Word Splite took: 0 ms.
```