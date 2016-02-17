<?php namespace Alfredoem\Ragnarok\Api\v1;

use Alfredoem\Ragnarok\Utilities\Make;
use Illuminate\Support\Facades\Auth;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use Alfredoem\Ragnarok\AuthRagnarok;
use Alfredoem\Ragnarok\RagnarokResponse;
use Illuminate\Support\Facades\Session;

class RagnarokApi
{
    protected $success = false;
    protected $userRagnarok;
    protected $responseRagnarok;

    public function __construct(AuthRagnarok $userRagnarok, RagnarokResponse $responseRagnarok)
    {
        $this->responseRagnarok = $responseRagnarok;
        $this->userRagnarok = $userRagnarok;
    }

    /**
     * @param $data
     * @return \Alfredoem\Ragnarok\RagnarokResponse
     */

    public function login($data)
    {
        if( ! key_exists('remember', $data)) {
            $data['remember'] = false;
        }

        // login attempt
        if (Auth::once(['email' => $data['email'], 'password' => $data['password']], $data['remember'])) {

            $auth = Auth::user();

            // Store session user
            $session = SecUserSessions::create(['userId' => $auth->userId, 'sessionCode' => Make::uniqueString(),
                'ipAddress' => $data['ipAddress'], 'status' => 1, 'dateIns' => date('Y-m-d'),
                'datetimeIns' => date('Y-m-d H:m:s')]);

            $auth->ipAddress = $data['ipAddress'];
            $auth->sessionCode = $session->sessionCode;
            $auth->userSessionId = $session->userSessionId;
            $auth->environment = Session::get('environment');

            // Make ragnarok user
            $this->userRagnarok ->make($auth);

            $this->success = true;
        }

        return $this->responseRagnarok->make($this->success, $this->userRagnarok);
    }
}