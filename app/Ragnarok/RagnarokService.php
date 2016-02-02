<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Utilities\EncryptAes;
use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\Make;

class RagnarokService
{
    const API_SECURITY_URL = 1;
    const SERVER_SECURITY_URL = 2;

    public function login($data)
    {
        if(! isset($data['remember']))
        {
            $data['remember'] = false;
        }

        $api = new RagnarokApi;
        return Make::arrayToObject($api->login($data['email'], $data['password'], $data['remember']));
    }

    public static function checkConnection()
    {
        $domain = SecParameter::find(self::SERVER_SECURITY_URL)->value;

        if (!filter_var($domain, FILTER_VALIDATE_URL)) {// check, if a valid url is provided
            return false;
        }

        $domain = SecParameter::find(self::SERVER_SECURITY_URL)->value;

        $valid = fsockopen($domain, 80, $errno, $errstr, 20);

        if ($valid) {
            return true;
        } else {
            return false;
        }

    }


    private function executeCURL($data, $url)
    {
        $enc = EncryptAes::encrypt($data);
        $dataEncrypt = 'data='.$enc;
        $dataEncrypt = str_replace('+', '%2B', $dataEncrypt);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, count($dataEncrypt));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataEncrypt);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//    Retirar en HTTPS
        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if( curl_errno($curl) ){
            $json = json_encode(array('status'=> false, 'statusCode' => $http_status, 'statusText' => curl_error($curl)));
        }else{
            $res = EncryptAes::dencrypt($response);
            $json = json_encode(['status'=> true, 'statusCode' => $http_status, 'statusText' => curl_error($curl), 'response' => json_decode($res)]);
        }

        curl_close($curl);

        return $json;
    }

}