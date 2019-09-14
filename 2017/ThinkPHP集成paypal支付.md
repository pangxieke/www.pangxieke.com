---
title: ThinkPHP集成paypal支付
id: 402
categories:
  - php
date: 2015-01-26 23:02:58
tags: paypal
---

在thinkphp中集成paypal支付功能，源码如下
```php
<?php
class paypalAction extends baseAction {  
     
    /** 
     * 自己的paypal账号     
     */
    private $account = 'pay@real.com ';//真实商家账号
    //开发人员注册的sandbox 商家账号，用于测试 
    private $account_test = 'pay@real.com';   
    /** 
     * paypal支付网关地址 
     */ 
    private $gateway = 'https://www.paypal.com/cgi-bin/webscr';
 
    private $sandbox_gateway = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    private $debug = false; //调试开关 
     
    private $host = 'http://www.pangxieke.com'; //线上网址
    private $host_test = 'http://localhost.com';//本地测试网址，本地测试无法接受paypal返回的notify验证信息，需要公网
     
    private $host194 = 'http://www.test.com'; //notify验证,如网站使用多台服务器，可以自主自定某一台服务接受notify验证信息，方便自己处理
     
    /**
     * 币种兑换美元汇率
     */
    private $change_to_usd = array(
        'RMB' => 6.2281,  
    );    
   
    /**
     * 构造函数，主要用于调试 切换调试模式下的信息
     */
    function __construct() {
        parent::__construct();
        if($this->debug){
            $this->gateway = $this->sandbox_gateway;
            $this->host = $this->host_test;
            $this->account = $this->account_test;
        }
    }
     
    /**
     *  调试信息访问接口
     */
    public function getPayLog(){
         
        //echo PayLog_r();
         
    }
     
    /** 
     * 生成订单并跳转到Paypal进行支付 
     * 需要post传递 $_POST['order_no'] 订单号
     */ 
    public function dopay() {
        PayLog($_REQUEST);
        /** 
         * 自己的逻辑代码 
         * 判断是否登录、购买的哪个商品、购物车等等逻辑 
         * 当然可以调用Model更简单点 
         * 这里不在赘述 
         */
   
        /** 
         * 订单包含哪几种商品、谁买的、什么时间、几件 
         */
        $out_trade_no = $_POST['order_no'];     //订单号，
         
        //此为获取订单信息模块，使用时可以自主替换
        $info=$this->getOrderInfo($out_trade_no);
        $ordid = $this->getOrderid($out_trade_no);
        $list=$this->getDetailInfo($out_trade_no);
        $subjectStr = $list[0]['prodname']; 
        if (count($list)>1) {
            $subjectStr .="等";
        }
        //订单名称
        $subject = $subjectStr;
         
        //付款金额
        $total_fee = $info['total'];
         
        //订单描述，在www.paypal.com显示用
        $body = subString($subject, 0, 50);
   
        if( $info ) {  
            $pp_info = array();// 初始化准备提交到Paypal的数据  
            $pp_info['cmd'] = '_xclick';// 告诉Paypal，我的网站是用的我自己的购物车系统  
            $pp_info['business'] = $this->account;// 告诉paypal，我的（商城的商户）Paypal账号，就是这钱是付给谁的  
            $pp_info['item_name'] = "$body";// 用户将会在Paypal的支付页面看到购买的是什么东西，只做显示，没有什么特殊用途，如果是多件商品，则直接告诉用户，只支付某个订单就可以了  
            $pp_info['amount'] = round($total_fee/$this->change_to_usd['RMB'], 2); //告诉Paypal，我要收多少钱 ,四舍五入小数点取2位 ，paypal不支持RMB，故若网站购物车使用人民币需要转换
            $pp_info['currency_code'] = 'USD';// RMB 告诉Paypal，我要用什么货币。这里需要注意的是，由于汇率问题，如果网站提供了更改货币的功能，那么上面的amount也要做适当更改，paypal是不会智能的根据汇率更改总额的  
            $pp_info['return'] = $this->host . '/index.php?m=message&a=orderdetail&ordid=' . $ordid;// 当用户成功付款后paypal会将用户自动引导到此页面。如果为空或不传递该参数，则不会跳转
            $pp_info['invoice'] = $out_trade_no;  //订单编号
            $pp_info['charset'] = 'utf-8';  
            $pp_info['no_shipping'] = '1';  
            $pp_info['no_note'] = '1';
            //$pp_info['cs'] = '1';   //背景颜色
            $pp_info['image_url'] = $this->host . '/static/2/images/paypal_wgc.png';   //paypal.com页左上角logo 150*50
            $pp_info['cancel_return'] = $this->host . '/index.php?m=user&a=order';// 当跳转到paypal付款页面时，用户又突然不想买了。则会跳转到此页面  
            $pp_info['notify_url'] = $this->host194 . '/index.php?g=home&m=paypal&a=notify&orderid='.$out_trade_no;// Paypal会将指定 invoice 的订单的状态定时发送到此URL(Paypal的此操作，是paypal的服务器和我方商城的服务器点对点的通信，用户感觉不到）  
            $pp_info['rm'] = '2';   //返回参数的方法，2为post
             
            //$paypal_paypal_url = $this->gateway . '?' . http_build_query($pp_info);
            //header("Location:$paypal_paypal_url");exit;
             
            //为支付中转页，可以替换。主要是提交form表单，将$pp_info的信息提交到paypal
            $this->showheader();
            //建立form表单信息
            $html_text = $this->buildRequestForm($pp_info, "get", "确认");
            echo $html_text;
            $this->showfooter();
            unset($pp_info);  
            exit;
        } else {  
             
            $this->redirect("message/orderdetail", array("ordid" => $out_trade_no)); //跳转到配置项中配置的支付失败页面；
        }  
         
    }  
     
     
    /**
     *  paypal会将支付成功信息返回
     * paypal返回的信息必须再次发送到paypal验证，以防用户传递伪造的信息
     * 由于这个文件只有为Paypal的服务器访问，所以无需考虑做什么页面什么的，这个页面不是给人看的，是给机器看的
     * paypal返回的信息大概如下
    'mc_gross'=>'11.50','invoice'=>'313154828246','protection_eligibility'=>'Ineligible',
        'payer_id'=>'5B2S5PL9U8254','tax'=>'0.00','payment_date'=>'19:22:56 Dec 28, 2014 PST',
 'payment_status'=>'Completed','charset'=>'gb2312','first_name'=>'Test','mc_fee'=>'0.69',
'notify_version'=>'3.8','custom'=>'','payer_status'=>'verified','business'=>'收款账号','quantity'=>'1',
'verify_sign'=>'AiPC9BjkCyDFQXbSkoZcgqH3hpacA2T9xJs1DO9xVqa7F20fI2ZPref4','payer_email'=>'支付人账号','txn_id'=>'56141695RA928061F','payment_type'=>'instant','last_name'=>'Buyer','receiver_email'=>'收款账号','payment_fee'=>'0.69','receiver_id'=>'KTSYXQSRX9ZLG',        'txn_type'=>'web_accept','item_name'=>'','mc_currency'=>'USD','item_number'=>'','residence_country'=>'CN','test_ipn'=>'1','handling_amount'=>'0.00','transaction_subject'=>'','payment_gross'=>'11.50','shipping'=>'0.00','ipn_track_id'=>'88e59d891bd1e','orderid'=>'313154828246'
     *  具体参数描述
        mc_gross    交易收入
        address_status  地址信息状态
        paypal_address_id   Paypal地址信息ID
        payer_id    付款人的Paypal ID
        tax 税收
        address_street  通信地址
        payment_date    交易时间
        payment_status  交易状态
        charset 语言编码
        address_zip 邮编
        first_name  付款人姓氏
        address_country_code    国别
        address_name    收件人姓名
        custom  自定义值
        payer_status    付款人账户状态
        business    收款人Paypal账户
        address_country 通信地址国家
        address_city    通信地址城市
        quantity    货物数量
        payer_email 付款人email
        txn_id  交易ID
        payment_type    交易类型
        last_name   付款人名
        address_state   通信地址省份
        receiver_email  收款人email
        address_owner   尚未公布/正式启用
        receiver_id 收款人ID
        ebay_address_id 易趣用户地址ID
        txn_type    交易通告方式
        item_name   货品名称
        mc_currency 货币种类
        item_number 货品编号
        payment_gross   交易总额[只适用于美元情况]
        shipping    运送费
     */
    public function notify() {
         
        PayLog($_REQUEST);  //记录支付信息到文件，主要用于调试时不写数据库
         
         
        $order_id = (int) $_REQUEST['orderid']; //orderid此参数是自己设定  为$pp_info['notify_url']传递过去而传回来的
         
        // $order_info = $this->getOrderInfo($order_id);  可以加入逻辑判断，判断是否是真实访问
        //判断订单是否存在
        // if(empty($order_id) || !$order_info){
            // exit;
        // }
         
        // 由于该URL不仅仅只有Paypal的服务器能访问，其他任何服务器都可以向该方法发起请求。所以要判断请求发起的合法性，也就是要判断请求是否是paypal官方服务器发起的
         
        // 如果是paypal服务器发出的信息
             
         
        $trade_no = $_REQUEST['txn_id'];                //交易号
        $trade_status = $_REQUEST['payment_status'];    //交易状态
        $total_fee = $order_info['total'];             //交易金额
        $notify_id = $_REQUEST['ipn_track_id'];          //
        $notify_time = date('Y-m-d H:i:s', strtotime("$_REQUEST[payment_date]"));       //通知的发送时间。时间格式为 19:05:05 Dec 29, 2014 PST，转换为本地时间
        $buyer_email = $_REQUEST['payer_email'];        //买家帐号
        $pay_type = 'paypal';                           //用数据库记录交易模式，非必要
        $mc_currency = $_REQUEST['mc_currency'];        //交易币种
     
        //此数据是用于写入数据信息，健值为数据库字段，使用时修改为自己的数据库字段
        $parameter = array(
            "out_trade_no" => $order_id, //订单编号；
            "trade_no" => $trade_no, //paypal交易号；
            "total_fee" => $total_fee, //交易金额；
            "trade_status" => $trade_status, //交易状态
            "notify_id" => $notify_id, //通知校验ID。
            "notify_time" => $notify_time, //通知的发送时间。
            "buyer_email" => $buyer_email, //买家支付宝帐号
            "pay_type" => $pay_type,
        );
         
        //检查订单是否是创建了，而且未支付。如为支付，写入支付信息。因为支付信息还未验证是否是paypal返回的合法信息，订单状态update为pending。待反向验证成功才能确定是否完成
        $ret = $this->checkorderstatus($order_id);
        if($trade_status == 'Completed' || $trade_status == 'Pending'){
            if (!$ret) {
                PayLog($parameter);
                $this->orderhandle($parameter);  //进行订单处理，写入数据库，状态pending
            }
        }
  
        // 拼凑 post 请求数据 ，将接受的信息在通过curl发送到paypal。如果paypal返回VERIFIED，代表此信息是paypal发送的
        $req = 'cmd=_notify-validate';// 验证请求  
        foreach ($_POST as $k=>$v)  
        {  
            $v = urlencode(stripslashes($v));  
            $req .= "&{$k}={$v}";  
        }  
   
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$this->gateway);  
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
        curl_setopt($ch,CURLOPT_POST,1);  
        curl_setopt($ch,CURLOPT_POSTFIELDS,$req);  
        $res = curl_exec($ch);  
        curl_close($ch);  
         
        PayLog($res);
        if( $res && !empty($order_info) ) {  
            // 本次请求是否由Paypal官方的服务器发出的请求  
            if(strcmp($res, 'VERIFIED') == 0) {  
                /** 
                 * 判断订单的状态 
                 * 判断订单的收款人 
                 * 判断订单金额  币种金额都需要对应
                 * 判断货币类型 
                 */ 
                if(($_POST['payment_status'] != 'Completed' && $_POST['payment_status'] != 'Pending') 
OR ($_POST['receiver_email'] != $this->account) 
OR ($_POST['mc_gross'] != round($order_info['total']/$this->change_to_usd['RMB'], 2)) 
OR ('USD' != $_POST['mc_currency'])) {  
                    // 如果有任意一项成立，则终止执行。由于是给机器看的，所以不用考虑什么页面。直接输出即可  
                     
                    // PayLog(array( //调试用
                        // 'verify_result' => 'error',
                        // 'webpaypal' => 'callback',
                        // 'order_id'  => $order_id,
                        // 'paypal_status' => boolval($_POST['payment_status'] != 'Completed' && $_POST['payment_status'] != 'Pending'),
                        // 'receiver_email' => boolval($_POST['receiver_email'] != $this->account),
                        // 'mc_gross' => boolval($_POST['mc_gross'] != round($order_info['total']/$this->change_to_usd['RMB'], 2)),
                        // 'mc_currency' => boolval('USD' != $_POST['mc_currency']),
       
                    // ));
                    exit('fail');  
                } else if($_POST['payment_status'] == 'Completed'){// 如果验证通过，则证明本次请求是合法的   
                    //检查交易号是否重复$_REQUEST['txn_id'];
                    PayLog("webpaypal:callback:$order_id:verify_result:ok:payment_total:" . $_POST['mc_gross']);
                    //支付成功，更新订单状态
                    M('order')->where('order_no=' . $order_id)->save(array("state" => "PAID", 'spaytime' => time(), 'spay_msg' => '', 'pay_type' => 'paypal', 'payment_total'=>$_POST['mc_gross'], 'payment_currency'=>'USD'));
                    exit('success');  
                }  
            } else {  
                PayLog("webpaypal:callback:$order_id:verify_result:fail");
                exit('fail');  
            }  
        }  
    }
     
    //检测订单是否已经创建，且未支付
    function checkorderstatus($ordersn) {
        $state = M('order')->where("order_no='$ordersn'")->getField('state');
        if ($state == "CREATED") {
            return false;
        } else {
            return true;
        }
    }
     
    //notify时，记录订单为PAYING
    function orderhandle($parameter) {
        $ordersn = $parameter['out_trade_no'];
        $data['trade_no'] = $parameter['trade_no'];
        $data['rade_status'] = $parameter['trade_status'];
        $data['notify_id'] = $parameter['notify_id'];
        $data['notify_time'] = $parameter['notify_time'];
        $data['buyer_email'] = $parameter['buyer_email'];
        $data['payment'] = $parameter['total_fee'];
        $data['paytime'] = time();
        $data['pay_type'] = $parameter['pay_type'];
        $data['state'] = "PAYING";
        M('order')->where('order_no=' . $ordersn)->save($data);
    }
     
    //订单信息查询
    function getOrderInfo($ordersn) {
        return M("order")->where(array("order_no" => $ordersn))->find();
    }
     
    //订单详细信息查询
    function getDetailInfo($ordersn) {
        return M("order_detail")->alias('a')->join(C('DB_PREFIX') . 'itme as b ON a.itemid=b.id')->field('a.*,b.prodname')->where(array('a.order_no' => $ordersn))->select();
    }
        
    function showheader() {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>paypal交易接口</title></head><body>';
    }
 
    function showfooter() {
        echo'</body></html>';
    }
     
     
    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name) {
        //待请求参数数组
        //$para = $this->buildRequestPara($para_temp);
        $para = $para_temp;
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."' method='".$method."'>";
        while (list ($key, $val) = each ($para)) {
         $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";      
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";        
        return $sHtml;
    }
     
     
    function getOrderid($ordersn) {
        return M("order")->where(array("order_no" => $ordersn))->getField("id");
    }
         
}
 
 
define ('LOG_PATH', 'log');
 
function PayLog($msg) {
   $t = "[" . date("Ymd:His") . "]" . array2str($msg);
   $logfile = "pay_temp.log";
   file_put_contents($logfile, $t . "\n\r", FILE_APPEND);
}
 
function PayLog_r(){
   $logfile =  "pay_temp.log";
   return file_get_contents($logfile);
}
 
function array2str($tmparr) {
    if (!is_array($tmparr))
        return $tmparr;
    $retstr = "";
    foreach ($tmparr as $key => $val) {
 
        if (is_array($val)) {
            $tmp = array2str($val);
            $retstr.="'$key'=>array($tmp),";
        } else {
            $retstr.="'$key'=>'" . addslashes($val) . "',";
        }
    }
    return "array($retstr)";
}
```