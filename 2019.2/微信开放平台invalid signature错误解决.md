---
title: 微信开放平台invalid signature错误解决
category: php
id: wechat-error-invalid-signature
date: 2019-2-26 20:30:00
---

使用的是微信开放平台，第三方授权，通过第三方获取js_api_ticket, 然后sign后返回给前端页面，但前端页面一直爆config:invalid signature这个错误

## 错误排查
### 使用微信签名校验工具 
`https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=jsapisign`
校验后是正常的

### 排查access_token
参考https://www.jianshu.com/p/da1fddf1a0f2
排查access_token,是使用的公众号的authorizer_access_token，非第三方平台的component_access_token

## 最终错误，获取ticket接口错误
后参考https://segmentfault.com/q/1010000002520634
**注意jsapi_ticket的生成，别调到卡券ticket的生成接口了，type要传"jsapi"**
```
  $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=wx_card", $accessToken);
  ```
  发现`type`参数果然错误，应该使用`jsapi`
 ```
 $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $accessToken);
 ```

