<?php

namespace App\Http\Controllers\V1\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use DB;

class OAuth2Controller extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function google(Request $request)
    {
        $url = config('app.url');

        $user = DB::table('users')->where('id', auth()->user()->id)->first();
        $subfolder = strtotime($user->created_at);

        $client = new \Google\Client();
        $client->setAuthConfig(Storage::disk('local')->path('/client_secret_993141084449-eu5ppq35r364v5a51gpvgmora5bj78p1.apps.googleusercontent.com.json'));
        $client->setScopes('https://www.googleapis.com/auth/content');
        $client->setRedirectUri("{$url}/store/oauth2/google");
        $token = $client->authenticate($request->input('code'));
        Storage::disk('local')->put("feeds/{$subfolder}/GOOGLE_MERCHANT.token", json_encode($token));

        return true;
    }
}
