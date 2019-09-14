---
title: Kuaidi100查询快递信息
tags:
  - Kuaidi100
id: 728
categories:
  - php
date: 2015-05-27 18:33:15
---

自定义 Kuaidi100.Com 快递查询函数

1、应用场景
（1）电商网站用户打开“我的订单”时调用此API显示结果
（2）物流系统对帐前调用此API查一次所有运单的签收状态

2、请求地址
`http://api.kuaidi100.com/api?id=[]&com=[]&nu=[]&valicode=[]&show=[0|1|2|3]&muti=[0|1]&order=[desc|asc]`

3.参考文档 http://www.kuaidi100.com/openapi/api_post.shtml

```php
/**
 * 通过订单号，获取物流信息数组
 * @param string $no 运单号
 * @param string $com 货运公司代码，如yunda
 * @return array 二维数组 array(array('time'=>'', 'context'=>''))
 */
function getOrderExpress($no, $com){
 
    $com = strtolower($com);
     
    $cont = getKD100ExprInfo($com, $no, '0', $useApi = false);
    $res = json_decode($cont);
    if ($res->status == '200' || $res->status == '1') {
        if (!empty($res->data)) {
            foreach ($res->data as $k => $v) {
                $expressArr[] = array('time' => $v->time, 'context' => $v->context);
            }
        }
    }else{
        $expressArr[] = array();
    }
     
    return $expressArr;
}
 
/**
 * 通过curl 请求http://api.kuaidi100.com/api 获取快递信息
 * @param string $cmpcode 快递公司代码
 * @param string $exprno 快递单号
 * @param string $show 返回数据类型 返回类型： 0：json，1：xml对象，2：html对象，3：text文本
 * @param bool $useApi 是否使用api
 */
function getKD100ExprInfo($cmpcode, $exprno, $show = 0, $useApi = false) {
    $AppKey = '*****';
    $url = ($useApi) ? 'http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$cmpcode.'&nu='.$exprno
    .'&show=0&muti=1&order=asc' : "http://www.kuaidi100.com/query?type=$cmpcode&postid=$exprno";
    //$cont = CurlOpen($url, $post = '', $cookie = '', $timeout = 30);
    $cont = CurlOpen($url);
 
    if (!$useApi) {
        $res = json_decode($cont, true);
        $res['data'] = array_reverse($res['data']);
        $cont = json_encode($res);
    }
    return $cont;
}
 
function CurlOpen($url){
    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_HEADER,0);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt ($curl, CURLOPT_TIMEOUT,5);
    $get_content = curl_exec($curl);
    curl_close ($curl);
     
    return $get_content;
}
 
header('content-type:text/html;charset=utf-8');
$res = getOrderExpress('3100380429014 ', 'YUNDA');
var_dump($res);
 
/*
array (size=14)
  0 => 
    array (size=2)
      'time' => string '2015-05-12 17:00:54' (length=19)
      'context' => string '到达：江苏江都市公司小纪分部 已收件' (length=52)
  1 => 
    array (size=2)
      'time' => string '2015-05-12 21:27:14' (length=19)
      'context' => string '到达：江苏江都分拨中心' (length=33)
  2 => 
    array (size=2)
      'time' => string '2015-05-12 21:33:03' (length=19)
      'context' => string '到达：江苏江都分拨中心 发往：广东深圳分拨中心' (length=67)
*/
```