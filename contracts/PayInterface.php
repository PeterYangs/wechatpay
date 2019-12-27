<?php


namespace Contracts;


interface PayInterface
{



    function __construct($appid, $mch_id, $key,$cert_path='',$key_path='');


    function getPayParam($body,$out_trade_no,$total_fee,$ip,$notify_url,$openid='',$attach='');

    function check();

}