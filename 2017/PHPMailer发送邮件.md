---
title: PHPMailer发送邮件
tags:
  - 邮件，PHPMailer
id: 315
categories:
  - php
date: 2014-09-26 21:28:24
---

1.首先是下载PHPMailer

http://code.google.com/a/apache-extras.org/p/phpmailer/

2.解压

从中取出class.phpmailer.php 和 class.smtp.php 放到你的项目的文件夹，因为我们等下会引用到它们.

3.创建发送邮件的函数,其中你需要配置smtp服务器

```php
function postmail($to,$subject = '',$body = ''){
    //Author:Jiucool WebSite: http://www.jiucool.com
    //$to 表示收件人地址 $subject 表示邮件标题 $body表示邮件正文
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set('Asia/Shanghai');//设定时区东八区
    require_once('class.phpmailer.php');
    include('class.smtp.php');
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $body            = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="GBK";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "ssl";                 // 安全协议，可以注释掉
    $mail->Host       = 'stmp.163.com';      // SMTP 服务器
    $mail->Port       = 25;                   // SMTP服务器的端口号
    $mail->Username   = 'wangliang_198x';  // SMTP服务器用户名，PS：我乱打的
    $mail->Password   = 'password';            // SMTP服务器密码
    $mail->SetFrom('xxx@xxx.xxx', 'who');
    $mail->AddReplyTo('xxx@xxx.xxx','who');
    $mail->Subject    = $subject;
    $mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, '');
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    if(!$mail->Send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
//        echo "Message sent!恭喜，邮件发送成功！";
    }
}
``` 

4. 使用函数
```php
postmail('wangliang_198x@163.com','My subject','哗啦啦');

```