---
title: php生成手机验证码
id: 858
categories:
  - php
date: 2015-11-02 20:27:32
tags: 短信
---

php生成手机验证码，可以指定长度
```

 function generate_code($length = 6) {
    $min = pow(10 , ($length - 1));
    $max = pow(10, $length) - 1;
    return rand($min, $max);
 }

```