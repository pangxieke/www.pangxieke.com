---
title: paypal返回数据参数
id: 404
categories:
  - php
date: 2015-03-05 18:44:07
tags:
---

get 过去参数
https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_flow&amp;SESSION=aNxOOLcJheU72UXMHlWneOzW1qd0oOcykcRM1NdemRmbtM1gyW8ZprrDpiO&amp;dispatch=50a222a57771920b6a3d7b606239e4d529b525e0b7e69bf0224adecfb0124e9b61f737ba21b08198d8562aa8a3da7ac30bbfba73b3e80dcc

参数
如果你使用paypal[国内贝宝]的即时付款通知，在paypal会在付款操作的自动返回url里POST一段参数给你，类似如下连接：

http://www.leoneo.com/paypal/test.php?tx=x9E67578X9184704L&amp;st=Completed&amp;amt=0.01&amp;cc=CNY&amp;cm=&amp;sig=FYR%2fc2Q3NTzO0R....etc
其中主要参数是 tx=tx=x9E67578X9184704L ， 这是此次交易的标志ID，可以利用脚本来依照此ID获取当前交易的信息，发送查询后，paypal会返回一串格式化信息，通常有以下信息

mc_gross 交易收入
address_status 地址信息状态
paypal_address_id Paypal地址信息ID
payer_id 付款人的Paypal ID
tax 税收
address_street 通信地址
payment_date 交易时间
payment_status 交易状态
charset 语言编码
address_zip 邮编
first_name 付款人姓氏
address_country_code 国别
address_name 收件人姓名
custom 自定义值
payer_status 付款人账户状态
business 收款人Paypal账户
address_country 通信地址国家
address_city 通信地址城市
quantity 货物数量
payer_email 付款人email
txn_id 交易ID
payment_type 交易类型
last_name 付款人名
address_state 通信地址省份
receiver_email 收款人email
address_owner 尚未公布/正式启用
receiver_id 收款人ID
ebay_address_id 易趣用户地址ID
txn_type 交易通告方式
item_name 货品名称
mc_currency 货币种类
item_number 货品编号
payment_gross 交易总额[只适用于美元情况]
shipping 运送费