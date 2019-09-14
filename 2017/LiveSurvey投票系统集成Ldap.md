---
title: LiveSurvey投票系统集成Ldap
id: 1164
categories:
  - php
date: 2016-12-19 18:35:03
tags:
---

[![survey_ldap](/images/2016/12/survey_ldap.png)](/images/2016/12/survey_ldap.png)

最新年底，公司需要做一个投票系统，查询到了开源的投票系统LiveSurvey。投票用户限定为全公司用户。
了解到支持ldap，结合ldap，导入了公司所有用户。

## 参考资料

[LimeSurvey官方手册](https://manual.limesurvey.org/LimeSurvey_Manual/zh-hans)

[官方手册关于ldap设置](https://manual.limesurvey.org/LDAP_settings)

[LimeSurvey源代码https://github.com/LimeSurvey/LimeSurvey](https://github.com/LimeSurvey/LimeSurvey)

## 配置ldap

```php
// 1.开启ldap
// application/config/config-defaults.php
$config['enableLdap'] = true;

// 2.修改配置文件
//application/config/ldap.php

$serverId=0;
$ldap_server[$serverId]['server'] = &amp;quot;www.pangxieke.com&amp;quot;;
$ldap_server[$serverId]['port'] = &amp;quot;389&amp;quot;;
$ldap_server[$serverId]['protoversion'] = &amp;quot;ldapv2&amp;quot;;
// 'ldaps' is supported for 'ldapv2' servers
// 'start-tls' is supproted for 'ldapv3' servers
// 但我这两种配置都没有成功，查看源码后，将ldaps改成其他任何支付就可以了，这里改为false
$ldap_server[$serverId]['encrypt'] = &amp;quot;false&amp;quot;;
$ldap_server[$serverId]['referrals'] = false;
$ldap_server[$serverId]['binddn']   = 'uid=mybinduser,dc=mycompany,dc=org';
$ldap_server[$serverId]['bindpw']   = 'AsecretPassword';
```

查询设定

```php
$query_id: is the id of the LDAP query
$ldap_queries[$query_id]['ldapServerId']: bind the query to a specific server
$ldap_queries[$query_id]['name']: String describing the query. It will be displayed in the GUI
$ldap_queries[$query_id]['userbase']: Root DN to use for user searches
$ldap_queries[$query_id]['userfilter']: filter used to select potential users' entries. It must be enclosed in parenthesis
$ldap_queries[$query_id]['userscope']: scope of the LDAP search for users ('base', 'one' or 'sub')
$ldap_queries[$query_id]['firstname_attr']: Ldap attribute that will be mapped to the Firstname field of the token entry
$ldap_queries[$query_id]['lastname_attr']: Ldap attribute that will be mapped to the Lastname field of the token entry
$ldap_queries[$query_id]['email_attr']: Ldap attribute that will be mapped to the email address field of the token entry
Optionaly you can retrieve more information from the directory:
$ldap_queries[$query_id]['token_attr']: Ldap attribute that will be mapped to the token code
$ldap_queries[$query_id]['language']: Ldap attribute that will be mapped to the user language code
$ldap_queries[$query_id]['attr1']: Ldap attribute that will be mapped to the attribute_1 field
$ldap_queries[$query_id]['attr2']: Ldap attribute that will be mapped to the attribute_2 field
```

## 查询所有用户

需要查询所有用户，但是demo提供的方式，没法查出，改写了userfilter参数，能够导入所有用户了。

```php
//$ldap_queries[$query_id]['userfilter'] = '(&amp;amp;(objectClass=inetOrgPerson)(my-fake-accountstatus-attribute=enabled))';
$ldap_queries[$query_id]['userfilter'] = 'objectClass=organizationalPerson';
```

更多设定可以参考[**本站：LimeSurvey中文操作指引**](http://www.pangxieke.com/share/1170.html)