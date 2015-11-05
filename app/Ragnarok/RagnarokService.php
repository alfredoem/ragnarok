<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Utilities\EncryptAes;
use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\Make;

class RagnarokService
{
    const API_SECURITY_URL = 1;

    public function login($data)
    {

        if (! isset($data['remember'])) {
            $data['remember'] = false;
        }

        $dataJson = json_encode(['email'  =>  $data['email'], 'password'  =>  $data['password'], 'remember'  =>  $data['remember']]);
        $url =  SecParameter::find(self::API_SECURITY_URL)->value . '/login';
        $response = json_decode($this->executeCURL($dataJson, $url));

        if(! self::checkConnection() && $response->status == false) {
            $api = new RagnarokApi;
            $response = Make::arrayToObject($api->login($data['email'], $data['password'], $data['remember']));
        }

        return $response->response;
    }

    public static function checkConnection()
    {
        $domain = SecParameter::find(self::API_SECURITY_URL)->value;
        //check, if a valid url is provided
        if(!filter_var($domain, FILTER_VALIDATE_URL))
        {
            return false;
        }

        $curlInit = curl_init($domain);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);

        return ($response) ? true : false;
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