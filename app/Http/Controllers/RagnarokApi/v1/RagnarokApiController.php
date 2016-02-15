<?php namespace Alfredoem\Ragnarok\Http\Controllers\RagnarokApi\v1;

use Alfredoem\Ragnarok\AuthRagnarok;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\EncryptAes;
use Illuminate\Support\Facades\Auth;

class RagnarokApiController extends Controller
{
    protected $api;

    public function __construct(RagnarokApi $api)
    {
        $this->api = $api;
    }

    public function getIndex()
    {
        return trans('ragnarok.api.info');
    }


    public function postValidUserSession(Request $request)
    {
        $input = $request->all();
        $data = json_decode(
            EncryptAes::dencrypt($input['data'])
        );

        $valid = SecUserSessions::whereuserid($data->userId)
            ->wheresessioncode($data->sessionCode)
            ->wheredateins(date('Y-m-d'))
            ->wherestatus(1);

        $count = $valid->count();

        if ($count > 0) {
            $session = $valid->get()->last();
            $user = AuthRagnarok::make($session->user);
            AuthRagnarok::forget();
            return EncryptAes::encrypt(json_encode(['success' => true, 'data' => $user]));
        }

        return EncryptAes::encrypt(json_encode(['success' => false, 'data' => []]));
    }

}
