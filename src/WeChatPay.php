<?php


namespace wechatPay;


use contracts\PayInterface;
use Service\Base;
use Service\WeappPay;

class WeChatPay
{

    protected $appid;

    protected $mch_id;

    protected $key;

    protected $cert_path;

    protected $key_path;



    function __construct($appid, $mch_id, $key, $cert_path = '', $key_path = '')
    {

        $this->appid=$appid;

        $this->mch_id=$mch_id;

        $this->key=$key;

        $this->cert_path=$cert_path;

        $this->key_path=$key_path;

    }


    /**
     * Create by Peter
     * 2019/12/27 09:51:54
     * Email:904801074@qq.com
     * @param $type string  支付方式
     * @return PayInterface
     */
    function getType($type){


        $pay=null;

        switch ($type){

            case 'weapp':

                $pay=new WeappPay($this->appid,$this->mch_id,$this->key,$this->cert_path,$this->key_path);

                break;

            case 'native':


                break;

        }

        return  $pay;


    }

}