<?php namespace Alfredoem\Ragnarok;

class RagnarokService
{

    public function login($email, $password)
    {
        $data = json_encode([
            'email'  =>  $email,
            'password'  =>  $password
        ]);

        $url = 'http://local.ragnarok.com/ragnarok/api/v1/login';

        return $this->executeCURL($data, $url);
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
            $json = json_encode(array('Success'=> false,'msg' => curl_error($curl)));
        }else{
            $json = EncryptAes::dencrypt($response);
        }
        //dd($json);
        curl_close($curl);

        try {
            $this->logWebServiceRequest($url, '', '', $data, $json);
        } catch (QueryException $e) {

        }

        return $json;
    }




}