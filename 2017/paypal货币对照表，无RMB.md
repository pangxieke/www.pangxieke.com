---
title: paypal货币对照表，无RMB
id: 406
categories:
  - php
date: 2015-01-26 23:03:46
tags:
---

如果发现currency_code是RMB，就把currency_code转换成美元（USD），且按照汇率（$convert_rate）把商品的价格（amount_1）和运费（amount_2）都转化成美元。

如果你想接收其它paypal支持的货币（这货为啥就不支持RMB），比如港币，只需要更改currency code($paypal_args[‘currency_code’])和汇率($convert_rate)就可以了。

货币符号对照表在此，按自己需要的选择吧。

AUD: Australian Dollar

BRL: Brazilian Real

CAD: Canadian Dollar

MXN: Mexican Nuevo Peso

NZD: New Zealand Dollar

HKD: Hong Kong Dollar

SGD: Singapore Dollar

USD: US Dollar

EUR: Euro

JPY: Japanese Yen

TRY: Turkish Lira

NOK: Norwegian Krone

CZK: Czech Koruna

DKK: Danish Krone

HUF: Hungarian Forint

ILS: Israeli New Shekel

MYR: Malaysian Ringgit

PHP: Philippine Peso

PLN: Polish Zloty

SEK: Swedish Krona

CHF: Swiss Franc

TWD: Taiwan Dollar

THB: Thai Baht

GBP: Pound Sterling