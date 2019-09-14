---
title: 欧飞充值php实例
id: 778
categories:
  - php
date: 2015-09-08 22:44:00
tags:
---

欧飞充值是手机短信和流量供应商，公司开发了手机短信和流量重装功能，使用了该公司Api接口，示例如下

### deomo
```php
class ofcard_service {
  
    var $gateway ;           //手机直充网关地址
    var $param ;             //查询参数
 
    /**
    * 从配置文件及入口文件中初始化变量
    * @param string $cardnum 话费面值
    * @param string $order_id 订单号
    * @param string $game_userid 手机号
    * @param string $userid SP编码
    * @param string $userpws SP接入密码
    * @param string $version 欧飞接口版本（固定值为：4.0）
    * @todo 配置文件数组化
    */
    function ofcard_service($cardnum ,$order_id ,$game_userid ,$userid ,$userpws ,$version){
 
        $this ->gateway = 'http://esales1.ofcard.com:8088/onlineorder.do ' ;
 
        $this ->param = array ();
        $this ->param[ 'userid' ] = $userid ;
        $this ->param[ 'userpws' ] = md5( $userpws );
        //$this->param['cardid'] = '140101';
        $this ->param[ 'cardid' ] = $this ->get_cardid( $game_userid );
        $this ->param[ 'cardnum' ] = $cardnum /50;
        $this ->param[ 'sporder_id' ] = $order_id ;
        $this ->param[ 'sporder_time' ] = date ( 'YmdHis' );
        $this ->param[ 'game_userid' ] = $game_userid ;
        $keystr = 'OFCARD' ;
        $this ->param[ 'md5_str' ] = strtoupper ( md5(
             $this ->param[ 'userid' ].
             $this ->param[ 'userpws' ].
             $this ->param[ 'cardid' ].
             $this ->param[ 'cardnum' ].
             $this ->param[ 'sporder_id' ].
             $this ->param[ 'sporder_time' ].
             $game_userid . $keystr ) );
        $this ->param[ 'version' ] = $version ;
    }
 
    /**
    * 进行充值,使用snoopy提交
    * @param Snoopy $snoopy
    * @param ezSQL_mysql $db
    * @param string $order_id
    * @return 充值结果
    * @todo snoopy 集成
    */
    function recharge( $snoopy , $db , $order_id ) {
        $snoopy ->submit( $this ->gateway, $this ->param);
        //下面数据库操作属于商家逻辑
        $sql = "UPDATE `recharge` SET `is_recharge` = "
        . $this->get_xml_value( "game_state" ,$snoopy ->results) . ", `ofcard_trade_id` = '" .
         $this ->get_xml_value( "orderid" , $snoopy ->results) . "', `purchase_price` = '" .
         $this ->get_xml_value( "ordercash" , $snoopy ->results) .
         "' WHERE `order_id` = " . $order_id ;
        $db ->query( $sql );
        return $this ->get_xml_value( "game_state" , $snoopy ->results);
    }
 
    /**
    * xml数据简单解析
    * @param  string $name
    * @param   string $xml
    * @return  string $ret
    */
    function get_xml_value( $name , $xml )
    {
        $ret = '' ;
        preg_match( "|<" . $name . ">(.*)</" . $name . ">|U" , $xml , $ret );
        return $ret [1];
    }
 
    /**
    *
    * 获取充值状态
    * http://202.102.53.141:83/api/query.do?userid=xxxxx&spbillid=spxxxxxx
    * @param string $userid SP编码
    * @param string $spbillid 商户系统订单号
    * @return 充值状态
    */
    function get_is_recharge( $userid , $spbillid ){
        $status_url = "http://202.102.53.141:83/api/query.do?userid= "
        . $userid . "&spbillid=" . $spbillid ;
        return file_get_contents ( $status_url );
    }
 
    /**
    *
    * 获取账户余额
    * @param string $userid
    * @param string $userpws
    * @param string $version
    * @return 账户余额
    */
    function get_leftcredit( $userid , $userpws , $version )
    {
        $url = "http://esales1.ofcard.com:8088/queryuserinfo.do?userid= "
        . $userid . "&userpws=" . md5( $userpws ) . "&version=" . $version ;
        return self::get_xml_value( "ret_leftcredit" , file_get_contents ( $url ));
    }
 
    /**
    * 是否可以充值(未进行余额判断)
    * @param string $phoneno
    * @param string $price
    * @param string $userid
    * @return bool 是否可以充值
    */
    function is_recharge( $phoneno , $price , $userid ) {
        $url = "http://esales1.ofcard.com:8088/telcheck.do?phoneno= "
        . $phoneno . "&price=" . $price . "&userid=" . $userid ;
        $ret = split( '#' , file_get_contents ( $url ));
        return (1 == $ret [0]);
    }
 
    /**
    *
    * 所需提货商品的编码
    * (现全国移动联通快充直充编码为且仅为140101，电信手机编码为且仅为１８)
    * @param string $phoneno 手机号码
    * @todo 常量加入配置文件
    */
    function get_cardid( $phoneno ) {
        $num = substr ( $phoneno , 0, 3);
        $ChinaMobile = array(134,135,147,147,136,137,138,139,150,151,152,182,157,158,159,187,188);
        $ChinaUnicom = array (130,131,132,155,156,145,185,186);
        $ChinaTelecom = array (133,153,180,189);
 
        if (in_array( $num , $ChinaMobile ) || in_array( $num , $ChinaUnicom )) {
         return '140101' ;
        }
 
        if (in_array( $num , $ChinaTelecom )) {
         return '18' ;
        }
    }
}
 
$ofcard = new ofcard_service( $original_price ,$dingdan ,$mobile ,$userid ,$userpws ,$version );
  
$ofcard ->recharge( $snoopy , $db , $dingdan );
```