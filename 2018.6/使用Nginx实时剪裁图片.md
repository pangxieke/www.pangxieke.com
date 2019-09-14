---
title: 使用Nginx实时剪裁图片
id: user-nginx-image-filter-module-resize-image
category: linux
date: 2018.6.5 20:00:00
---

## 需求
我们经常需要处理图片，对图片进行压缩，剪切。
例如用户在个人中心上传图片，作为个人头像。此时头像一般为缩略图。

或者前段人员开发时，需要对同一个图片，在不同尺寸屏幕下，显示不同规格的图片。

## 实现方式
常用方式是通过后台程序实现，例如PHP。剪裁不同的图片，然后存储在磁盘中，可以多次调用。

其实我们可以通过Nginx，快速实现。通过Nginx中图片处理模块，轻量、快速实现，图片压缩、剪裁等功能。

http_image_filter_module是nginx提供的集成图片处理模块，支持nginx-0.7.54以后的版本。
在网站访问量不是很高磁盘有限不想生成多余的图片文件的前提下可，就可以用它实时缩放图片，旋转图片，验证图片有效性以及获取图片宽高以及图片类型信息。
缺点：由于是即时计算的结果，所以网站访问量大的话，不建议使用。

## 安装
默认HttpImageFilterModule模块是不会编译进nginx，需要在configure时候指定`--with-http_image_filter_module`

先查询Nginx是否安装此模块
```
nginx -V

#格式化之后的命令, 方便查看
 2>&1 nginx -V | tr ' '  '\n'|grep ssl
```

编译安装Nginx
```
./configure arguments: --prefix=/usr/local/nginx --with-http_image_filter_module
```

如果未安装回报`“/configure: error: the HTTP image filter module requires the GD library.”`错误
解决：HttpImageFilterModule模块需要依赖gd-devel的支持
```
yum install gd-devel
```
或者
```
apt-get install libgd2-xpm libgd2-xpm-dev
```

## Nginx配置
```
server_name image.test.com;

location ~* (.*)_(\d+)x(\d+)\.(jpg|png|gif)$ {
		set $s $1;
		set $w $2;
		set $h $3;
		image_filter resize $w $h;
		#image_filter crop $w $h;
		image_filter_buffer 10M;
		rewrite ^(.*)\.(jpg|png|gif)$ $s.$2 break;
}
```

		
## 测试
先测试能否正常访问
`http://image.test.com/test/2.jpg`

![](/images/2018/06/2.jpg)

再测试图片剪切
`http://image.test.com/test/2_200x200.jpg`

![](/images/2018/06/3.jpg)
当然，也可以图片旋转等功能，只需要修改相应配置

## 参数配置
http_image_filter_module支持5种指令：

image_filter：
测试图片文件合法性（image_filter test）；
3个角度旋转图片（image_filter rotate 90 | 180 | 270）；
以json格式输出图片宽度、高度、类型（image_filter size）；
最小边缩小图片保持图片完整性（resize width height）；
以及最大边缩放图片后截取多余的部分（image_filter crop [width] [height]）；

image_filter_jpeg_quality：
设置jpeg图片的压缩质量比例（官方最高建议设置到95，但平时75就可以了）；

image_filter_buffer：
限制图片最大读取大小，默认为1M；

image_filter_transparency：
用来禁用gif和palette-based的png图片的透明度，以此来提高图片质量。

image_filter_sharpen：
这个指令在nginx-1.1.8和1.0.11版本后增加的，目前还不知道是干啥用。