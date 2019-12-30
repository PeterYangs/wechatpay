# wechatpay
微信支付sdk

###  小程序和公众号支付

##### 支付请求

```php
<?php
use wechatPay\WeChatPay;

include '../vendor/autoload.php';

//实例化
$pay = new WeChatPay(
    'xxxx',
    'xxx',
    'xxxxxxxxxxxxx',
    __DIR__.DIRECTORY_SEPARATOR.'apiclient_cert.pem',
    __DIR__.DIRECTORY_SEPARATOR.'apiclient_key.pem'
);

//小程序和公众号支付
$weapp=$pay->getType('weapp');

//获取支付参数
$param=$weapp->getPayParam(
    '支付测试',
    time(),
    1,
    '127.0.0.1',
    'http://www.baidu.com',
    'xxx',
    '123'
);

```

##### 支付通知检查

```php
<?php
//检查失败返回false，成功返回通知参数
$weapp->check();
```

##### 退款申请
```php
<?php
$re=$weapp->refund(
    '1111111',
    time(),
    1,
    1,
    'http://www.baidu.com'
);
```

##### 退款通知检查
```php
<?php
//失败返回false,成功返回退款参数
$data=$weapp->check_refund();
```

##### 成功应答
```php
<?php

echo $weapp->return_success();
```

#### 其他支付完善中。。。。
