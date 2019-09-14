---
title: wordpress迁移hexo，批量修改md文件名
categories: share
id: 1331
date: 2017.9.12 
---

### 一、hexo与wordpress对比
最近研究hexo，觉得静态博客加载速度快。
以前都是用wordpress写博客，需要上传图片，修改标题等，写完博客后，上传及调整样式，至少需要十分钟。
而hexo支持Markdown语法，写博客十分方便。因此考虑将wordpress博客迁移到hexo。

### 二、迁移前准备
#### 2.1需保证URL不变
但wordpress博客已经上线两年了，虽然流量不多，但是为了保证百度收录不会受影响，迁移时文章URL不能变动。
以前文章导航使用`category/id.html`形式，例如`www.pangxieke/share/1330.html`这种样式。

#### 2.2图片同步
wordpress使用自有服务器，图片存储在`themes/uploads/`下，需要将图片也完美迁移过去，不然需要修改所有文章的图片链接，那也是十分繁琐的

### 三、wordpress文章导出
#### 3.1 导出文章为xml
登录 Wordpress 后台，在“工具”-“导出”中导出所有记录

![](/images/2017/09/wordpress_out.png)
得到`wordpress.2017-09-08.xml`

#### 3.2 替换xml中图片路径
将图片路径如`www.pangxieke.com/themes/uploads/2017/09/avator.png` 替换为`/images/2017/09/avator.png`, 注意images前面为`/` ,这里我遇到一个坑。
开始时，我使用相对路径`./images/2017/09/avator.png`， 文章有分类，如分类为`share`,此时生成的静态文件img src 为 `./share/images/2017/09/avator.png`, 但我希望图片根目录为`images`,所以的图片在此下，而且不要受分类影响。

后来使用绝对路径才解决，即 **/images**  而不是 ~~./images~~

### 四、导入hexo

#### 4.1 hexo migrate 插件

首先安装 hexo-migrator-wordpress 插件

在hexo项目根目录下执行命令
`npm install hexo-migrator-wordpress --save`

导入文章
`hexo migrate wordpress <source>`
例如
`hexo migrate wordpress wordpress.2017.9.11.xml`

此时在 `_posts` 目录下有所有的文章

#### 4.2 批量修改md文件名
导入产生的文件名，可能是hash码产生的，我不能通过标题看出是那篇文章，不方便修改。

![](/images/2017/09/md_title.png)

所有使用php写了一个程序，读取文章的title，然后修改了所有文章名
 ```php
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
```

得到效果

![](/images/2017/09/md_new.png)

