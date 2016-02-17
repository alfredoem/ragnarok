<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;

class RagnarokService
{
    const API_SECURITY_URL = 1;
    const SERVER_SECURITY_URL = 2;

    protected $curlRagnarok;

    public function __construct(RagnarokCurl $curlRagnarok, RagnarokApi $apiRagnarok)
    {
        $this->apiRagnarok = $apiRagnarok;
        $this->curlRagnarok = $curlRagnarok;
    }

    /**
     * @param $data
     * @return RagnarokResponse
     */
    public function login($data)
    {
        return $this->apiRagnarok->login($data);
    }

    /**
     * @return bool
     */
    public function checkConnection()
    {
        $serverUrl = $this->getServerSecurityUrl();
        return $this->curlRagnarok->httpStatusConnection($serverUrl);
    }

    /**
     * @param $userId
     * @param $sessionCode
     * @return RagnarokCurlResponse
     */
    public function validUserSession($userId, $sessionCode)
    {
        $url = $this->getAPISecurityUrl('valid-user-session', [$userId, $sessionCode]);

        $response = $this->curlRagnarok->httpGetRequest($url);

        return $response;
    }

    public function forgetUserSession()
    {
        $auth = AuthRagnarok::user();

        if ($auth->environment == 1 || ( ! $this->checkConnection() && $auth->environment == 2)) {
            $session = SecUserSessions::find($auth->userSessionId);

            if ($session) {
                $session->update(['status' => 0, 'datetimeUpd' => date('Y-m-d H:m:s')]);
            }
        }
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return string
     */
    public function getAPISecurityUrl($method = '', $parameters = array())
    {
        $url = SecParameter::find(self::API_SECURITY_URL)->value;

        if ($method) {
            $url .= "/{$method}";

            if ( ! empty($parameters)) {
                foreach ($parameters as $param) {
                    $url .= "/{$param}";
                }
            }

        }

        return $url;
    }

    /**
     * @return string
     */
    public function getServerSecurityUrl()
    {
        return SecParameter::find(self::SERVER_SECURITY_URL)->value;
    }

}