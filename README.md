# wechatpay
微信支付sdk

简单demo:<br/>
$p=new \wechatPay\Pay('appid','商户id','商户秘钥');<br/>

调用扫码支付<br/>
$p->for_NATIVE(time(),'www.baidu.com','127.0.0.1');<br/>

支付回调签名检查<br/>
$re=$pay->check();<br/>
