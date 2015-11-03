<?php namespace App\Http\Middleware;

//use App\Generals\ParamsDB;
use Closure;

class RagnarokApiGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*$headers = $request->header();
        if(!isset($headers['www-authenticate'])){return json_encode(['success'=>'false', 'msg'=>'Process Rejected']);}
        $code = explode("|",$headers['www-authenticate'][0]);
        $userKey = base64_decode($code[0]);
        $key = ParamsDB::Where('Name', '=', (!isset($code[1])?'KeyApiGiftCardZito':'KeyApiGiftCardZitoWeb'))->first();
        \Session::put('ZitoApp', (!isset($code[1])?'Facebook':'Web'));
        if( ! \Hash::check($userKey, $key->Value) ){
            return json_encode(['success'=>'false', 'msg'=>'Process Rejected']);
        }*/

        return $next($request);
    }
}