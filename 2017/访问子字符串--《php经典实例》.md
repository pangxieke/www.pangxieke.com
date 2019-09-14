---
title: 访问子字符串--《php经典实例》
tags:
  - strpos
  - 子字符串
id: 165
categories:
  - php
date: 2014-09-01 10:07:39
---

<div style="color: #000000; font-family: 微软雅黑; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: -webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
<div>访问子字符串</div>
<div>--《php经典实例》1.1访问子字符串</div>
<div></div>
<div>你想知道一个字符串中是否包含了一个特殊的子字符串</div>
<div>例如找出包含@的电子邮件地址</div>
<div></div>
<div>使用strpos()</div>
<div></div>
<div>

```php

if (strpos($_POST['email'], '@') === false) {
    print 'There was no @ in the e-mail address!';
}

```

</div>
<div>注意：</div>
<div>由strpos()返回的值，是在这个字符串中找到的子字符串的起始位置</div>
<div>如果在这个字符串中没有找到对应的子字符串，strpos()返回false</div>
<div>如果子字符串位于这个字符串的开始处，strpos()f返回0，因为位置0表示这个字符串的开始</div>
<div>为了区分返回的0和false，必须使用全等操作费===,或者不全等操作符!==</div>
<div></div>
</div>
&nbsp;