---
title: Yii2安装搭建和将入口文件移到根目录
tags:
  - yii2
id: 788
categories:
  - php
date: 2015-09-13 12:08:58
---

[原文：Yii2安装搭建和将入口文件移到根目录](http://www.dodobook.net/php/2279 "Yii2安装搭建和将入口文件移到根目录")

用Composer下载Yii2速度太慢了，所以我还是喜欢下载打包好的框架文件。

在https://github.com/yiisoft/yii2/releases 下载最新的的Yii2，advanced是高级模板，basic是基础模板。他们的区别是高级模板里帮你分好了前后台模块，而基础模板里只有一个模块。

以高级模板为例：

下载并将里面的文件解压至`D:\wamp\www\yii2advanced` 里（注意：我是将压缩包里advanced文件夹里的文件解压在这里，而不是将advanced文件夹解压在此）。

然后双击init.bat进行初始化（如果你是使用基础模板则不用此步骤），输入数字0并回车选择Development模式，输入yes并回车确定。

最后导入示例数据（如果你不是要学习或体验Yii2而是要进行开发的话，这步就可以省略）。首先你需要创建一个数据库，我命名其为yii2advanced（注意：排序建议选择utf8_general_ci）。在`D:\wamp\www\yii2advanced\common\config`目录下，打开`main-local.php`文件，将里面的dbname修改成你的数据库名，username为你的数据库用户名，password为你的数据库密码。然后在yii2的根目录，也就是我的yii2advanced文件夹里，使用cmd命令进入该文件目录，输入yii migrate并回车，再输入yes确定，最后显示Migrated up successfully.说明导入数据成功。

这样Yii2的高级模板就安装完成，你可以从`http://localhost/yii2advanced/frontend/web/` 访问网站的前台：
[![QQ截图20150902104453](/images/2015/09/QQ截图20150902104453.png)](/images/2015/09/QQ截图20150902104453.png)

从http://localhost/yii2advanced/backend/web/ 访问网站的后台：

那么你可以从链接上发现，无论是前台还是后台，链接里都多了个/web/ ，我们希望访问的目录应该是这样：前台为http://localhost/yii2advanced，后台为http://localhost/yii2advanced/admin.php 。所以现在就来进行更改。

另外要明确的是，更改应该仅限于模块本身，而不应该去修改Yii2框架。

我们先来修改前台（这里为了避免bom头影响，建议在IDE里进行修改）：

打开D:\wamp\www\yii2advanced\frontend\web 文件夹，将里面的index.php文件复制（或剪切）至根目录D:\wamp\www\yii2advanced 。然后编辑里面的内容为：
```php
<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
   
require(__DIR__ . './vendor/autoload.php');
require(__DIR__ . './vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . './common/config/bootstrap.php');
require(__DIR__ . './frontend/config/bootstrap.php');
   
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . './common/config/main.php'),
    require(__DIR__ . './common/config/main-local.php'),
    require(__DIR__ . './frontend/config/main.php'),
    require(__DIR__ . './frontend/config/main-local.php')
);
   
$application = new yii\web\Application($config);
$application->run();
```
然后再修改在`D:\wamp\www\yii2advanced\frontend\config` 里的`main.php`文件，在component里加入
```php
'assetManager' => [
    'basePath' => '@webroot/frontend/web/assets',
    'baseUrl' => '@web/frontend/web/assets'
],
```
整个main.php应该为：
```php
<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
   
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'assetManager' => [
            'basePath' => '@webroot/frontend/web/assets',
            'baseUrl' => '@web/frontend/web/assets'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
```
最后修改`D:\wamp\www\yii2advanced\frontend\assets` 里的`AppAsset.php`文件，将里面的：

```php
public $css = [
    'css/site.css',
];
```
//修改为：
```php
public $css = [
    'frontend/web/css/site.css',
];
```
这样就可以用`http://localhost/yii2advanced` 直接访问首页了。

后台修改方法是将`D:\wamp\www\yii2advanced\backend\web` 里的index.php文件夹复制（剪切）至根目录并重命名为admin.php，其他修改地方与上述类似，只是将frontend改为backend即可。

原文地址:[dodobook:Yii2安装搭建和将入口文件移到根目录](http://www.dodobook.net/php/2279 "Yii2安装搭建和将入口文件移到根目录")