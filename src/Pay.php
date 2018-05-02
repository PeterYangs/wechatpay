<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/2 0002
 * Time: 13:47
 */

namespace wechatPay;

class Pay
{

    //公众号appid
    protected $appid = "";

    //商户id
    protected $mch_id = "";

    //商户key
    protected $key = "";


    function __construct($appid, $mch_id, $key)
    {


        $this->appid = $appid;
        $this->mch_id = $mch_id;
        $this->key = $key;

    }

    /**
     * 扫码支付
     * Create by Peter
     * @param $order_no
     * @param string $notify_url
     * @param string $ip
     * @return array|mixed|string
     */
    function for_NATIVE($order_no, $notify_url, $ip = '')
    {

        if (!$ip) $ip = $_SERVER['REMOTE_ADDR'];


        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";

        $data = [
            'appid' => $this->appid,
            'mch_id' => $this->mch_id,
            'nonce_str' => $this->get_noncestr(),
            'body' => '扫码支付测试',
            'out_trade_no' => $order_no . "_" . mt_rand(1000, 9999),
            'total_fee' => 1,
            'spbill_create_ip' => $ip,
            'notify_url' => $notify_url,
            'trade_type' => 'NATIVE',
            'product_id' => time()

        ];



        $signature = $this->get_signature_for_pay($data);


        $data['sign'] = $signature;


        $data = $this->arrayToXml($data);


        $re = $this->postXmlCurl($data, $url);

        $data = $this->xmlToArray($re);


        return $data;

    }

    /**
     * 微信支付获取签名
     * Create by Peter
     * @param array $param
     * @return bool|string
     */
    private function get_signature_for_pay(array $param)
    {

        ksort($param);

        $str = "";

        foreach ($param as $key => $value) {

            $str .= $key . "=" . $value . "&";

        }

        $str = substr($str, 0, strlen($str) - 1);


        $str .= "&key=" . $this->key;

        $str = md5($str);

        return $str;

    }


    /**
     * 数组转XML
     * Create by Peter
     * @param $arr
     * @return string
     */
    private function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }


    /**
     * xml转数组
     * Create by Peter
     * @param $xml
     * @return mixed
     */
    private function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }


    /**
     * 接口请求
     * Create by Peter
     * @param $xml
     * @param $url
     * @param bool $useCert
     * @param int $second
     * @return bool|mixed
     */
   private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            //curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            //curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }


    /**
     * 生成随机字符串
     * Create by Peter
     * @param int $length
     * @return string
     */
    protected function get_noncestr($length = 16)
    {

        $str = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";

        $noncestr = "";

        for ($i = 0; $i < $length; $i++) {


            $noncestr .= substr($str, mt_rand(0, strlen($str) - 1), 1);

        }


        return $noncestr;


    }

    /**
     * 返回微信服务器成功
     * Create by Peter
     */
    function echo_success()
    {

        echo "<xml>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <return_msg><![CDATA[OK]]></return_msg>
             </xml>";
    }

    /**
     * 验证签名
     * Create by Peter
     * @return bool|mixed
     */
    function check(){


        $data=$GLOBALS['HTTP_RAW_POST_DATA'];

        $data=$this->xmlToArray($data);


        $sign=$data['sign'];

        if(!$sign) return false;

        unset($data['sign']);
        $s=$this->get_signature_for_pay($data);

        if(strtolower($s)==strtolower($sign)) return $data;


        return false;


    }

}