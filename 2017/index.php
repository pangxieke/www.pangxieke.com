<?php
header("content-type:text/html; charset=utf-8");

$dir = "./";

$dh = opendir($dir);
$i = 0;
while ($file = readdir($dh)){
    if( $file =="." || $file ==".."){
        continue;
    }
    $arr = file($file);
    $title = $arr[1];
    $title = trim(str_replace('title:', '', $title));
    $new =  $title . '.md';
    $new = iconv("utf-8", "gb2312", $new);

    rename($file, $new);
    $i ++;
}
echo $i;

