---
title: Opencart增加支付方式
id: 1100
categories:
  - php
date: 2016-10-31 18:58:06
tags: opencart
---

[![opencart_payment](/images/2016/10/opencart_payment.png)](/images/2016/10/opencart_payment.png)
电商网站一般都对应了很多的支付方式。例如微信支付、支付宝支付、银联支付。
Opencart新增支付方式是比较容易的，可以通过插件很方便的扩展支付方式。
以下是按照过程，假设安装pangxieke_payment这种支付方式

## 1.将文件放在网站根目录

前台文件
```php
/catalog/controller/payment/pangxieke_payment.php
/catalog/language/engish/payment/pangxieke_payment.php
/catalog/model/payment/pangxieke_payment.php
/view/theme/default/template/payment/pangxieke_payment.tpl
```

后台文件
```php
/admin/controller/payment/pangxieke_payment.php
/admin/language/engish/payment/pangxieke_payment.php
/view/theme/default/template/payment/pangxieke_payment.tpl
```

## 2.到后台安装插件

路径：扩展功能/支付管理
找到对应的pangxieke_payment 点击install，会向extension表写入支付方式
配置相关参数：key等，及网站支付状态

## 3.选择支付方式并调用

在支付页面，选择支付方式页，会查询model/payment下所有的安装并开启的支付方式
原理：
1.选择支付方式时，会ajax加载`catalog/checkout/payment_method.php`
```php
$results = $this->model_extension_extension->getExtensions('payment');//查询安装的支付模块
 
foreach ($results as $result) {
     
    if ($this->config->get($result['code'] . '_status')) {//支付开启状态
         
        $this->load->model('payment/' . $result['code']);
 
        //在已经开启支付方式中，还可以判断当前订单地址是否使用
        $method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);
 
        if ($method) {
            if ($recurring) {
                if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                    $method_data[$result['code']] = $method;
                }
            } else {
                $method_data[$result['code']] = $method;
            }
        }
    }
}
```

`model/extension/extension.php`文件
```php
class ModelExtensionExtension extends Model {
    function getExtensions($type) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
        return $query->rows;
    }
}
```
 
2. 创建订单后，支付时，跳转到选中的支付方式
对应文件`catalog/checkout/done.php`

```
$data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
```

3.加载支付方式对应的index方法，通过各种内嵌，或者跳转等各种方式，展示支付页面

4.用户填写信用卡等信息，发起支付。

5.支付公司通过notice地址，异步返回信息

6.接收通知，处理订单状态。页面作跳转，跳转到支付成功页面。