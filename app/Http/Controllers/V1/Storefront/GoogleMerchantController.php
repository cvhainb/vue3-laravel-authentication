<?php

namespace App\Http\Controllers\V1\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\Storefront\UserController;
use Storage;
use Http;

class GoogleMerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authinfo()
    {

        $url = config('app.url');
        $feedPath = (new UserController)->feedPath();

        $client = new \Google\Client();
        $client->setAuthConfig(Storage::disk('local')->path('/client_secret_993141084449-eu5ppq35r364v5a51gpvgmora5bj78p1.apps.googleusercontent.com.json'));
        $client->setScopes('https://www.googleapis.com/auth/content');
        $client->setRedirectUri("{$url}/google-oauth2");

        $token = json_decode(Storage::disk('local')->get("{$feedPath}/GOOGLE_MERCHANT.token"), true);
        $client->setAccessToken($token);
        
        // Token expired
        if($client->isAccessTokenExpired()) {
            return response()->json(['message' => "The access token is expired. Please re-sign in your Google account again!"], 422);
        }
        
        $service = new \Google\Service\ShoppingContent($client);
        
        $googleMerchantAccounts = [];
        foreach($service->accounts->authinfo()->accountIdentifiers as $accountIdentifier) {
            $googleMerchantAccounts[] = [
                'id' => $accountIdentifier->getMerchantId(),
                'name' => $service->accounts->get($accountIdentifier->getMerchantId(), $accountIdentifier->getMerchantId())->getName()
            ];
        }

        return response()->json(['accounts' => $googleMerchantAccounts]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
