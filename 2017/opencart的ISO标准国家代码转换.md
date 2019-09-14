---
title: opencart的ISO标准国家代码转换
tags:
  - opencart
id: 1084
categories:
  - mysql
date: 2016-10-18 18:20:02
---

[![table_old](/images/2016/10/table_old.png)](/images/2016/10/table_old.png)

国际标准化组织的ISO 3166-1国际标准是ISO 3166的第一部分，有ISO标准国家代码。1974年首次出版。每个国际普遍公认的国家或地区有三种代码，就是二位字母代码、三位字母代码、以及联合国统计局所建立的三位数字代码。

## 需求

历史项目中使用了opencart的country表。但只有三位数字的代码，没有二位数代码。现在需要用到二位数代码。需要找到对应的二位数代码，进行一行数据修改。有245个国家，手动一个个国家修改费事，而且容易出错。

## 方法

找到一个既有二位代码又有三位代码对应的国家表，获取到国家对应的二位代码，生成update语句。然后在自己项目中执行sql语句。
这里需要用到mysql的concat函数
```php
//country表为ISO标准表，country_new为项目用的表
select CONCAT( 'update country_new SET `iso_code_2`= "', iso_code_2, '" where iso_code_3="', iso_code_3,'";' ) from country

```

## SQL语句

```php
update ry_country SET `iso_code_2`= "AF" where iso_code_3="AFG"; 
update ry_country SET `iso_code_2`= "AL" where iso_code_3="ALB"; 
update ry_country SET `iso_code_2`= "DZ" where iso_code_3="DZA"; 
update ry_country SET `iso_code_2`= "AS" where iso_code_3="ASM"; 
update ry_country SET `iso_code_2`= "AD" where iso_code_3="AND"; 
update ry_country SET `iso_code_2`= "AO" where iso_code_3="AGO"; 
update ry_country SET `iso_code_2`= "AI" where iso_code_3="AIA"; 
update ry_country SET `iso_code_2`= "AQ" where iso_code_3="ATA"; 
update ry_country SET `iso_code_2`= "AG" where iso_code_3="ATG"; 
update ry_country SET `iso_code_2`= "AR" where iso_code_3="ARG"; 
update ry_country SET `iso_code_2`= "AM" where iso_code_3="ARM"; 
update ry_country SET `iso_code_2`= "AW" where iso_code_3="ABW"; 
update ry_country SET `iso_code_2`= "AU" where iso_code_3="AUS"; 
update ry_country SET `iso_code_2`= "AT" where iso_code_3="AUT"; 
update ry_country SET `iso_code_2`= "AZ" where iso_code_3="AZE"; 
update ry_country SET `iso_code_2`= "BS" where iso_code_3="BHS"; 
update ry_country SET `iso_code_2`= "BH" where iso_code_3="BHR"; 
update ry_country SET `iso_code_2`= "BD" where iso_code_3="BGD"; 
update ry_country SET `iso_code_2`= "BB" where iso_code_3="BRB"; 
update ry_country SET `iso_code_2`= "BY" where iso_code_3="BLR"; 
update ry_country SET `iso_code_2`= "BE" where iso_code_3="BEL"; 
update ry_country SET `iso_code_2`= "BZ" where iso_code_3="BLZ"; 
update ry_country SET `iso_code_2`= "BJ" where iso_code_3="BEN"; 
update ry_country SET `iso_code_2`= "BM" where iso_code_3="BMU"; 
update ry_country SET `iso_code_2`= "BT" where iso_code_3="BTN"; 
update ry_country SET `iso_code_2`= "BO" where iso_code_3="BOL"; 
update ry_country SET `iso_code_2`= "BA" where iso_code_3="BIH"; 
update ry_country SET `iso_code_2`= "BW" where iso_code_3="BWA"; 
update ry_country SET `iso_code_2`= "BV" where iso_code_3="BVT"; 
update ry_country SET `iso_code_2`= "BR" where iso_code_3="BRA"; 
update ry_country SET `iso_code_2`= "IO" where iso_code_3="IOT"; 
update ry_country SET `iso_code_2`= "BN" where iso_code_3="BRN"; 
update ry_country SET `iso_code_2`= "BG" where iso_code_3="BGR"; 
update ry_country SET `iso_code_2`= "BF" where iso_code_3="BFA"; 
update ry_country SET `iso_code_2`= "BI" where iso_code_3="BDI"; 
update ry_country SET `iso_code_2`= "KH" where iso_code_3="KHM"; 
update ry_country SET `iso_code_2`= "CM" where iso_code_3="CMR"; 
update ry_country SET `iso_code_2`= "CA" where iso_code_3="CAN"; 
update ry_country SET `iso_code_2`= "CV" where iso_code_3="CPV"; 
update ry_country SET `iso_code_2`= "KY" where iso_code_3="CYM"; 
update ry_country SET `iso_code_2`= "CF" where iso_code_3="CAF"; 
update ry_country SET `iso_code_2`= "TD" where iso_code_3="TCD"; 
update ry_country SET `iso_code_2`= "CL" where iso_code_3="CHL"; 
update ry_country SET `iso_code_2`= "CN" where iso_code_3="CHN"; 
update ry_country SET `iso_code_2`= "CX" where iso_code_3="CXR"; 
update ry_country SET `iso_code_2`= "CC" where iso_code_3="CCK"; 
update ry_country SET `iso_code_2`= "CO" where iso_code_3="COL"; 
update ry_country SET `iso_code_2`= "KM" where iso_code_3="COM"; 
update ry_country SET `iso_code_2`= "CG" where iso_code_3="COG"; 
update ry_country SET `iso_code_2`= "CK" where iso_code_3="COK"; 
update ry_country SET `iso_code_2`= "CR" where iso_code_3="CRI"; 
update ry_country SET `iso_code_2`= "CI" where iso_code_3="CIV"; 
update ry_country SET `iso_code_2`= "HR" where iso_code_3="HRV"; 
update ry_country SET `iso_code_2`= "CU" where iso_code_3="CUB"; 
update ry_country SET `iso_code_2`= "CY" where iso_code_3="CYP"; 
update ry_country SET `iso_code_2`= "CZ" where iso_code_3="CZE"; 
update ry_country SET `iso_code_2`= "DK" where iso_code_3="DNK"; 
update ry_country SET `iso_code_2`= "DJ" where iso_code_3="DJI"; 
update ry_country SET `iso_code_2`= "DM" where iso_code_3="DMA"; 
update ry_country SET `iso_code_2`= "DO" where iso_code_3="DOM"; 
update ry_country SET `iso_code_2`= "TL" where iso_code_3="TLS"; 
update ry_country SET `iso_code_2`= "EC" where iso_code_3="ECU"; 
update ry_country SET `iso_code_2`= "EG" where iso_code_3="EGY"; 
update ry_country SET `iso_code_2`= "SV" where iso_code_3="SLV"; 
update ry_country SET `iso_code_2`= "GQ" where iso_code_3="GNQ"; 
update ry_country SET `iso_code_2`= "ER" where iso_code_3="ERI"; 
update ry_country SET `iso_code_2`= "EE" where iso_code_3="EST"; 
update ry_country SET `iso_code_2`= "ET" where iso_code_3="ETH"; 
update ry_country SET `iso_code_2`= "FK" where iso_code_3="FLK"; 
update ry_country SET `iso_code_2`= "FO" where iso_code_3="FRO"; 
update ry_country SET `iso_code_2`= "FJ" where iso_code_3="FJI"; 
update ry_country SET `iso_code_2`= "FI" where iso_code_3="FIN"; 
update ry_country SET `iso_code_2`= "FR" where iso_code_3="FRA"; 
update ry_country SET `iso_code_2`= "GF" where iso_code_3="GUF"; 
update ry_country SET `iso_code_2`= "PF" where iso_code_3="PYF"; 
update ry_country SET `iso_code_2`= "TF" where iso_code_3="ATF"; 
update ry_country SET `iso_code_2`= "GA" where iso_code_3="GAB"; 
update ry_country SET `iso_code_2`= "GM" where iso_code_3="GMB"; 
update ry_country SET `iso_code_2`= "GE" where iso_code_3="GEO"; 
update ry_country SET `iso_code_2`= "DE" where iso_code_3="DEU"; 
update ry_country SET `iso_code_2`= "GH" where iso_code_3="GHA"; 
update ry_country SET `iso_code_2`= "GI" where iso_code_3="GIB"; 
update ry_country SET `iso_code_2`= "GR" where iso_code_3="GRC"; 
update ry_country SET `iso_code_2`= "GL" where iso_code_3="GRL"; 
update ry_country SET `iso_code_2`= "GD" where iso_code_3="GRD"; 
update ry_country SET `iso_code_2`= "GP" where iso_code_3="GLP"; 
update ry_country SET `iso_code_2`= "GU" where iso_code_3="GUM"; 
update ry_country SET `iso_code_2`= "GT" where iso_code_3="GTM"; 
update ry_country SET `iso_code_2`= "GN" where iso_code_3="GIN"; 
update ry_country SET `iso_code_2`= "GW" where iso_code_3="GNB"; 
update ry_country SET `iso_code_2`= "GY" where iso_code_3="GUY"; 
update ry_country SET `iso_code_2`= "HT" where iso_code_3="HTI"; 
update ry_country SET `iso_code_2`= "HM" where iso_code_3="HMD"; 
update ry_country SET `iso_code_2`= "HN" where iso_code_3="HND"; 
update ry_country SET `iso_code_2`= "HK" where iso_code_3="HKG"; 
update ry_country SET `iso_code_2`= "HU" where iso_code_3="HUN"; 
update ry_country SET `iso_code_2`= "IS" where iso_code_3="ISL"; 
update ry_country SET `iso_code_2`= "IN" where iso_code_3="IND"; 
update ry_country SET `iso_code_2`= "ID" where iso_code_3="IDN"; 
update ry_country SET `iso_code_2`= "IR" where iso_code_3="IRN"; 
update ry_country SET `iso_code_2`= "IQ" where iso_code_3="IRQ"; 
update ry_country SET `iso_code_2`= "IE" where iso_code_3="IRL"; 
update ry_country SET `iso_code_2`= "IL" where iso_code_3="ISR"; 
update ry_country SET `iso_code_2`= "IT" where iso_code_3="ITA"; 
update ry_country SET `iso_code_2`= "JM" where iso_code_3="JAM"; 
update ry_country SET `iso_code_2`= "JP" where iso_code_3="JPN"; 
update ry_country SET `iso_code_2`= "JO" where iso_code_3="JOR"; 
update ry_country SET `iso_code_2`= "KZ" where iso_code_3="KAZ"; 
update ry_country SET `iso_code_2`= "KE" where iso_code_3="KEN"; 
update ry_country SET `iso_code_2`= "KI" where iso_code_3="KIR"; 
update ry_country SET `iso_code_2`= "KP" where iso_code_3="PRK"; 
update ry_country SET `iso_code_2`= "KR" where iso_code_3="KOR"; 
update ry_country SET `iso_code_2`= "KW" where iso_code_3="KWT"; 
update ry_country SET `iso_code_2`= "KG" where iso_code_3="KGZ"; 
update ry_country SET `iso_code_2`= "LA" where iso_code_3="LAO"; 
update ry_country SET `iso_code_2`= "LV" where iso_code_3="LVA"; 
update ry_country SET `iso_code_2`= "LB" where iso_code_3="LBN"; 
update ry_country SET `iso_code_2`= "LS" where iso_code_3="LSO"; 
update ry_country SET `iso_code_2`= "LR" where iso_code_3="LBR"; 
update ry_country SET `iso_code_2`= "LY" where iso_code_3="LBY"; 
update ry_country SET `iso_code_2`= "LI" where iso_code_3="LIE"; 
update ry_country SET `iso_code_2`= "LT" where iso_code_3="LTU"; 
update ry_country SET `iso_code_2`= "LU" where iso_code_3="LUX"; 
update ry_country SET `iso_code_2`= "MO" where iso_code_3="MAC"; 
update ry_country SET `iso_code_2`= "MK" where iso_code_3="MKD"; 
update ry_country SET `iso_code_2`= "MG" where iso_code_3="MDG"; 
update ry_country SET `iso_code_2`= "MW" where iso_code_3="MWI"; 
update ry_country SET `iso_code_2`= "MY" where iso_code_3="MYS"; 
update ry_country SET `iso_code_2`= "MV" where iso_code_3="MDV"; 
update ry_country SET `iso_code_2`= "ML" where iso_code_3="MLI"; 
update ry_country SET `iso_code_2`= "MT" where iso_code_3="MLT"; 
update ry_country SET `iso_code_2`= "MH" where iso_code_3="MHL"; 
update ry_country SET `iso_code_2`= "MQ" where iso_code_3="MTQ"; 
update ry_country SET `iso_code_2`= "MR" where iso_code_3="MRT"; 
update ry_country SET `iso_code_2`= "MU" where iso_code_3="MUS"; 
update ry_country SET `iso_code_2`= "YT" where iso_code_3="MYT"; 
update ry_country SET `iso_code_2`= "MX" where iso_code_3="MEX"; 
update ry_country SET `iso_code_2`= "FM" where iso_code_3="FSM"; 
update ry_country SET `iso_code_2`= "MD" where iso_code_3="MDA"; 
update ry_country SET `iso_code_2`= "MC" where iso_code_3="MCO"; 
update ry_country SET `iso_code_2`= "MN" where iso_code_3="MNG"; 
update ry_country SET `iso_code_2`= "MS" where iso_code_3="MSR"; 
update ry_country SET `iso_code_2`= "MA" where iso_code_3="MAR"; 
update ry_country SET `iso_code_2`= "MZ" where iso_code_3="MOZ"; 
update ry_country SET `iso_code_2`= "MM" where iso_code_3="MMR"; 
update ry_country SET `iso_code_2`= "NA" where iso_code_3="NAM"; 
update ry_country SET `iso_code_2`= "NR" where iso_code_3="NRU"; 
update ry_country SET `iso_code_2`= "NP" where iso_code_3="NPL"; 
update ry_country SET `iso_code_2`= "NL" where iso_code_3="NLD"; 
update ry_country SET `iso_code_2`= "AN" where iso_code_3="ANT"; 
update ry_country SET `iso_code_2`= "NC" where iso_code_3="NCL"; 
update ry_country SET `iso_code_2`= "NZ" where iso_code_3="NZL"; 
update ry_country SET `iso_code_2`= "NI" where iso_code_3="NIC"; 
update ry_country SET `iso_code_2`= "NE" where iso_code_3="NER"; 
update ry_country SET `iso_code_2`= "NG" where iso_code_3="NGA"; 
update ry_country SET `iso_code_2`= "NU" where iso_code_3="NIU"; 
update ry_country SET `iso_code_2`= "NF" where iso_code_3="NFK"; 
update ry_country SET `iso_code_2`= "MP" where iso_code_3="MNP"; 
update ry_country SET `iso_code_2`= "NO" where iso_code_3="NOR"; 
update ry_country SET `iso_code_2`= "OM" where iso_code_3="OMN"; 
update ry_country SET `iso_code_2`= "PK" where iso_code_3="PAK"; 
update ry_country SET `iso_code_2`= "PW" where iso_code_3="PLW"; 
update ry_country SET `iso_code_2`= "PA" where iso_code_3="PAN"; 
update ry_country SET `iso_code_2`= "PG" where iso_code_3="PNG"; 
update ry_country SET `iso_code_2`= "PY" where iso_code_3="PRY"; 
update ry_country SET `iso_code_2`= "PE" where iso_code_3="PER"; 
update ry_country SET `iso_code_2`= "PH" where iso_code_3="PHL"; 
update ry_country SET `iso_code_2`= "PN" where iso_code_3="PCN"; 
update ry_country SET `iso_code_2`= "PL" where iso_code_3="POL"; 
update ry_country SET `iso_code_2`= "PT" where iso_code_3="PRT"; 
update ry_country SET `iso_code_2`= "PR" where iso_code_3="PRI"; 
update ry_country SET `iso_code_2`= "QA" where iso_code_3="QAT"; 
update ry_country SET `iso_code_2`= "RE" where iso_code_3="REU"; 
update ry_country SET `iso_code_2`= "RO" where iso_code_3="ROM"; 
update ry_country SET `iso_code_2`= "RU" where iso_code_3="RUS"; 
update ry_country SET `iso_code_2`= "RW" where iso_code_3="RWA"; 
update ry_country SET `iso_code_2`= "KN" where iso_code_3="KNA"; 
update ry_country SET `iso_code_2`= "LC" where iso_code_3="LCA"; 
update ry_country SET `iso_code_2`= "VC" where iso_code_3="VCT"; 
update ry_country SET `iso_code_2`= "WS" where iso_code_3="WSM"; 
update ry_country SET `iso_code_2`= "SM" where iso_code_3="SMR"; 
update ry_country SET `iso_code_2`= "ST" where iso_code_3="STP"; 
update ry_country SET `iso_code_2`= "SA" where iso_code_3="SAU"; 
update ry_country SET `iso_code_2`= "SN" where iso_code_3="SEN"; 
update ry_country SET `iso_code_2`= "SC" where iso_code_3="SYC"; 
update ry_country SET `iso_code_2`= "SL" where iso_code_3="SLE"; 
update ry_country SET `iso_code_2`= "SG" where iso_code_3="SGP"; 
update ry_country SET `iso_code_2`= "SK" where iso_code_3="SVK"; 
update ry_country SET `iso_code_2`= "SI" where iso_code_3="SVN"; 
update ry_country SET `iso_code_2`= "SB" where iso_code_3="SLB"; 
update ry_country SET `iso_code_2`= "SO" where iso_code_3="SOM"; 
update ry_country SET `iso_code_2`= "ZA" where iso_code_3="ZAF"; 
update ry_country SET `iso_code_2`= "GS" where iso_code_3="SGS"; 
update ry_country SET `iso_code_2`= "ES" where iso_code_3="ESP"; 
update ry_country SET `iso_code_2`= "LK" where iso_code_3="LKA"; 
update ry_country SET `iso_code_2`= "SH" where iso_code_3="SHN"; 
update ry_country SET `iso_code_2`= "PM" where iso_code_3="SPM"; 
update ry_country SET `iso_code_2`= "SD" where iso_code_3="SDN"; 
update ry_country SET `iso_code_2`= "SR" where iso_code_3="SUR"; 
update ry_country SET `iso_code_2`= "SJ" where iso_code_3="SJM"; 
update ry_country SET `iso_code_2`= "SZ" where iso_code_3="SWZ"; 
update ry_country SET `iso_code_2`= "SE" where iso_code_3="SWE"; 
update ry_country SET `iso_code_2`= "CH" where iso_code_3="CHE"; 
update ry_country SET `iso_code_2`= "SY" where iso_code_3="SYR"; 
update ry_country SET `iso_code_2`= "TW" where iso_code_3="TWN"; 
update ry_country SET `iso_code_2`= "TJ" where iso_code_3="TJK"; 
update ry_country SET `iso_code_2`= "TZ" where iso_code_3="TZA"; 
update ry_country SET `iso_code_2`= "TH" where iso_code_3="THA"; 
update ry_country SET `iso_code_2`= "TG" where iso_code_3="TGO"; 
update ry_country SET `iso_code_2`= "TK" where iso_code_3="TKL"; 
update ry_country SET `iso_code_2`= "TO" where iso_code_3="TON"; 
update ry_country SET `iso_code_2`= "TT" where iso_code_3="TTO"; 
update ry_country SET `iso_code_2`= "TN" where iso_code_3="TUN"; 
update ry_country SET `iso_code_2`= "TR" where iso_code_3="TUR"; 
update ry_country SET `iso_code_2`= "TM" where iso_code_3="TKM"; 
update ry_country SET `iso_code_2`= "TC" where iso_code_3="TCA"; 
update ry_country SET `iso_code_2`= "TV" where iso_code_3="TUV"; 
update ry_country SET `iso_code_2`= "UG" where iso_code_3="UGA"; 
update ry_country SET `iso_code_2`= "UA" where iso_code_3="UKR"; 
update ry_country SET `iso_code_2`= "AE" where iso_code_3="ARE"; 
update ry_country SET `iso_code_2`= "GB" where iso_code_3="GBR"; 
update ry_country SET `iso_code_2`= "US" where iso_code_3="USA"; 
update ry_country SET `iso_code_2`= "UM" where iso_code_3="UMI"; 
update ry_country SET `iso_code_2`= "UY" where iso_code_3="URY"; 
update ry_country SET `iso_code_2`= "UZ" where iso_code_3="UZB"; 
update ry_country SET `iso_code_2`= "VU" where iso_code_3="VUT"; 
update ry_country SET `iso_code_2`= "VA" where iso_code_3="VAT"; 
update ry_country SET `iso_code_2`= "VE" where iso_code_3="VEN"; 
update ry_country SET `iso_code_2`= "VN" where iso_code_3="VNM"; 
update ry_country SET `iso_code_2`= "VG" where iso_code_3="VGB"; 
update ry_country SET `iso_code_2`= "VI" where iso_code_3="VIR"; 
update ry_country SET `iso_code_2`= "WF" where iso_code_3="WLF"; 
update ry_country SET `iso_code_2`= "EH" where iso_code_3="ESH"; 
update ry_country SET `iso_code_2`= "YE" where iso_code_3="YEM"; 
update ry_country SET `iso_code_2`= "CD" where iso_code_3="COD"; 
update ry_country SET `iso_code_2`= "ZM" where iso_code_3="ZMB"; 
update ry_country SET `iso_code_2`= "ZW" where iso_code_3="ZWE"; 
update ry_country SET `iso_code_2`= "ME" where iso_code_3="MNE"; 
update ry_country SET `iso_code_2`= "RS" where iso_code_3="SRB"; 
update ry_country SET `iso_code_2`= "AX" where iso_code_3="ALA"; 
update ry_country SET `iso_code_2`= "BQ" where iso_code_3="BES"; 
update ry_country SET `iso_code_2`= "CW" where iso_code_3="CUW"; 
update ry_country SET `iso_code_2`= "PS" where iso_code_3="PSE"; 
update ry_country SET `iso_code_2`= "SS" where iso_code_3="SSD"; 
update ry_country SET `iso_code_2`= "BL" where iso_code_3="BLM"; 
update ry_country SET `iso_code_2`= "MF" where iso_code_3="MAF"; 
update ry_country SET `iso_code_2`= "IC" where iso_code_3="ICA"; 
update ry_country SET `iso_code_2`= "AC" where iso_code_3="ASC"; 
update ry_country SET `iso_code_2`= "XK" where iso_code_3="UNK"; 
update ry_country SET `iso_code_2`= "IM" where iso_code_3="IMN"; 
update ry_country SET `iso_code_2`= "TA" where iso_code_3="SHN"; 
update ry_country SET `iso_code_2`= "GG" where iso_code_3="GGY"; 
update ry_country SET `iso_code_2`= "JE" where iso_code_3="JEY";  
update ry_country SET `iso_code_2`= "RO" where iso_code_3="ROU";  
```