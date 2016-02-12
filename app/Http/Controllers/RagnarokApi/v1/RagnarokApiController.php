<?php namespace Alfredoem\Ragnarok\Http\Controllers\RagnarokApi\v1;

use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\EncryptAes;

class RagnarokApiController extends Controller
{
    protected $api;

    public function __construct(RagnarokApi $api)
    {
        $this->api = $api;
    }

    public function getIndex()
    {
        return trans('Ragnarok::messages.api.info');
    }

    public function postLogin(Request $request)
    {
        $input = $request->all();
        $data = json_decode(
            EncryptAes::dencrypt($input['data'])
        );

        return EncryptAes::encrypt(json_encode($this->api->login($data->email, $data->password, $data->remember)));
    }

    public function postValidUserSession(Request $request)
    {
        $input = $request->all();
        $data = json_decode(
            EncryptAes::dencrypt($input['data'])
        );

        $valid = SecUserSessions::whereuserid($data->userId)->wheresessioncode($data->sessionCode);
        $session = $valid->get()->last();
        $count = $valid->count();

        return EncryptAes::encrypt(json_encode(['success' => $count > 0 ? true : false, 'data' => $session->user]));
    }

}
