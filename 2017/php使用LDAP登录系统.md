---
title: php使用LDAP登录系统
id: 1052
categories:
  - php
date: 2016-07-20 19:02:45
tags:
---

### LDAP介绍
LDAP(Lightweight Directory Access Protocol)的意思是"轻量级目录访问协议"，是一个用于访问"目录服务器"(Directory Servers)的协议。这里所谓的"目录"是指一种按照树状结构存储信息的数据库。这个概念和硬盘上的目录结构类似，不过LDAP的"根目录"必须是"The world"，并且其一级子目录必须是"countries"。二级目录通常包含有公司(companies)、组织(organisations)、地点(places)等等……相应的三级子目录通常会包含人员(people)、设备(equipment)、文档(documents)

php可以借助LDAP，可以和OA，git等其他系统公用用户

LDAP中则通过"distinguished name"(简称"dn")来表示文件，通常像下面这样：

`cn=John Smith,ou=Accounts,o=My Company,c=US`
逗号(,)在这里表示级别分界线，并且从右向左阅读。上述dn可以理解为：
```
country = US
organization = My Company
organizationalUnit = Accounts
commonName = John Smith
```
术语对比：
dn,entry    目录/文件
attribute   属性
value       值

### PHP与LDAP
PHP默认并不启用LDAP支持，PHP的LDAP模块依赖于OpenLDAP或bind9.net提供的客户端LDAP库，你必须在编译的时候使用 --with-ldap[=DIR] 才行，如果你想要SASL支持，那还必须使用 --with-ldap-sasl[=DIR] 选项，而且你的系统中必须有 sasl.h 头文件才行。
[![ldap](/images/2016/07/ldap.png)](/images/2016/07/ldap.png)

### demo

```php
$ldapConnect=ldap_connect(LDAP_SERVER_IP , LDAP_SERVER_PORT );  
//建立到ldap服务器的连接LDAP_SERVER_IP是ldap服务器ip，LDAP_SERVER_PORT是ldap服务器端口(默认389)  
$bind= @ldap_bind($ldapConnect , $user . ‘@corp.qihoo.net’,$pass);  
//验证帐号密码，ldap_bind第一个为绑定的连接，第二个为用户名(注意是否有后缀)，第三个为密码。  
if($bind )  
{//验证成功  
      $SEARCH_DN= 'ou=XXX,ou=XXX,dc=XXXX,dc=XXXX,dc=XXXX';  
      //搜索基本条件值(类似于数据库的库和表)  
      $SEARCH_FIELDS= array('mail','displayName', 'cn');  
      //需要的搜索结果  
      $result= @ldap_search($ldapConnect,$SEARCH_DN,"cn=" . $user,$SEARCH_FIELDS);  
      //第三个参数是限定搜索结果为用户名为$user(类似where后的搜索条件)            
      $retData = @ldap_get_entries($ldapConnect, $result);  
      foreach($retDataas $k => $v)  
      {//筛选整理数据，返回  
           return array(  
                    'userName'=> $v['cn'][0],  
                    'nickName'=> $v['displayname'][0]  
                    'mail'=> $v['mail'][0]  
           );   
     }    
}  
else 
{
    //验证失败  
}  
ldap_close($ldapConnect);   
//关闭ldap连接  
```

参考文档 [http://www.jinbuguo.com/php/php-ldap.html](http://www.jinbuguo.com/php/php-ldap.html "PHP-LDAP 学习笔记")