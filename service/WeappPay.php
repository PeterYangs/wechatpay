<?php


namespace Service;

use Contracts\PayInterface;

/**
 * 小程序和公众号支付
 * Create By Peter
 * 2019/12/27 09:37:19
 * Email:904801074@qq.com
 * Class WeappPay
 * @package service
 */
class WeappPay extends Base implements PayInterface
{


    function getPayParam($body,$out_trade_no,$total_fee,$ip,$notify_url,$openid='',$attach=''){


        $re=$this->get_unifiedorder($body,$out_trade_no,$total_fee,$ip,$notify_url,$openid,$attach);

        $data=[
            'appId'=>$this->appid,
            'timeStamp'=>time(),
            'nonceStr'=>$this->get_noncestr(),
            'package'=>'prepay_id='.$re['prepay_id'],
            'signType'=>'MD5',

        ];

        $paySign=$this->get_signature_for_pay($data);


        $data['paySign']=$paySign;

        return $data;
    }


    function check()
    {
        return parent::check(); // TODO: Change the autogenerated stub
    }


}


//$w=new WeappPay()