---
title: php导入execl时间转换及calendar扩展安装
id: 1262
categories:
  - linux
date: 2017-05-02 19:12:17
tags:
---

[![20170502110418](/images/2017/05/20170502110418.jpg)](/images/2017/05/20170502110418.jpg)

phpexecl导入execl时，需要导入一列时间栏，其显示时间为2017/1/3，导入时数字为42738
此时我想把时间转换成时间戳格式

## Execl时间转换

```php
#使用phpexecl
function excelTime($days){
    return gmdate("Y-m-d H:i:s", \PHPExcel_Shared_Date::ExcelToPHP($days));
}
```
或者
```php
private function excelTime($date, $time = false) {
    if (function_exists('GregorianToJD')) {
        if (is_numeric($date)) {
            $jd = GregorianToJD(1, 1, 1970);
            $gregorian = JDToGregorian($jd + intval($date) - 25569);
            $date = explode('/', $gregorian);
            $date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) 
            . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) 
            . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT)
            . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    } else {
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $date = $date > 25568 ? $date + 1 : 25569; 
        $ofs = (70 * 365 + 17 + 2) * 86400;
        $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
    }
    return $date;
}
```

## GregorianToJD函数

gregoriantojd() 函数把格利高里历法的日期转换为儒略日计数
```php
int gregoriantojd ( int $month , int $day , int $year )
```
注释：尽管该函数可处理 4714 B.C. 之前的日期，您还是要注意格利高里历法在 1582 年才建立，一些国家甚至更晚才接受它（大不列颠在 1752 年，苏联在 1918 年，希腊在 1923 年）。大部分欧洲国家使用罗马儒略历法（公历）先于格利高里历法。

## calendar扩展

GregorianToJD函数不存在
<small>
Fatal error: Call to undefined function gregoriantojd() 
</small>
需要安装calendar扩展模块

```php
cd lnmp1.3/src/php-7.0.7/ext/calendar/
make
make test
sudo make install

ls /usr/local/php/lib/php/extensions/no-debug-non-zts-20151012/
#可以看到calendar.so文件
vi /usr/local/php/etc/php.ini

//加入calendar扩展
extension=calendar.so

//重启php服务
//phpinfo()查看
```
[![calendar](/images/2017/05/calendar.png)](/images/2017/05/calendar.png)