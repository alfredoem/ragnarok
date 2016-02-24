<?php

namespace Alfredoem\Ragnarok\Soul;

use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;

class RagnarokService
{
    protected $curlRagnarok;
    protected $paramRagnarok;

    /**
     * RagnarokService constructor.
     * @param RagnarokCurl $curlRagnarok
     * @param RagnarokApi $apiRagnarok
     * @param RagnarokParameter $paramRagnarok
     */
    public function __construct(RagnarokCurl $curlRagnarok,
                                RagnarokApi $apiRagnarok,
                                RagnarokParameter $paramRagnarok)
    {
        $this->apiRagnarok = $apiRagnarok;
        $this->curlRagnarok = $curlRagnarok;
        $this->paramRagnarok = $paramRagnarok;
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
        $serverUrl = $this->paramRagnarok->retrieve(SecParameter::SERVER_SECURITY_URL);
        return $this->curlRagnarok->httpStatusConnection($serverUrl);
    }

    /**
     * @param $userId
     * @param $sessionCode
     * @return RagnarokCurlResponse
     */
    public function validUserSession($userId, $sessionCode)
    {
        $url = $this->paramRagnarok->retrieve(SecParameter::API_SECURITY_URL);
        $uri = "{$url}/valid-user-session/{$userId}/{$sessionCode}";
        $response = $this->curlRagnarok->httpGetRequest($uri);
        return $response;
    }

    public function forgetUserSession()
    {
        $auth = AuthRagnarok::user();

        if(AuthRagnarok::check()) {

            if ($auth->environment == 1 || ( ! $this->checkConnection() && $auth->environment == 2)) {
                $session = SecUserSessions::find($auth->userSessionId);

                if ($session) {
                    $session->update(['status' => 0, 'datetimeUpd' => date('Y-m-d H:m:s')]);
                }

            }

        }
    }
}