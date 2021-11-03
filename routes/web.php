<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\LoginCredentials;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/google-oauth2', function(Request $request) {
    
    $url = config('app.url');

    $client = new Google\Client();
    $client->setAuthConfig(Storage::disk('local')->path('/client_secret_993141084449-eu5ppq35r364v5a51gpvgmora5bj78p1.apps.googleusercontent.com.json'));
    $client->setScopes("https://www.googleapis.com/auth/content");
    $client->setRedirectUri("{$url}/store/oauth2/google");

    if ($request->input('code')) {
    } else {
        header('Location: ' . $client->createAuthUrl());
        exit;
    }
});

/* Route::get('/google-oauth2', function(Request $request) {
    
    $client = new Google\Client();
    $client->setAuthConfig(Storage::disk('local')->path('/client_secret_993141084449-eu5ppq35r364v5a51gpvgmora5bj78p1.apps.googleusercontent.com.json'));
    $client->setScopes('https://www.googleapis.com/auth/content');
    $client->setRedirectUri('https://0aa1-2001-ee0-53a5-31f0-2d88-524b-808f-6803.ngrok.io/google-oauth2');

    if (session('oauth_access_token')) {

        $client->setAccessToken(session('oauth_access_token'));
        
        $service = new Google\Service\ShoppingContent($client);

        $merchant_id = '6297658';
        $product = new Google_Service_ShoppingContent_Product();
        $product->setOfferId('book123');
        $product->setTitle('A Tale of Two Cities');
        $product->setDescription('A classic novel about the French Revolution');
        $product->setLink('http://my-book-shop.com/tale-of-two-cities.html');
        $product->setImageLink('http://my-book-shop.com/tale-of-two-cities.jpg');
        $product->setContentLanguage('en');
        $product->setTargetCountry('GB');
        $product->setChannel('online');
        $product->setAvailability('in stock');
        $product->setCondition('new');
        $product->setGoogleProductCategory('Media > Books');
        $product->setGtin('9780007350896');

        $price = new Google_Service_ShoppingContent_Price();
        $price->setValue('12.50');
        $price->setCurrency('GBP');

        $shipping_price = new Google_Service_ShoppingContent_Price();
        $shipping_price->setValue('0.99');
        $shipping_price->setCurrency('GBP');

        $shipping = new Google_Service_ShoppingContent_ProductShipping();
        $shipping->setPrice($shipping_price);
        $shipping->setCountry('GB');
        $shipping->setService('Standard shipping');

        $shipping_weight = new Google_Service_ShoppingContent_ProductShippingWeight();
        $shipping_weight->setValue(200);
        $shipping_weight->setUnit('grams');

        $product->setPrice($price);
        $product->setShipping(array($shipping));
        $product->setShippingWeight($shipping_weight);
        var_dump($product);

    $result = $service->products->insert($merchant_id, $product);
    var_dump($result);die('dddd');
    } elseif (isset($_GET['code'])) {
        $token = $client->authenticate($_GET['code']);
        //$_SESSION['oauth_access_token'] = $token;
        session()->put('oauth_access_token', $token);
        session()->save();
        header('Location: https://0aa1-2001-ee0-53a5-31f0-2d88-524b-808f-6803.ngrok.io/google-oauth2');
    } else {
        header('Location: ' . $client->createAuthUrl());
        exit;
    }

}); */

Route::get('/google', function(Request $request) {

    $url = config('app.url');

    $client = new Google\Client();
    $client->setAuthConfig(Storage::disk('local')->path('/client_secret_993141084449-eu5ppq35r364v5a51gpvgmora5bj78p1.apps.googleusercontent.com.json'));
    $client->setScopes('https://www.googleapis.com/auth/content');
    $client->setRedirectUri("{$url}/google-oauth2");

    $token = json_decode(Storage::disk('local')->get("/feeds/1634354760/GOOGLE_MERCHANT.token"), true);
    $client->setAccessToken($token);

    $service = new Google\Service\ShoppingContent($client);

    $merchant_id = '6297658';
    $product = new Google_Service_ShoppingContent_Product();
    $product->setOfferId('book123');
    $product->setTitle('A Tale of Two Cities');
    $product->setDescription('A classic novel about the French Revolution');
    $product->setLink('http://my-book-shop.com/tale-of-two-cities.html');
    $product->setImageLink('http://my-book-shop.com/tale-of-two-cities.jpg');
    $product->setContentLanguage('en');
    $product->setTargetCountry('GB');
    $product->setChannel('online');
    $product->setAvailability('in stock');
    $product->setCondition('new');
    $product->setGoogleProductCategory('Media > Books');
    $product->setGtin('9780007350896');

    $price = new Google_Service_ShoppingContent_Price();
    $price->setValue('2.50');
    $price->setCurrency('GBP');

    $shipping_price = new Google_Service_ShoppingContent_Price();
    $shipping_price->setValue('0.99');
    $shipping_price->setCurrency('GBP');

    $shipping = new Google_Service_ShoppingContent_ProductShipping();
    $shipping->setPrice($shipping_price);
    $shipping->setCountry('GB');
    $shipping->setService('Standard shipping');

    $shipping_weight = new Google_Service_ShoppingContent_ProductShippingWeight();
    $shipping_weight->setValue(200);
    $shipping_weight->setUnit('grams');

    $product->setPrice($price);
    $product->setShipping(array($shipping));
    $product->setShippingWeight($shipping_weight);
    
    $result = $service->products->insert($merchant_id, $product);
    var_dump($result);die('dddd');

});

/**
 * Get response from external websites
 * used for web hook from payment gateway
 */
Route::get('/authorized', function(Request $request) {

    // Store token
    $storeUrl = $request->input('store_url');
    $storeName = Arr::first(explode('.', $storeUrl));
    if(empty($storeName)) {
        echo "Unauthenticated";
        return false;
    }
    
    // Save the token
    Storage::disk('local')->put("stores/{$storeUrl}", $request->input('token'));

    // Verify the store/token
    $storeUrl = 'http://localhost:8000';
    $response = Http::withToken($request->input('token'))->get("{$storeUrl}/api/v1/app/store-owner");
    $json = $response->json();

    // Add a new user to mcfeede and auto login
    if(!empty($json)) {
        
        $password = Str::random(8);
        DB::table('users')->insertOrIgnore([
            'name' => $json['name'],
            'email' => $json['email'],
            'password' => bcrypt($password),
            'store_url' => $json['domain'],
            'created_at' => Carbon::now(),
        ]);
        
        Mail::to($json['email'])->send(new LoginCredentials([
            'email' => $json['email'],
            'password' => $password,
        ]));

        return redirect("/login?msg=We just sent you an email with your login details.");
    }

    return false;
});

/**
 * App vue component
 */
Route::get('/{any}', function() {

    return view('app');

})->where('any', '.*');

