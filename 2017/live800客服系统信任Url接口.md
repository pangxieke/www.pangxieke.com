---
title: live800客服系统信任Url接口
id: 1071
categories:
  - php
date: 2016-10-09 18:36:54
tags: 在线客服
---

[![live800](/images/2016/10/live800.png)](/images/2016/10/live800.png)

## 应用场景

现在网站都有客服系统，客服与user沟通时，掌握user的信息越多越方便沟通。这样就需要客服系统能够与网站的user系统对接，这样能够不用user告诉客服，就能够知道用户的order，account等相关信息。
此时系统需要api接口与web端后台对接。

测试了live800客服系统，由于没有官方Api接口，自己手写一个，特记录一下，方便后人。

## 基本逻辑

- 在调用live800的js后面加入info信息，将userid和username等信息传递live800
- 在live800客服端设置信任url和key值
- live800客户端请求信任url（/live-user），在客户端中加载此页面显示。
- live-user页面对应控制器api/live/getUserInfo，在控制器中，需要验证用户key，防止信息泄露

## 代码如下

```php
//controller
/**
 * Class ControllerApiLive
 * live800接口
 */
class ControllerLive800 extends Controller
{
    private $liveHostUrl = 'http://v2.live800.com/live800/chatClient/floatButton.js?';
 
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('live/client');
    }
 
    /**
     * 获取live800 url ，在网站上嵌入js中的url
     *
     * @return string
     * @author:tlh
     */
    public function getLive800Script()
    {
        $url = $this->liveHostUrl;
        $str = $this->getInfoValueStr();
        if(!empty($str)){
            $url .= '&info=' . $str;
        }
        return $url;
    }
     
    /**
     * live800获取用户信息
     *
     * @author:tlh
     * @date 2016.9.28
     */
    public function getUserInfo()
    {
        $input = [
            'userId'    => isset($this->request->get['userId']) ? $this->request->get['userId'] : '',
            'timestamp' => isset($this->request->get['timestamp']) ? $this->request->get['timestamp'] : '',
            'hashCode'  => isset($this->request->get['hashCode']) ? $this->request->get['hashCode'] : '',
        ];
        $obj = $this->model_live_client;
        foreach($input as $key=>$val){
            $obj->setParameter($key, $val);
        }
        //验证签名
        if(empty($obj->checkSign())){
            return false;
        }
        //TODO 验证正确，自己的逻辑
         
    }
     
     /**
     * @return string
     */
    public function getInfoValueStr()
    {
        $isLogged = $this->customer->isLogged();
        if(!$isLogged){
            return '';
        }
        $data = [
            'userId'    => 'userid',
            'name'    => 'username',
            'memo'    => 'test',
            'timestamp' => time(),
        ];
 
        $obj = $this->model_api_live_client;
        foreach($data as $key=>$val){
            $obj->setParameter($key, $val);
        }
 
        return $obj ->getInfoValueStr();
    }
}

```

```php
//model
class ModelLiveClient extends Model
{
    private $key = 'you key';
    private $parameters = [];
 
    /**
     *获取密钥
     */
    function getKey()
    {
        return $this->key;
    }
 
    /**
     * 验证签名
     * true:是
     * false:否
     */
    function checkSign() {
        $signPars = "";
        foreach($this->parameters as $k => $v) {
            if("hashCode" != $k && "" != $v) {
                $signPars .= $v;
            }
        }
        $signPars .= $this->getKey();
         
        $sign = md5(($signPars));
        $tenpaySign = $this->getParameter("hashCode");
        return $sign == $tenpaySign;
    }
 
    /**
     *  作用：格式化参数，签名过程需要使用
     */
    function formatStr($paraMap, $urlencode)
    {
        $buff = "";
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            $buff .= $v;
        }
        return $buff;
    }
 
    /**
     *  作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap)
    {
        $buff = "";
        foreach ($paraMap as $k => $v)
        {
            $buff .= $k . "=" . $v. '&';
        }
        if (strlen($buff) > 0)
        {
            $buff = substr($buff, 0, strlen($buff)-1);
        }
        return $buff;
    }
 
    public function getInfoValueStr()
    {
        $str = $this->formatBizQueryParaMap($this->parameters);
        $str .= '&hashCode=' . $this->getSign($this->parameters);
        return urlencode($str);
    }
 
    /**
     *  作用：生成签名
     */
    public function getSign($Obj)
    {
        $Parameters = [];
        foreach ($Obj as $k => $v)
        {
            if("hashCode" == $k){
                continue;
            }
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        $string = $this->formatStr($Parameters, false);
 
        //签名步骤二：在string后加入KEY
        $string = $string . $this->getKey();
        //签名步骤三：MD5加密
        $string = md5(urlencode($string));
        //签名步骤四：所有字符转为大写
        return $string;
    }
 
    /**
     *  作用：设置请求参数
     */
    function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }
 
    /**
     *获取参数值
     */
    function getParameter($parameter) {
        return isset($this->parameters[$parameter])?$this->parameters[$parameter] : '';
    }
 
    function trimString($value)
    {
        $ret = null;
        if (null != $value)
        {
            $ret = $value;
            if (strlen($ret) == 0)
            {
                $ret = null;
            }
        }
        return $ret;
    }
}
```