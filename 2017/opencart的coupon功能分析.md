---
title: opencart的coupon功能分析
id: 1086
categories:
  - php
date: 2016-10-18 19:50:51
tags: opencart
---

[![QQ20161018193945-b](/images/2016/10/QQ20161018193945-b.png)](/images/2016/10/QQ20161018193945-b.png)
opencart的优惠券，能够方便市场进行推广，具有灵活的使用限制，可以限制使用时间，订单最新金额，使用商品等。因而是一个是否实用的功能。

## 1. 开启coupon功能

```php
UPDATE `setting` SET `value`='1' WHERE `key` ='coupon_status';
```

## 2. 购物车页面展示

控制器`catalog/controller/checkout/cart.php`
```php
$data['coupon'] = $this->load->controller('checkout/coupon');//加载coupon使用的view
```

`catalog/checkout/cart.tpl`
```php
<?php if ($coupon) { ?>
    <div class="panel-group" id="accordion"><?php echo $coupon; ?></div>
<?php } ?>
```

对应的效果如图

## 3. coupon如何影响订单价格

```php
$results = $this->model_extension_extension->getExtensions('total');
 
foreach ($results as $key => $value) {
    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
}
 
array_multisort($sort_order, SORT_ASC, $results);
 
foreach ($results as $result) {
    if ($this->config->get($result['code'] . '_status')) {
        $this->load->model('total/' . $result['code']);
 
        $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
    }
}
```

## 4.coupon计算订单价格

`catalog/model/total/coupon.php`
```php
class ModelTotalCoupon extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
    //
    }
 }
```

## 5. 订单支付完成时，记录coupon使用记录

`catalog/controller/payment/***.php` ***为具体的支付方式，例如支付宝
```php
$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('alipay_order_status_id'));

```

`model/checkout/order.php`
```php
public function addOrderHistory($order_id, $order_status_id, $comment = '', $notify = false) {
    $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
 
    foreach ($order_total_query->rows as $order_total) {
        $this->load->model('total/' . $order_total['code']);
 
        if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
            $this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
        }
    }
}
```

`catalog/model/total/coupon.php`

```php
public function confirm($order_info, $order_total) {
    $code = '';
 
    $start = strpos($order_total['title'], '(') + 1;
    $end = strrpos($order_total['title'], ')');
 
    if ($start && $end) {
        $code = substr($order_total['title'], $start, $end - $start);
    }
 
    $this->load->model('checkout/coupon');
 
    $coupon_info = $this->model_checkout_coupon->getCoupon($code);
 
    if ($coupon_info) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_info['coupon_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', customer_id = '" . (int)$order_info['customer_id'] . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
    }
}
```

## 6. 后台添加coupon

market>coupon>add

可以灵活限制可以使用coupon的产品

可以灵活限制可以使用coupon的最新订单金额
[![QQ20161018194833](/images/2016/10/QQ20161018194833.png)](/images/2016/10/QQ20161018194833.png)