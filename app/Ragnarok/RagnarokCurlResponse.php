<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Utilities\EncryptAes;

class RagnarokCurlResponse
{
    public $success = false;
    public $statusCode = 0;
    public $statusText = '';
    public $response;

    public function resolve($curl, $response)
    {
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( curl_errno($curl) ) {

            $this->fill(false, $http_status, curl_error($curl), []);

        } else {

            if ($http_status == 200) {

                $res = json_decode(EncryptAes::dencrypt($response));
                $this->fill($res->success, $http_status, curl_error($curl), $res);

            } else {

                $this->fill(false, $http_status, curl_error($curl), []);

            }

        }

        return $this;
    }

    public function fill($success, $statusCode, $statusText, $response)
    {
        $this->success = $success;
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
        $this->response = $response;
    }

}