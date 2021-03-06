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


    /**
     * 退款
     * Create by Peter
     * 2019/12/27 17:06:19
     * Email:904801074@qq.com
     * @param $transaction_id string 微信支付流水号
     * @param $out_refund_no string 退款订单号
     * @param $total_fee  string 订单总金额
     * @param $refund_fee string 退款金额
     * @param $notify_url string 退款成功通知地址
     * @return array
     * @throws \Exception
     */
    function refund($transaction_id,$out_refund_no,$total_fee,$refund_fee,$notify_url){

        $url='https://api.mch.weixin.qq.com/secapi/pay/refund';

        $data=[
            'appid'=>$this->appid,
            'mch_id'=>$this->mch_id,
            'nonce_str'=>$this->get_noncestr(),
            'transaction_id'=>$transaction_id,
            'out_refund_no'=>$out_refund_no,
            'total_fee'=>$total_fee,
            'refund_fee'=>$refund_fee,
            'notify_url'=>$notify_url

        ];

        $sign=$this->get_signature_for_pay($data);

        $data['sign']=$sign;



        $xml=$this->arrayToXml($data);

        $re=$this->postXmlCurl($xml,$url,true);


        return $this->xmlToArray($re);

    }


    /**
     * 退款通知检查
     * Create by Peter
     * 2019/12/30 10:13:27
     * Email:904801074@qq.com
     * @param null $data
     * @return mixed
     */
    function check_refund($data=null){

        if(!$data)  $data=$this->getData();

        $array=$this->xmlToArray($data);

        $req_info=$array['req_info'];

        $req_info=base64_decode($req_info,true);

        $md5_key=md5($this->key);

         $res=$this->xmlToArray(openssl_decrypt($req_info , 'aes-256-ecb', $md5_key, OPENSSL_RAW_DATA));

         if(is_array($res)) return $res;

         return false;

    }


    function return_success()
    {
        return parent::return_success(); // TODO: Change the autogenerated stub
    }


}