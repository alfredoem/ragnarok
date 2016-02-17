<?php namespace Alfredoem\Ragnarok;


use Alfredoem\Ragnarok\Utilities\EncryptAes;
use Alfredoem\Ragnarok\RagnarokCurlResponse;

class RagnarokCurl
{

    protected $curlResponse;

    public function __construct(RagnarokCurlResponse $curlResponse)
    {
        $this->curlResponse = $curlResponse;
    }

    /**
     * @param $url
     * @return \Alfredoem\Ragnarok\RagnarokCurlResponse
     */
    public function httpGetRequest($url)
    {
        // create curl resource
        $curl = curl_init();

        // set url
        curl_setopt($curl, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $response = curl_exec($curl);

        $resolve = $this->curlResponse->resolve($curl, $response);

        curl_close($curl);

        return $resolve;
    }

    /**
     * @param $url
     * @return bool
     */
    public function httpStatusConnection($url)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL)) {// check, if a valid url is provided
            return false;
        }

        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);// 301 solved
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return $httpCode == 200 ? true : false;
    }

    /**
     * @param $url
     * @param $data
     * @return \Alfredoem\Ragnarok\RagnarokCurlResponse
     */
    public function httpPosRequest($url, $data)
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);

        $resolve = $this->curlResponse->resolve($curl, $response);

        curl_close($curl);

        return $resolve;
    }

}