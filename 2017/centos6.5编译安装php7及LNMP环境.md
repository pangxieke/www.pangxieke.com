---
title: centos6.5编译安装php7及LNMP环境
tags:
  - lnmp
  - php7
id: 981
categories:
  - linux
date: 2016-04-17 16:01:19
---

实践centos6.5编译安装 php7的LNMP生产环境

费时接近2天，终于成功编译安装了LNMP环境，使用php7。
中间遇到很多问题，费时好久终于解决
期间出现yum install 出错， 提示`Cannot find a valid baseurl for repo: PUIAS_6_computational`
找好久，发现是镜像源配置错误 删除`/etc/yum.repos.d/PUIAS_6_computational` 重新配置镜像

## 一、准备工作：

1、建立一个软件包目录存放,最小化安装centos6.5

```php
mkdir -p /usr/local/src/
#清理已经安装包

rpm -e httpd
rpm -e mysql
rpm -e php
yum -y remove httpd
yum -y remove mysql
yum -y remove php

#搜索apache包
rpm -qa http*

#强制卸载apache包
rpm -e --nodeps #查询出来的文件名 例如rpm -e mysql-libs-5.1.73-3.el6_5.x86_64 --nodeps

#检查是否卸载干净
rpm -qa|grep http*
#selinux可能会致使编译安装失败，我们先禁用它。永久禁用，需要重启生效

sed -i 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/selinux/config
#临时禁用，不需要重启 setenforce 0
```

2、安装必备工具

```php
yum -y install make gcc gcc-c++ gcc-g77 flex bison file libtool libtool-libs autoconf\
 kernel-devel libjpeg libjpeg-devel libpng libpng-devel libpng10 libpng10-devel\
  gd gd-devel freetype freetype-devel libxml2 libxml2-devel zlib zlib-devel \
  glib2 glib2-devel bzip2 bzip2-devel libevent libevent-devel ncurses ncurses-devel \
  curl curl-devel e2fsprogs e2fsprogs-devel krb5 krb5-devel libidn libidn-devel \
  openssl openssl-devel gettext gettext-devel ncurses-devel gmp-devel pspell-devel\
  unzip libcap lsof
```

3、如果想软件安装速度，将yum源设置为阿里云开源镜像
后期发现yum安装一直报错。提示`Cannot find a valid baseurl for repo: PUIAS_6_computational`
找好久，发现是镜像源配置错误 删除`/etc/yum.repos.d/PUIAS_6_computational` 重新配置镜像才

```php
cd /etc/yum.repos.d/
cp -a CentOS-Base.repo CentOS-Base.repo.bak
wget -O CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-6.repo
yum clean all
yum makecache
```

## 二、安装mysql5.6.17

1、按照标准需要给mysql创建所属用户和用户组

```php
#创建群组
groupadd mysql
#创建一个用户，不允许登陆和不创主目录
useradd -s /sbin/nologin -g mysql -M mysql
#检查创建用户
tail -1 /etc/passwd
#centos最小化安装后，会有mysql的库因此先卸载！

#检查安装与否
rpm -qa|grep mysql
#强制卸载
rpm -e mysql-libs-5.1.73-3.el6_5.x86_64 --nodeps
```

2、MySQL从5.5版本开始，通过`./configure`进行编译配置方式已经被取消，取而代之的是`cmake`工具。 因此，我们首先要在系统中源码编译安装`cmake`工具。

```php
wget http://www.cmake.org/files/v2.8/cmake-2.8.12.2.tar.gz
#注：如果地址失效 wget http://www.cmake.org/files/v2.8/cmake-2.8.12.2.tar.gz --no-check-certificate
tar zxvf cmake-2.8.12.2.tar.gz
cd cmake-2.8.12.2
./configure
make && make install
```

3、使用cmake来编译安装mysql5.6.17

```php
wget http://dev.mysql.com/get/Downloads/MySQL-5.6/mysql-5.6.17.tar.gz
tar zxvf mysql-5.6.17.tar.gz
cd mysql-5.6.17
cmake \
-DCMAKE_INSTALL_PREFIX=/usr/local/mysql \
-DMYSQL_DATADIR=/usr/local/mysql/data \
-DSYSCONFDIR=/etc \
-DWITH_MYISAM_STORAGE_ENGINE=1 \
-DWITH_INNOBASE_STORAGE_ENGINE=1 \
-DWITH_MEMORY_STORAGE_ENGINE=1 \
-DWITH_READLINE=1 \
-DMYSQL_UNIX_ADDR=/var/lib/mysql/mysql.sock \
-DMYSQL_TCP_PORT=3306 \
-DENABLED_LOCAL_INFILE=1 \
-DWITH_PARTITION_STORAGE_ENGINE=1 \
-DEXTRA_CHARSETS=all \
-DDEFAULT_CHARSET=utf8 \
-DDEFAULT_COLLATION=utf8_general_ci \
-DMYSQL_USER=mysql \
-DWITH_DEBUG=0 \
-DWITH_SSL=system
make && make install

#修改/usr/local/mysql权限
chmod +w /usr/local/mysql
chown -R mysql:mysql /usr/local/mysql
```

4、关于my.cnf配置文件：
在启动MySQL服务时，会按照一定次序搜索`my.cnf`，先在/etc目录下找，找不到则会搜索`”$basedir/my.cnf”` 就是安装目录下 `/usr/local/mysql/`my.cnf，这是新版MySQL的配置文件的默认位置！ 注意：在CentOS 6.x版操作系统的最小安装完成后，在/etc目录下会存在一个my.cnf，需要将此文件更名为其他的名字。 如：`/etc/my.cnf.bak`，否则，该文件会干扰源码安装的MySQL的正确配置，造成无法启动。 由于我们已经卸载了最小安装完成后的mysq库所以，就没必要操作了。

```php
#进入support-files目录
cd support-files/
#如果还有my.cnf请备份
mv /etc/my.cnf /etc/my.cnf.bak
#如果愿意也可以复制配置文件到etc下
cp my-default.cnf /etc/my.cnf

#执行初始化配置脚本，创建系统自带的数据库和表，注意配置文件的路径
/usr/local/mysql/scripts/mysql_install_db --defaults-file=/etc/my.cnf --basedir=/usr/local/mysql --datadir=/usr/local/mysql/data --user=mysql

#拷贝mysql安装目录下support-files服务脚本到init.d目录
cp support-files/mysql.server /etc/init.d/mysqld
#赋予权限
chmod +x /etc/init.d/mysqld
#设置开机启动
chkconfig mysqld on
#启动MySQL
service mysqld start
#或者
/etc/init.d/mysql start
```

5、MySQL5.6.x启动成功后，root默认没有密码，我们需要设置root密码。
设置之前，我们需要先设置PATH，要不,不能直接调用mysql

```php
#修改/etc/profile文件
vi /etc/profile
#在文件末尾添加
PATH=/usr/local/mysql/bin:$PATH
export PATH
#让配置立即生效
 
source /etc/profile
#登陆测试，默认是没有密码,直接回车就可进入
 
mysql -uroot -p
 
#设置mysql密码
/usr/local/mysql/bin/mysqladmin -uroot -p password '你的密码'
#登陆进命令行模式
 
mysql -uroot -p
 
#查看用户
select user,host from mysql.user;
 
#删除不必要的用户
drop user ""@localhost;
drop user ""@c65mini.localdomain;
drop user root@c65mini.localdomain;
drop user root@'::1';
 
#赋予账号远程访问的权限
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY '你的密码' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '你的密码' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'c65mini.localdomain' IDENTIFIED BY '你的密码' WITH GRANT OPTION;
#关于删除MySQL的默认root用户参考：http://blog.chinaunix.net/uid-16844903-id-3377690.html
 
#其它一些信息查询： 检查mysql版本
mysql -uroot -p"密码" -e "select version();"
#验证mysql安装路径
ls -ld /usr/local/mysql/
```

## 三、安装PHP7

安装依赖关系

1、libiconv库为需要做转换的应用提供了一个iconv()的函数，以实现一个字符编码到另一个字符编码的转换。 错误提示：`configure: error: Please reinstall the iconv library.`

```php
wget http://ftp.gnu.org/pub/gnu/libiconv/libiconv-1.14.tar.gz
tar zxvf libiconv-1.14.tar.gz
cd libiconv-1.14
./configure --prefix=/usr/local/libiconv
make && make install
cd ..
```

2、libmcrypt是加密算法扩展库。 错误提示：`configure: error: Cannot find imap library (libc-client.a). Please check your c-client installation.`

```php
wget http://iweb.dl.sourceforge.net/project/mcrypt/Libmcrypt/2.5.8/libmcrypt-2.5.8.tar.gz
tar zxvf libmcrypt-2.5.8.tar.gz
cd libmcrypt-2.5.8
./configure
make && make install
cd ..
```

3、Mhash是基于离散数学原理的不可逆向的php加密方式扩展库，其在默认情况下不开启。 mhash的可以用于创建校验数值，消息摘要，消息认证码，以及无需原文的关键信息保存 错误提示：`configure: error: “You need at least libmhash 0.8.15 to compile this program. http://mhash.sf.net/”`

```php
wget http://hivelocity.dl.sourceforge.net/project/mhash/mhash/0.9.9.9/mhash-0.9.9.9.tar.bz2
tar jxvf mhash-0.9.9.9.tar.bz2
cd mhash-0.9.9.9
./configure
make && make install
cd ..
```

4、mcrypt 是 php 里面重要的加密支持扩展库，Mcrypt扩展库可以实现加密解密功能，就是既能将明文加密，也可以密文还原。

```php
wget http://iweb.dl.sourceforge.net/project/mcrypt/MCrypt/2.6.8/mcrypt-2.6.8.tar.gz
tar zxvf mcrypt-2.6.8.tar.gz
cd mcrypt-2.6.8
./configure
make && make install
cd ..
```

编译mcrypt可能会报错：`configure: error: *** libmcrypt was not found`

```php
vi  /etc/ld.so.conf
#最后一行添加
/usr/local/lib/
#载入
ldconfig
```

编译mcrypt可能会报错：`/bin/rm: cannot remove `libtoolT': No such file or directory`
修改 configure 文件，把RM='$RM'改为RM='$RM -f' 这里的$RM后面一定有一个空格。 如果后面没有空格，直接连接减号，就依然会报错。

5、正式开始编译php7

```php
wget http://cn2.php.net/distributions/php-7.0.5.tar.gz
tar zxvf php-7.0.5.tar.gz
cd php-7.0.5
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --enable-fpm --with-fpm-user=www --with-fpm-group=www --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-freetype-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-magic-quotes --enable-safe-mode --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --with-curlwrappers --enable-mbregex --enable-mbstring --with-mcrypt --enable-ftp --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-gettext --disable-fileinfo --enable-maintainer-zts
make && make install
```

修改fpm配置`php-fpm.conf.default`文件名称

```php
mv /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf
#注意：发现启动时错误，配置文件无法找到，需要mv /usr/local/php/etc/php-fpm.d/www.conf.default /usr/local/php/etc/php-fpm.d/default.conf

#复制php.ini配置文件
cp php.ini-production /usr/local/php/etc/php.ini

#复制php-fpm启动脚本到init.d
cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
#赋予执行权限
chmod +x /etc/init.d/php-fpm
#添加为启动项
#chkconfig --add php-fpm
#设置开机启动
chkconfig php-fpm on

#按照标准，给php-fpm创建一个指定的用户和组
#创建群组
groupadd www
#创建一个用户，不允许登陆和不创主目录
useradd -s /sbin/nologin -g www -M www
#立即启动php-fpm
service php-fpm start
#或者
/etc/init.d/php-fpm start
```

## 四、安装nginx1.7

nginx所需的依赖关系，一般我们都需要先装pcre, zlib，前者为了重写rewrite，后者为了gzip压缩。如果系统已经yum 安装了这些库也没关系，无需卸载。直接编译安装最新的就可以了。为了一次性完成编译，先准备编译下面的依赖关系！

1、安装PCRE库

```php
wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.35.tar.gz
注：如果地址失效，可以使用http://ufpr.dl.sourceforge.net/project/pcre/pcre/8.38/pcre-8.38.zip
tar -zxvf pcre-8.35.tar.gz
cd pcre-8.35
./configure
make && make install
```

2、安装zlib库

```php
wget http://zlib.net/zlib-1.2.8.tar.gz
tar -zxvf zlib-1.2.8.tar.gz
cd zlib-1.2.8
./configure
make && make install
```

3、安装nginx

```php
wget http://nginx.org/download/nginx-1.7.0.tar.gz
tar zxvf nginx-1.7.0.tar.gz
cd nginx-1.7.0
./configure \
--user=www \
--group=www \
--prefix=/usr/local/nginx \

make && make install
cd ..
```

4、启动nginx测试

```php
/usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf
```

5、启动脚本

```php
#!/bin/sh
#
# nginx - this script starts and stops the nginx daemon
#
# chkconfig:   - 85 15
# description:  Nginx is an HTTP(S) server, HTTP(S) reverse \
#               proxy and IMAP/POP3 proxy server
# processname: nginx
# config:      /etc/nginx/nginx.conf
# config:      /etc/sysconfig/nginx
# pidfile:     /var/run/nginx.pid
 
# Source function library.
. /etc/rc.d/init.d/functions
 
# Source networking configuration.
. /etc/sysconfig/network
 
# Check that networking is up.
[ "$NETWORKING" = "no" ] && exit 0
 
nginx="/usr/local/nginx/sbin/nginx"
prog=$(basename $nginx)
 
sysconfig="/etc/sysconfig/$prog"
lockfile="/var/lock/subsys/nginx"
pidfile="/usr/local/nginx/logs/nginx.pid"
 
NGINX_CONF_FILE="/usr/local/nginx/conf/nginx.conf"
 
[ -f $sysconfig ] && . $sysconfig
 
start() {
    [ -x $nginx ] || exit 5
    [ -f $NGINX_CONF_FILE ] || exit 6
    echo -n $"Starting $prog: "
    daemon $nginx -c $NGINX_CONF_FILE
    retval=$?
    echo
    [ $retval -eq 0 ] && touch $lockfile
    return $retval
}
 
stop() {
    echo -n $"Stopping $prog: "
    killproc -p $pidfile $prog
    retval=$?
    echo
    [ $retval -eq 0 ] && rm -f $lockfile
    return $retval
}
 
restart() {
    configtest_q || return 6
    stop
    start
}
 
reload() {
    configtest_q || return 6
    echo -n $"Reloading $prog: "
    killproc -p $pidfile $prog -HUP
    echo
}
 
configtest() {
    $nginx -t -c $NGINX_CONF_FILE
}
 
configtest_q() {
    $nginx -t -q -c $NGINX_CONF_FILE
}
 
rh_status() {
    status $prog
}
 
rh_status_q() {
    rh_status >/dev/null 2>&1
}
 
# Upgrade the binary with no downtime.
upgrade() {
    local oldbin_pidfile="${pidfile}.oldbin"
 
    configtest_q || return 6
    echo -n $"Upgrading $prog: "
    killproc -p $pidfile $prog -USR2
    retval=$?
    sleep 1
    if [[ -f ${oldbin_pidfile} && -f ${pidfile} ]];  then
        killproc -p $oldbin_pidfile $prog -QUIT
        success $"$prog online upgrade"
        echo
        return 0
    else
        failure $"$prog online upgrade"
        echo
        return 1
    fi
}
 
# Tell nginx to reopen logs
reopen_logs() {
    configtest_q || return 6
    echo -n $"Reopening $prog logs: "
    killproc -p $pidfile $prog -USR1
    retval=$?
    echo
    return $retval
}
 
case "$1" in
    start)
        rh_status_q && exit 0
        $1
        ;;
    stop)
        rh_status_q || exit 0
        $1
        ;;
    restart|configtest|reopen_logs)
        $1
        ;;
    force-reload|upgrade)
        rh_status_q || exit 7
        upgrade
        ;;
    reload)
        rh_status_q || exit 7
        $1
        ;;
    status|status_q)
        rh_$1
        ;;
    condrestart|try-restart)
        rh_status_q || exit 7
        restart
        ;;
    *)
        echo $"Usage: $0 {start|stop|reload|configtest|status|force-reload|upgrade|restart|reopen_logs}"
        exit 2
esac
```

启动测试

```php
#注意权限chmod +x /etc/init.d/nginx
#启动测试
/etc/init.d/nginx restart
#或者
service nginx restart
#设置开机启动
chkconfig nginx on
#访问测试，暂时关闭防火墙
/etc/init.d/iptables stop
#访问成功后，开启防火墙，过滤80端口
#配置80,3306端口访问
/sbin/iptables -I INPUT -p tcp --dport 80 -j ACCEPT
/sbin/iptables -I INPUT -p tcp --dport 3306 -j ACCEPT
/etc/rc.d/init.d/iptables save
/etc/init.d/iptables restart
```

6、无法解析php文件
在`/usr/local/nginx/html/` 建立`phpinfo.php`文件，访问发现php文件无法解析
修改nginx.conf文件
加入

```php
location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
}
```

测试,访问成功[![php7](/images/2016/04/php7.png)](/images/2016/04/php7.png)

7、经过优化的nginx.conf文件

```php
user  www www;
 
worker_processes 1;
 
error_log  /home/wwwlogs/nginx_error.log  crit;
 
pid        /usr/local/nginx/logs/nginx.pid;
 
google_perftools_profiles /tmp/tcmalloc;
 
#Specifies the value for maximum file descriptors that can be opened by this process.
worker_rlimit_nofile 51200;
 
events
    {
        use epoll;
        worker_connections 51200;
    }
 
http
    {
        include       mime.types;
        default_type  application/octet-stream;
 
        server_names_hash_bucket_size 128;
        client_header_buffer_size 32k;
        large_client_header_buffers 4 32k;
        client_max_body_size 50m;
 
        sendfile on;
        tcp_nopush     on;
 
        keepalive_timeout 60;
 
        tcp_nodelay on;
 
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
        fastcgi_buffer_size 64k;
        fastcgi_buffers 4 64k;
        fastcgi_busy_buffers_size 128k;
        fastcgi_temp_file_write_size 256k;
 
        gzip on;
        gzip_min_length  1k;
        gzip_buffers     4 16k;
        gzip_http_version 1.0;
        gzip_comp_level 2;
        gzip_types       text/plain application/x-javascript text/css application/xml;
        gzip_vary on;
        gzip_proxied        expired no-cache no-store private auth;
        gzip_disable        "MSIE [1-6]\.";
 
        #limit_zone  crawler  $binary_remote_addr  10m;
 
        server_tokens off;
        #log format
        log_format  access  '$remote_addr - $remote_user [$time_local] "$request" '
             '$status $body_bytes_sent "$http_referer" '
             '"$http_user_agent" $http_x_forwarded_for';
 
server
    {
        listen       80;
        server_name www.cnhzz.com;
        index index.html index.htm index.php;
        root  /home/wwwroot/htdocs;
 
            location ~ \.php$ {
                    fastcgi_pass   127.0.0.1:9000;
                    fastcgi_index  index.php;
                    fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    include        fastcgi_params;
            }
 
        location /status {
            stub_status on;
            access_log   off;
        }
 
        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
            {
                expires      30d;
            }
 
        location ~ .*\.(js|css)?$
            {
                expires      12h;
            }
 
        access_log  /home/wwwlogs/access.log  access;
    }
include vhost/*.conf;
}
```

参考文章：[实践centos6.5编译安装LNMP架构web环境](http://www.centoscn.com/CentosServer/www/2015/0422/5245.html "实践centos6.5编译安装LNMP架构web环境")