<?php namespace Alfredoem\Ragnarok\Http\Controllers\RagnarokApi\v1;

use Alfredoem\Ragnarok\Soul\AuthRagnarok;
use Alfredoem\Ragnarok\Soul\RagnarokResponse;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alfredoem\Ragnarok\Support\EncryptAes;

class RagnarokApiController extends Controller
{
    /**
     * 1: security server, 2: admin application
     * @var int
     */
    protected $environment = 2;
    protected $authRagnarok;
    protected $responseRagnarok;
    protected $responseSuccess = false;
    protected $responseData;

    public function __construct(AuthRagnarok $authRagnarok, RagnarokResponse $responseRagnarok)
    {
        $this->responseRagnarok = $responseRagnarok;
        $this->authRagnarok = $authRagnarok;
    }

    public function getIndex()
    {
        return trans('ragnarok.api.info');
    }

    /**
     * Check if the user have a active session
     * @param Request $request
     * @param $userId
     * @param $sessionCode
     * @return string
     */
    public function getValidUserSession(Request $request, $userId, $sessionCode)
    {
        $session = SecUserSessions::whereuserid($userId)
            ->wheresessioncode($sessionCode)
            ->wheredateins(date('Y-m-d'))
            ->wherestatus(1)
            ->first();

        $ipAddress = $request->ip();

        if ($session) {

            if ($session->datetimeUpd < date('Y-m-d H:m:s')) {
                $session->update(['ipAddress' => $ipAddress, 'datetimeUpd' => date('Y-m-d H:m:s')]);
            }

            if ($session->datetimeUpd < date('Y-m-d H:m:s') || $session->ipAddress == $ipAddress) {

                $data = $session->user;
                $data->ipAddress = $ipAddress;
                $data->sessionCode = $session->sessionCode;
                $data->userSessionId = $session->userSessionId;
                $data->environment = $this->environment;
                $data->remember_token = $session->user->remember_token;

                $this->responseData = $data;
                $this->responseSuccess = true;
            }

        }

        return EncryptAes::encrypt(json_encode($this->responseRagnarok->make(
            $this->responseSuccess, $this->responseData
        )));
    }
}
