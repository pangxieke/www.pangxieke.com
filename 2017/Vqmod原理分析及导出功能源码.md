---
title: Vqmod原理分析及导出功能源码
id: 1105
categories:
  - php
date: 2016-11-10 20:56:15
tags:
---

[![ninja147774023196016](/images/2016/11/ninja147774023196016.jpg)](/images/2016/11/ninja147774023196016.jpg)

今天开发表格查询导出功能，发现以前的同事在opencart上已经开发了相关功能，但我查找相关的逻辑，却一直无法找到对应的代码。很好奇，是如何实现的导出的功能。

后来找了好久，才明白了，是使用了Vqmod的一个导出插件。查找相关了文档，找到了一个比较准确的定义。

## vQmod简介

"vQmod™"（又称为虚拟快速Mod）是一個覆盖系统的设计，以避免改变原系统的核心文件，目前已经逐渐成为主流趋势。这个概念很简单，创建xml搜索/替换脚本文件，而不是直接更改核心文件。在页面加载解析为每个源核心文件使用php函数include或require_once来载入脚本文件。当源核心文件需要修改时，会生成一个临时文件。该临时文件在执行过程中取代了原来的核心文件，原来的核心文件是永远不会改变的。

因此，vQmod可在执行过程中不修改任何原本的核心文件，而对于原核心文件产生虚拟改变的效果。
目前vQmod使用xml，但不排除将来也采用其他的文件格式。
具体的详细资料，大家可以参考官网 http://code.google.com/p/vqmod/

## 原理分析

根据文档的描述，vQmod技术可以在不修改系统文件的情况下，对原系统的功能做任意更改，这样做的一个很大的好处是新系统不会因为二次开发而不能把系统升级到最新版本。

大概原理就是把原系统中的所有include(_once)、require(_once)中的文件路径替换成VQMod::modCheck()，参数就是该文件路径，该函数会根据用户定义的规则，把相关文件修改之后存一份缓存，然后返回缓存文件的路径，这样就可以实现对原系统的修改。

从代码层面来看，首先是执行了VQMod::bootup()，这个函数会扫描vqmod/xml文件夹下所有的xml文件，解析xml文件后放入静态属性$_mods中。而VQMod::modCheck这个函数根据文件路径，生成一个缓存文件的路径，如果该缓存文件存在并且未过期就直接返回缓存文件路径，否则检查静态属性$_mods中是否存在对该文件修改的规则，存在就根据规则生成新文件存入缓存文件，并返回缓存文件路径，否则返回原文件路径。

vqmod需要dom扩展的支持，用于解析xml文件，如果一个php运行环境不支持dom扩展，则vqmod就不能使用。个人认为这个完全可以用php或json来替代，使用php可以直接用一个return array()来返回需要的信息；而使用json的唯一问题是引号，会导致规则编写不方便。这个也比较容易解决，可以做一个vqmod规则生成页面，用工具来生成对应的规则文件。

## 安装vqmod

Github地址：https://github.com/vqmod/vqmod/wiki
代码地址：http://code.google.com/p/vqmod/wiki/Install_OpenCart
1.将压缩包解压后把文件上传后上传到网站根目录
2.将vqmod/vqcache文件夹权限修改为755或者777
3.浏览器中执行http://www.yoursite.com/vqmod/install
4.有安装成功的提示VQMOD HAS BEEN INSTALLED ON YOUR SYSTEM!

## 导出功能Demo

```php
//文件路径vqmod/xml/export.xml
<modification> 
    <id>Very simple AJAX live search</id>
    <version>1.0.0</version>
    <vqmver>1.2.3</vqmver>
    <author>n[oO]ne</author>
    
   <file path="admin/controller/sale/order.php">
        <operation>
            <search position="before"><![CDATA[
            $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
            ]]></search>
            <add><![CDATA[
            $data['export'] = $this->url->link('sale/order/export', 'token=' . $this->session->data['token'] . $url, 'SSL');
            ]]></add>
        </operation>
         
            <operation>
            <search position="before"><![CDATA[
            $data['button_invoice_print'] = $this->language->get('button_invoice_print');
            ]]></search>
            <add><![CDATA[
            $data['button_export'] = $this->language->get('button_export');
            ]]></add>
        </operation>
         
            <operation>
            <search position="before"><![CDATA[
            protected function getList() {
            ]]></search>
            <add><![CDATA[
public function export()
{
    //查询条件
    $data = array();
    $orders = array();
    $orders_column = array();
    $this->load->model('sale/order');
    $results = $this->model_sale_order->getOrdersexport($data);
    $orders_list = array();
    foreach ($results as $result) {
        $orders_list[] = array('order_id' => $result['order_id'], 'order_rand_no' => $result['order_rand_no'], 'customer_group' => $result['customer_group'], 'customer_name' => $result['customer_name'], 'email' => $result['email'], 'telephone' => $result['telephone'], 'payment_address' => $result['payment_address'], 'shipping_address' => $result['shipping_address'], 'payment_method' => $result['payment_method'], 'shipping_method' => $result['shipping_method'], 'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']), 'currency_code' => $result['currency_code'], 'date_added' => $result['date_added'], 'order_status' => $result['order_status']);
    }
    $orders_column = array('Order ID', 'Order Rand No', 'Customer Group', 'Customer Name', 'Email', 'Telephone', 'Payment Address', 'Shipping Address', 'Payment Method', 'Shipping Method', 'Total', 'Currency Code', 'Date Added', 'Order Status');
    $orders[0] = $orders_column;
    foreach ($orders_list as $orders_row) {
        $orders[] = $orders_row;
    }
    require_once DIR_SYSTEM . 'library/excel_xml.php';
    $xls = new Excel_XML('UTF-8', false, 'Orders List');
    $xls->addArray($orders);
    $xls->generateXML('orderslist_' . date('Y-m-d _ H:i:s'));
}
            ]]></add>
        </operation>
         
    </file>
 
    <file path="admin/model/sale/order.php">
        <operation>
            <search position="before"><![CDATA[public function getOrders($data = array()) {]]></search>
            <add><![CDATA[
public function getOrdersexport($data = array())
{
    $sql = "SELECT o.order_id, o.order_rand_no, cgd.name as customer_group, CONCAT(o.firstname, ' ', o.lastname) AS customer_name,\r\nemail, telephone, CONCAT(o.payment_firstname, ' ', o.payment_lastname,',',o.payment_address_1,',',o.payment_address_2,',',o.payment_city,'-',o.payment_postcode) AS payment_address,\r\nCONCAT(o.shipping_firstname, ' ', o.shipping_lastname,',',o.shipping_address_1,',',o.shipping_address_2,',', o.shipping_city,'-',o.shipping_postcode) AS shipping_address,\r\n o.payment_method, o.shipping_method, o.total, o.currency_code,o.currency_value,\r\n o.date_added, oos.name as order_status\r\nFROM `" . DB_PREFIX . "order` o\r\nLEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (o.customer_group_id = cgd.customer_group_id and cgd.language_id = '" . (int) $this->config->get('config_language_id') . "')\r\nLEFT JOIN " . DB_PREFIX . "order_status oos ON (o.order_status_id = oos.order_status_id) WHERE oos.language_id = '" . (int) $this->config->get('config_language_id') . "'";
    if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
        $sql .= " AND o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
    } else {
        $sql .= " AND o.order_status_id > '0'";
    }
    if (!empty($data['filter_order_id'])) {
        $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
    }
    if (!empty($data['filter_customer'])) {
        $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
    }
    if (!empty($data['filter_date_added'])) {
        $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }
    if (!empty($data['filter_date_modified'])) {
        $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
    }
    if (!empty($data['filter_total'])) {
        $sql .= " AND o.total = '" . (double) $data['filter_total'] . "'";
    }
    $sort_data = array('o.order_id', 'customer', 'status', 'o.date_added', 'o.date_modified', 'o.total');
    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
        $sql .= " ORDER BY " . $data['sort'];
    } else {
        $sql .= " ORDER BY o.order_id";
    }
    $data['order'] = 'DESC';
    if (isset($data['order']) && $data['order'] == 'DESC') {
        $sql .= " DESC";
    } else {
        $sql .= " ASC";
    }
    if (isset($data['start']) || isset($data['limit'])) {
        if ($data['start'] < 0) {
            $data['start'] = 0;
        }
        if ($data['limit'] < 1) {
            $data['limit'] = 20;
        }
        $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
    }
    $query = $this->db->query($sql);
    return $query->rows;
}
            ]]></add>
        </operation>
    </file>
</modification>
```