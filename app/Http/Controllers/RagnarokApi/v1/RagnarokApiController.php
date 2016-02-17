<?php namespace Alfredoem\Ragnarok\Http\Controllers\RagnarokApi\v1;

use Alfredoem\Ragnarok\AuthRagnarok;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\EncryptAes;

class RagnarokApiController extends Controller
{
    /**
     * 1: security server, 2: admin application
     * @var int
     */
    protected $environment = 2;
    protected $authRagnarok;

    public function __construct(AuthRagnarok $authRagnarok)
    {
        $this->authRagnarok = $authRagnarok;

    }

    public function getIndex()
    {
        return trans('ragnarok.api.info');
    }

    public function getValidUserSession(Request $request, $userId, $sessionCode)
    {
        $valid = SecUserSessions::whereuserid($userId)
            ->wheresessioncode($sessionCode)
            ->whereipaddress($request->ip())
            ->wheredateins(date('Y-m-d'))
            ->wherestatus(1);

        $count = $valid->count();

        if ($count > 0) {
            $session = $valid->get()->last();
            $data = $session->user;
            $data->ipAddress = $session->ipAddress;
            $data->sessionCode = $session->sessionCode;
            $data->userSessionId = $session->userSessionId;
            $data->environment = $this->environment;

            $auth = $this->authRagnarok->instance($data);

            return EncryptAes::encrypt(json_encode(['success' => true, 'data' => $auth]));
        }

        return EncryptAes::encrypt(json_encode(['success' => false, 'data' => []]));
    }
}
