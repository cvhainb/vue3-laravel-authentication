<?php

namespace App\Http\Controllers\V1\Storefront;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use DB;

class ExportController extends Controller
{

    private $language = "en";

    private $gmHeader = ['id', 'title', 'description', 'link', 'image_link', 'mobile_link', 'additional_image_link', 
    'availability', 'availability_date', 'cost_of_goods_sold', 'expiration_date', 'price', 'sale_price', 
    'sale_price_effective_date', 'google_product_category', 'product_type', 'brand', 'gtin', 'mpn', 
    'identifier_exists', 'condition', 'item_group_id', 'ships_from_country'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "feed_type" => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);    
        }

        switch($request->input('feed_type')) {
            case 'Google':
                $this->exportDataForGoogleShopping($request->all());
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Array $attributes
     * @return \Illuminate\Http\Response
     */
    public function setGoogleMerchantHeader($attributes)
    {
        $this->gmHeader = array_merge($this->gmHeader, $attributes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportDataForGoogleShopping($data)
    {
        
        // Set attributes for header
        if(isset($data['attributes']) && !empty($data['attributes'])) {
            $this->setGoogleMerchantHeader($data['attributes']);
        }
        
        // open the file "demosaved.csv" for writing
        $user = DB::table('users')->where('id', auth()->user()->id)->first();
        $subfolder = strtotime($user->created_at);

        // Create file
        Storage::disk('local')->put("feeds/{$subfolder}/Google_Shopping_Feed.csv", "");
        $fh = fopen(storage_path("app/feeds/{$subfolder}/Google_Shopping_Feed.csv"), 'w');

        // Push header
        fputcsv($fh, $this->gmHeader);

        $products = DB::table('products')->join('product_translations', function ($join) {
            $join->on('products.user_id', '=', 'product_translations.user_id')
            ->on('products.store_product_id', '=', 'product_translations.store_product_id');
        })->where('locale', $this->language)->get();

        foreach($products as $product) {

            $imageLink = $additionalImageLink = '';
            $images = json_decode($product->images, true);
            if(!empty($images)) {
                $imageLink = $images[0];
                if(count($images) > 1) {
                    array_shift($images);
                    $additionalImageLink = implode(',', $images);
                }
            }

            $csvData = [];

            // Set all keys with empty value
            foreach($this->gmHeader as $title) {
                $csvData[] = "";
            }
            
            $csvData[array_search('id', $this->gmHeader)] = $product->store_product_id;
            $csvData[array_search('title', $this->gmHeader)] = $product->name;
            $csvData[array_search('description', $this->gmHeader)] = $product->description;
            $csvData[array_search('link', $this->gmHeader)] = $product->url;
            $csvData[array_search('image_link', $this->gmHeader)] = $imageLink;
            $csvData[array_search('mobile_link', $this->gmHeader)] = "";
            $csvData[array_search('additional_image_link', $this->gmHeader)] = $additionalImageLink;
            $csvData[array_search('availability', $this->gmHeader)] = $product->quantity > 0 ? 'in stock' : 'out of stock';
            $csvData[array_search('price', $this->gmHeader)] = $product->price;
            $csvData[array_search('sale_price', $this->gmHeader)] = $product->sale_price > 0 ? $product->sale_price : "";
            $csvData[array_search('google_product_category', $this->gmHeader)] = isset($data['google_product_category']) && !empty($data['google_product_category']) ? $data['google_product_category'] : "";
            $csvData[array_search('product_type', $this->gmHeader)] = isset($data['product_type']) && !empty($data['product_type']) ? $data['product_type'] : "";
            $csvData[array_search('brand', $this->gmHeader)] = $product->brand;
            $csvData[array_search('gtin', $this->gmHeader)] = $product->gtin;
            $csvData[array_search('mpn', $this->gmHeader)] = $product->mpn;
            $csvData[array_search('product_weight', $this->gmHeader)] = "{$product->weight} {$data['weight_unit']}";
            $csvData[array_search('product_width', $this->gmHeader)] = "{$product->width} {$data['dimension_unit']}";
            $csvData[array_search('product_length', $this->gmHeader)] = "{$product->length} {$data['dimension_unit']}";
            $csvData[array_search('product_height', $this->gmHeader)] = "{$product->height} {$data['dimension_unit']}";
            $csvData[array_search('condition', $this->gmHeader)] = 'new';
            $csvData[array_search('identifier_exists', $this->gmHeader)] = (empty($product->gtin) || empty($product->mpn)) ? 'no' : 'yes';
            $csvData[array_search('item_group_id', $this->gmHeader)] = $product->item_group_id > 0 ? $product->item_group_id : "";
            $csvData[array_search('ships_from_country', $this->gmHeader)] = isset($data['ships_from_country']) && !empty($data['ships_from_country']) ? $data['ships_from_country'] : "US";

            // Loop the attributes
            $meta = json_decode($product->meta, true);
            if(!empty($meta) && isset($data['attributes']) && !empty($data['attributes'])) {
                $compared = array_intersect($data['attributes'], $meta['options']);
                if(!empty($compared)) {
                    foreach($compared as $attr) {
                        $csvData[array_search($attr, $this->gmHeader)] = $meta['values'][$attr][0];
                    }
                }
            }
            

            // Put data to file
            fputcsv($fh, $csvData);
        }

        // Convert csv to xlsx
        $spreadsheet = new Spreadsheet();
        $reader = new Csv();

        /* Set CSV parsing options */

        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);

        /* Load a CSV file and save as a XLS */
        $spreadsheet = $reader->load(storage_path("app/feeds/{$subfolder}/Google_Shopping_Feed.csv"));
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path("app/feeds/{$subfolder}/Google_Shopping_Feed.xlsx"));

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        Storage::disk('local')->delete("feeds/{$subfolder}/Google_Shopping_Feed.csv");

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
