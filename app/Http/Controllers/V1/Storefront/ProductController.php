<?php

namespace App\Http\Controllers\V1\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\V1\Storefront\UserController;
use Storage;
use Http;
use DB;

class ProductController extends Controller
{

    private $lang = 'en';
    private $currency = 'USD';
    private $targetCountry = 'US';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->get();
        $products->map(function($product){
            $product->images = json_decode($product->images);
            $product->translations = DB::table('product_translations')->where([
                'user_id' => auth()->user()->id,
                'store_product_id' => $product->store_product_id
            ])->select('name', 'description', 'locale')->get();
        });
        return response()->json(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function syncing(Request $request)
    {
        
        $page = $request->input('page', 1);
        $storeUrl = DB::table('users')->where('id', auth()->user()->id)->select('store_url')->value('store_url');
        $token = Storage::disk('local')->get("/stores/{$storeUrl}");

        if(empty($token)) {
            return response()->json(['message' => 'Token not found!', 422]);
        }

        $response = Http::withToken($token)->accept('application/json')->get("http://localhost:8000/api/v1/app/products", [
            'page' => $page,
            'show_manufacturer_name' => true,
            'show_image_link' => true,
            'show_product_link' => true,
            'show_product_description' => true,
            'show_attributes' => true,
        ]);

        $jsonData = $response->json();

        // Error
        if(isset($jsonData['message']) && !empty($jsonData['message'])) {
            return response()->json(['message' => $jsonData['message']], 422);
        }

        if(isset($jsonData['paginator']['data']) && count($jsonData['paginator']['data'])) {
            foreach($jsonData['paginator']['data'] as $product) {

                $attributes = [];
                if(isset($product['attributes']) && !empty($product['attributes'])) {
                    foreach($product['attributes'] as $attr) {
                        $ko = array_search($this->lang, array_column($attr['attribute_option']['translations'], 'locale'));
                        $kov = array_search($this->lang, array_column($attr['attribute_option_value']['translations'], 'locale'));
                        
                        $attributes['options'][] = $attr['attribute_option']['translations'][$ko]['name'];
                        $attributes['options'] = array_unique($attributes['options']);
                        $attributes['values'][$attr['attribute_option']['translations'][$ko]['name']][] = $attr['attribute_option_value']['translations'][$kov]['name'];
                        $attributes['values'][$attr['attribute_option']['translations'][$ko]['name']] = array_unique($attributes['values'][$attr['attribute_option']['translations'][$ko]['name']]);
                    }
                }

                // Get english
                $brand = '';
                if(isset($product['manufacturer_translations']) && !empty($product['manufacturer_translations'])) {
                    $km = array_search($this->lang, array_column($product['manufacturer_translations'], 'locale'));
                    $brand = $product['manufacturer_translations'][$km]['name'];
                }
                
                DB::table('products')->updateOrInsert(
                    [
                        'user_id' => auth()->user()->id,
                        'store_product_id' => $product['id'] // Store's product ID
                    ],
                    [
                        'user_id' => auth()->user()->id,
                        'store_product_id' => $product['id'],
                        'images' => json_encode($product['images_link']),
                        'sku' => $product['sku'],
                        'gtin' => $product['gtin'],
                        'mpn' => $product['mpn'],
                        'price' => $product['price'],
                        'sale_price' => $product['sale_price'],
                        'quantity' => $product['quantity'],
                        'brand' => $brand,
                        'item_group_id' => $product['parent_id'],
                        'weight' => $product['weight'],
                        'width' => $product['width'],
                        'length' => $product['length'],
                        'height' => $product['height'],
                        'status' => $product['status'],
                        'meta' => strtolower(json_encode($attributes))
                    ]
                );

                foreach($product['translations'] as $translation) {
                    DB::table('product_translations')->updateOrInsert(
                        [
                            'user_id' => auth()->user()->id,
                            'store_product_id' => $product['id']
                        ],
                        [
                            'user_id' => auth()->user()->id,
                            'store_product_id' => $product['id'],
                            'locale' => $translation['locale'],
                            'name' => $translation['name'],
                            'url' => $translation['product_link'],
                            'description' => $translation['description'],
                        ]
                    );
                }

                $this->pushProduct2GoogleMerchant($this->productDetails($product['id']));
            }
            
            return response()->json(['total' => $jsonData['paginator']['total'], 'current' => $jsonData['paginator']['current_page']*$jsonData['paginator']['per_page']]);
        }

        return response()->json(['message' => 'Your sync data cannot complete at the moment. Please try again!'], 422);

    }

    /**
     * Get product details
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function productDetails($id)
    {
        return DB::table('products')->join('product_translations', 'products.store_product_id', '=', 'product_translations.store_product_id')->where([
            'products.store_product_id' => $id,
            'product_translations.locale' => $this->lang
        ])->first();
    }
    
    /**
     * Push product to Google Merchant
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pushProduct2GoogleMerchant($data)
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

        $merchant_id = '6297658';
        
        $product = new \Google_Service_ShoppingContent_Product();
        $product->setOfferId($data->id);
        $product->setTitle($data->name);
        $product->setDescription($data->description);
        $product->setLink($data->url);
        $product->setImageLink($data->images[0]);
        $product->setContentLanguage($this->lang);
        $product->setTargetCountry($this->targetCountry);
        $product->setChannel('online');
        $product->setAvailability('in stock');
        $product->setCondition('new');
        $product->setGoogleProductCategory('Home & Garden > Linens & Bedding > Bedding > Bed Canopies');
        //$product->setGtin('9780007350896');

        $price = new \Google_Service_ShoppingContent_Price();
        $price->setValue($data->price);
        $price->setCurrency($this->currency);

        $shippingPrice = new \Google_Service_ShoppingContent_Price();
        $shippingPrice->setValue('0.99');
        $shippingPrice->setCurrency($this->currency);

        $shipping = new \Google_Service_ShoppingContent_ProductShipping();
        $shipping->setPrice($shippingPrice);
        $shipping->setCountry($this->targetCountry);
        $shipping->setService('Standard shipping');

        $shippingWeight = new \Google_Service_ShoppingContent_ProductShippingWeight();
        $shippingWeight->setValue($data->weight);
        $shippingWeight->setUnit('grams');

        $product->setPrice($price);
        $product->setShipping(array($shipping));
        $product->setShippingWeight($shippingWeight);
        
        $result = $service->products->insert($merchant_id, $product);

        var_dump($result);die('dddd');
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
