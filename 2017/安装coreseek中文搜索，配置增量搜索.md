---
title: 安装coreseek中文搜索，配置增量搜索
tags:
  - coreseek，中文搜索
id: 711
categories:
  - linux
date: 2015-05-04 20:26:27
---

中文搜索coreseek安装，安装步骤：

1\. 下载安装包

```php
wget http://www.coreseek.cn/uploads/csft/4.0/coreseek-4.1-beta.tar.gz
```

2\. 解压

```php
tar xzvf coreseek-4.1-beta.tar.gz
```

3.##安装mmseg，中文分词

```php
 cd coreseek-4.1-beta
 cd mmseg-3.2.14
 ./bootstrap    #输出的warning信息可以忽略，如果出现error则需要解决
 ./configure --prefix=/usr/local/mmseg3
 make &amp;&amp; make install
 cd ..
 ```

4.安装coreseek

```php
cd csft-4.1
sh buildconf.sh    #输出的warning信息可以忽略，如果出现error则需要解决
 ./configure --prefix=/usr/local/coreseek  --without-unixodbc --with-mmseg
--with-mmseg-includes=/usr/local/mmseg3/include/mmseg/
--with-mmseg-libs=/usr/local/mmseg3/lib/ --with-mysql  ##如果提示mysql问题，可以查看MySQL数据源安装说明
 make &amp;&amp; make install
cd ..
```

5.测试mmseg分词，coreseek搜索（需要预先设置好字符集为zh_CN.UTF-8，确保正确显示中文）

```php
 cd testpack
 cat var/test/test.xml    #此时应该正确显示中文
 /usr/local/mmseg3/bin/mmseg -d /usr/local/mmseg3/etc var/test/test.xml
 /usr/local/coreseek/bin/indexer -c etc/csft.conf --all
 /usr/local/coreseek/bin/search -c etc/csft.conf 网络搜索
 ```

6.创建索引，启动服务，（需要定义好配置文件，我定义的配置文件名为mysql.conf）

```php
#建立索引 indexer
/usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/mysql.conf --all

#守护进程 searchd
/usr/local/coreseek/bin/searchd -c  /usr/local/coreseek/etc/mysql.conf
 ```

7.查看服务是否启动

```php
[root@localhost data]# ps aux | grep searchd
root 2705  0.0  0.2  53584  1376 pts/0 S 11:12  0:00 /usr/local/coreseek/bin/searchd
-c /home/coreseek-4.1-beta/testpack/etc/mysql.conf
root 2711  0.0  0.1 103184   836 pts/0 S+ 11:13  0:00 grep searchd
```

8.创建增量索引计划任务，及主索引计划任务

```php
#主索引 创建计划任务时要删除pid等
/usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/mysql.conf --all

#增量索引 indexer --rotate,已启动服务，请使用/usr/local/coreseek/bin/indexer -c 配置文件 --all --rotate
/usr/local/coreseek/bin/indexer mysql1 --config /usr/local/coreseek/etc/mysql.conf --rotate
```

# 附件，附录我使用的增量索引的配置文件

9.mysql.conf 配置文件

```php
#MySQL数据源配置，详情请查看：http://www.coreseek.cn/products-install/mysql/
#请先将var/test/documents.sql导入数据库，并配置好以下的MySQL用户密码数据库

#源定义
source mysql
{
    type                    = mysql

    sql_host                = localhost
    sql_user                = root
    sql_pass                =
    sql_db                    = test
    sql_port                = 3306
    sql_query_pre            = SET NAMES utf8

    sql_query_pre = REPLACE INTO coreseek_count SELECT 1, MAX(id) FROM article
    sql_query_range	= SELECT MIN(id),MAX(id) FROM xda_xitem #分区查询范围
    sql_range_step = 10000	#分区查询步进值
    #sql_query = SELECT * FROM documents WHERE id&gt;=$start AND id&lt;=$end

    sql_query  = select id, id as aid, title, stime from article where status=1 and id&gt;=$start
                 AND id&lt;=$end and id&lt;=( SELECT max_id FROM coreseek_count WHERE id=1 )

                  #sql_query第一列id需为整数
                  #title、content作为字符串/文本字段，被全文索引
    sql_attr_uint            = aid           #从SQL读取到的值必须为整数 声明无符号整数属性
    #sql_attr_timestamp      = date_added #从SQL读取到的值必须为整数，作为时间属性

    sql_query_info_pre      = SET NAMES utf8     #命令行查询时，设置正确的字符集
    sql_query_info          = SELECT * FROM article WHERE id=$id #命令行查询时，从数据库读取原始数据
    sql_attr_timestamp	    = stime

}
#增量索引源
source mysql1
{
    type                    = mysql

    sql_host                = localhost
    sql_user                = root
    sql_pass                =
    sql_db                  = test
    sql_port                = 3306
    sql_query_pre           = SET NAMES utf8

    sql_query_pre = select max(id) from article
    sql_query_range	= SELECT MIN(id),MAX(id) FROM article #分区查询范围
    sql_range_step = 10000	#分区查询步进值
    #sql_query = SELECT * FROM documents WHERE id&gt;=$start AND id&lt;=$end

    sql_query                = select id, id as aid, title, stime from article where status=1
            and id&gt;=$start AND id&lt;=$end and id &gt;( SELECT max_id FROM coreseek_count WHERE id=1 )

              #sql_query第一列id需为整数
              #title、content作为字符串/文本字段，被全文索引
    sql_attr_uint            = aid           #从SQL读取到的值必须为整数 声明无符号整数属性
    #sql_attr_timestamp        = date_added #从SQL读取到的值必须为整数，作为时间属性

    sql_query_info_pre      = SET NAMES utf8    #命令行查询时，设置正确的字符集
    sql_query_info          = SELECT * FROM article WHERE id=$id #命令行查询时，从数据库读取原始数据
    sql_attr_timestamp	    = stime

}

#index定义
index mysql
{
    source            = mysql             #对应的source名称
    path            =  /usr/local/coreseek/var/data/index #请修改为实际使用的绝对路径，索引文件路径
    docinfo            = extern
    mlock            = 0
    morphology        = none
    min_word_len        = 1
    html_strip                = 0

    #中文分词配置，详情请查看：http://www.coreseek.cn/products-install/coreseek_mmseg/
    charset_dictpath = /usr/local/mmseg3/etc/ #BSD、Linux环境下设置，/符号结尾
    #Windows环境下设置，/符号结尾，最好给出绝对路径，例如：C:/usr/local/coreseek/etc/...
    #charset_dictpath = etc/
    charset_type        = zh_cn.utf-8
    #charset_type        = utf-8
    #type = rt
    rt_attr_string = title
    rt_field = title
    ngram_len           = 0

    #min_prefix_len = 3	 	#索引前缀
    #prefix_fields = title

    min_infix_len = 1
    infix_fields = title	#索引中缀
    enable_star = 0	#

}

index mysql1
{
    source    = mysql1             #对应的source名称
    path      =  /usr/local/coreseek/var/data/index1  #请修改为实际使用的绝对路径，索引文件路径
    docinfo   = extern
    mlock            = 0
    morphology        = none
    min_word_len        = 1
    html_strip                = 0

    #中文分词配置，详情请查看：http://www.coreseek.cn/products-install/coreseek_mmseg/
    charset_dictpath = /usr/local/mmseg3/etc/ #BSD、Linux环境下设置，/符号结尾

    #Windows环境下设置，/符号结尾，最好给出绝对路径，例如：C:/usr/local/coreseek/etc/...
    #charset_dictpath = etc/
    charset_type        = zh_cn.utf-8
    #charset_type        = utf-8
    #type = rt
    rt_attr_string = title
    rt_field = title
    ngram_len           = 0

    #min_prefix_len = 3	 	#索引前缀
    #prefix_fields = title

    min_infix_len = 1
    prefix_fields = title	#索引中缀
    enable_star = 0	#

}

#全局index定义
indexer
{
    mem_limit            = 128M
}

#searchd服务定义
searchd
{
    listen              =   9312
    read_timeout        = 5
    max_children        = 30
    max_matches            = 10000	#最大匹配数
    seamless_rotate        = 0
    preopen_indexes        = 0
    unlink_old            = 1
    pid_file = /usr/local/coreseek/var/data/searchd_mysql.pid  #请修改为实际使用的绝对路径
    log = /usr/local/coreseek/var/log/searchd_mysql.log        #请修改为实际使用的绝对路径
    query_log = /usr/local/coreseek/var/log/query_mysql.log #请修改为实际使用的绝对路径.
    binlog_path =                                #关闭binlog日志

    compat_sphinxql_magics = 0

}
```

10.增量索引表：必须保证数据有id=1这一行，因为mysql.conf配置文件会更新ID=1的数据

```php
CREATE TABLE `coreseek_count` (
	`id` INT(11) UNSIGNED NOT NULL,
	`max_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COMMENT='用于记录coreseek搜索的增量索引ID'
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

```

11.自定义mmseg分词,系统的分词库不够强大，可以自己定义自己需要的分词库

为此单独写一篇文章 [Coreseek中自定义mmseg分词](http://www.pangxieke.com/linux/673.html "Coreseek中自定义mmseg分词")