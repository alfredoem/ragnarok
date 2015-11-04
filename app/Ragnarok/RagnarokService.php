<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Utilities\EncryptAes;

use Alfredoem\Ragnarok\SecParameters\SecParameter;

class RagnarokService
{

    const API_SECURITY_URL = 1;

    public function login($data)
    {
        if (! isset($data['remember'])) {
            $data['remember'] = false;
        }

        $data = json_encode(['email'  =>  $data['email'], 'password'  =>  $data['password'], 'remember'  =>  $data['remember']]);
        $url =  SecParameter::find(self::API_SECURITY_URL)->value . '/login';
        return json_decode($this->executeCURL($data, $url));
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

        if( curl_errno($curl) ){
            $json = json_encode(array('Success'=> false,'Msg' => curl_error($curl)));
        }else{
            $json = EncryptAes::dencrypt($response);
        }

        curl_close($curl);

        return $json;
    }

}