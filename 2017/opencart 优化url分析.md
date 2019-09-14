---
title: opencart 优化url分析
tags:
  - opencart
id: 1007
categories:
  - php
date: 2016-05-19 18:27:11
---

[![](/images/2016/05/opencart.png)](/images/2016/05/opencart.png)
接触到opencart，有对url进行优化，将真实的多层文件夹系统优化为
例如`www.pangxieke.com/product/category` 优化为`www.pangxieke.com/category`

## 实现原理

利用数据库存储seo_url和真实系统url。系统接收到seo_url后，转换为系统真实url

## 1、apache入口htaccess文件

```php
RewriteRule ^([^?]*) index.php?_route_=$1 [L,QSA]
所有的请求都转换为_route_
```

## 2、index.php文件加载seo_url

```php
$controller->addPreAction(new Action('common/seo_url'));
```

## 3、common/seo_url文件

分析_route_参数，查询数据库，将url转换为系统真实对应的url
例如 将category转换为product/category
代码为

```php
<?php
class ControllerCommonSeoUrl extends Controller {
public function index() {
 
    // Decode URL
    if (isset($this->request->get['_route_'])) {
        $parts = explode('/', $this->request->get['_route_']);
        // remove any empty arrays from trailing
        if (utf8_strlen(end($parts)) == 0) {
            array_pop($parts);
        }
         
        //查下数据库
        foreach ($parts as $part) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '"
                               . $this->db->escape($part) . "'");
 
            if ($query->num_rows) {
                if($query->row['status'] == 1){
                    $this->request->get['route'] = $query->row['query']; 
                    break;
                }
            } else {
                $this->request->get['route'] = 'error/not_found';
                break;
            }
        }
 
        if (isset($this->request->get['route'])) {
            return new Action($this->request->get['route']);
        }
    }
}
 
}

```

## 4、对应数据库

```php
CREATE TABLE `url_alias` (
    `url_alias_id` INT(11) NOT NULL AUTO_INCREMENT,
    `query` VARCHAR(255) NOT NULL,
    `keyword` VARCHAR(255) NOT NULL,
    `status` INT(1) NOT NULL,
    PRIMARY KEY (`url_alias_id`),
    INDEX `query` (`query`),
    INDEX `keyword` (`keyword`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
```