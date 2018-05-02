<?php
require '../vendor/autoload.php';

$p=new \wechatPay\Pay('wxe27221362f5c82d2','1419420702','TmCYxrZjr910YSPywLhMCeRnRGYPsm9x');

$re=$p->for_NATIVE(time(),'127.0.0.1');

print_r($re);

