<?php namespace Alfredoem\Ragnarok\Http\Controllers\RagnarokApi\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alfredoem\Ragnarok\Api\v1\RagnarokApi;
use Alfredoem\Ragnarok\Utilities\EncryptAes;

class RagnarokApiController extends Controller
{
    protected $loginPath = '/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/login';
    protected $api;

    public function __construct(RagnarokApi $api)
    {
        $this->api = $api;
    }

    public function getIndex()
    {
        return "=)";
    }

    public function postLogin(Request $request)
    {
        $input = $request->all();
        $data = json_decode(
            EncryptAes::dencrypt($input['data'])
        );

        return EncryptAes::encrypt(json_encode($this->api->login($data->email, $data->password, $data->remember)));
    }

}
