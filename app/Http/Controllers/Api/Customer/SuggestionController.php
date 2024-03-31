<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\SsgcSuggestion;
use App\Models\WishSuggestion;
use App\Models\WishSuggestionImage;
use App\Models\Product;
use App\Http\Resources\Customer\BookResource;
use App\Http\Resources\Customer\SuggestionBookResource;
use App\Models\Helpers\CommonHelper;
use Validator, Auth;
use DB;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class SuggestionController extends BaseController
{
    use CommonHelper;


    /**
     * Suggestion:Ssgc Suggestion add
     *
     * @bodyParam product_id string required Product_id. Example:3
     * @bodyParam description string required Description Example:abc
     * @bodyParam mobile_number string required Mobile Number Example:8787878787
     * @bodyParam email string required Email Example:abc_@gamil.com
     *
     * @response
    {
    "success": "1",
    "status": "200",
    "message": "Ssgc Suggestion Added"
    }
     */

    public function add_ssgc_suggestion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id'         =>  'required|exists:products,id',
                'mobile_number'      => 'required|digits:10',
                'email'              => 'required|email|max:190',
                'description'        => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }
            $user_id = Auth()->user()->id;
            $data = $request->all();
            $data['user_id']   = $user_id;
            $data['email']   = $request->email;
            SsgcSuggestion::create($data);

            return $this->sendResponse('', trans('suggestion.ssgc_suggestion_added'));
        } catch (\Exception $e) {
            return $this->sendError('', trans('common.something_went_wrong'));
        }
    }


    /**
     * Suggestion:Wish Suggestion add
     *
     * @bodyParam book_name string required Book Name Example:abc
     * @bodyParam subject string required Subject . Example:sparsh
     * @bodyParam description string required Description Example:abc
     * @bodyParam mobile_number string required Mobile Number Example:8787878787
     * @bodyParam email string required Email Example:abc_@gamil.co
     * @bodyParam images string Images Example:img/pdf
     * @bodyParam pdf string  Pdf Example:pdf
     *
     * @response
  {
    "success": "1",
    "status": "200",
    "message": "Wish Suggestion Added"
  }

     */
    public function add_wish_suggestion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'book_name'     => 'required|max:299',
                'subject'       => 'required|max:299',
                'description'   => 'required',
                'images'        => 'nullable',
                'images.*'      => 'mimes:jpeg,png,svg',
                'pdf'           => 'nullable',
                'pdf.*'         => 'mimes:pdf',
                'mobile_number' => 'required|digits:10',
                'email'         => 'required|email|max:190',
            ],[
                'description.required' => 'Suggestion box field is required.',
              
            ]);
            if ($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }
            if (empty($request->pdf) && empty($request->images)) {
                return $this->sendError('', trans('suggestion.one_field_required_image_pdf'));
            }
            $user_id = Auth::guard('api')->user()->id;
            $data = $request->except(['images', 'pdf']);
            $data['user_id']   = $user_id;

            $wish_suggestion = WishSuggestion::create($data);

            if ($request->images != null) {
                $images  = $request->images;
                foreach ($images as $image) {
                    $wish_image = new WishSuggestionImage();
                    $wish_image->wish_suggestion_id = $wish_suggestion->id;
                    $path = $this->saveMedia($image, 'wish_image');
                    $wish_image->image = $path;
                    $wish_image->save();
                }
            }
            if ($request->pdf != null) {
                $pdf  = $request->pdf;
                foreach ($pdf as $pf) {
                    $wish_pdf = new WishSuggestionImage();
                    $wish_pdf->wish_suggestion_id = $wish_suggestion->id;
                    $path = $this->saveMedia($pf, 'wish_pdf');
                    $wish_pdf->pdf = $path;
                    $wish_pdf->save();
                }
            }
            return $this->sendResponse('', trans('suggestion.wish_suggestion_added'));
        } catch (\Exception $e) {
            return $this->sendError('', trans('common.something_went_wrong'));
        }
    }


    /**
     * Suggestion: Suggestion Book list
     *
     * @bodyParam language string required Language (hindi,english). Example:hindi
     * @bodyParam category_id string optional Category_id (category_id or subcategory_id of any level). Example:3
     * @response
    {
        "success": "1",
        "status": "200",
        "message": "Books available",
        "data": [
            {
                "book_id": "21",
                "name": "Harry Potter",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740395441227.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "20",
                "name": "the Bible",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740394854126.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "19",
                "name": "CATCH-22",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740393712254.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "18",
                "name": "THE SOUND AND THE FURY",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740392737976.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "17",
                "name": "BRAVE NEW WORLD",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740391962670.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "16",
                "name": "Art of living",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740390975437.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "15",
                "name": "Arthashastra",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740389989247.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "14",
                "name": "Corporate-Chanakya",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740389076333.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "13",
                "name": "Half girl friend",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740388208663.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "12",
                "name": "Beloved",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740387401019.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "11",
                "name": "Don Quixote",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740375109461.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "10",
                "name": "The Great Gatsby",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740374312465.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "9",
                "name": "To Kill a Mockingbird",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740373723833.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "8",
                "name": "atomic-habits",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740372599640.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            },
            {
                "book_id": "7",
                "name": "Believe-Yourself",
                "sale_price": "",
                "mrp": "500.00",
                "image": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/uploads/media/book_16740371632382.png",
                "quantity": "1",
                "added_to_cart": "0",
                "cart_item_id": ""
            }
        ],
        "links": {
            "first": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/api/customer/suggestion_book_list?page=1",
            "last": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/api/customer/suggestion_book_list?page=2",
            "prev": "",
            "next": "http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/api/customer/suggestion_book_list?page=2"
        },
        "meta": {
            "current_page": 1,
            "last_page": 2,
            "per_page": 15,
            "total": 18
        }
    }
     */
    public function suggestion_book_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id,is_live,1',
            'language'    => 'required|in:english,hindi',
        ]);

        if ($validator->fails()) {
            return $this->sendError($this->array, $validator->errors()->first());
        }
        DB::beginTransaction();
        try {

         
            $data = Product::where(['is_live' => '1', 'status' => 'active'])
                ->whereIn('language', ['both', $request->language]);

            $user = Auth::guard('api')->user();
            //Check if user is logged in and show data according to the account type
            if($user){
               $data = $data->whereIn('visible_to',['both',$user->user_type]);
            }else {
                $data = $data->whereIn('visible_to',['both','retailer']);
            }
          
            // check if category is active/publish
            $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                          ->where('status','active')
                          ->whereNull('parent_id');
                      });
                    });
            
            if(isset($request->category_id) && $request->category_id != '')
            {
                $all_category_ids = $this->get_all_child_categories($request->category_id);
                $data = $data->whereHas('categories', function ($q) use ($all_category_ids) {
                            $q->whereIn('category_id', $all_category_ids);
                        });
            }
             $data = $data->orderBy('id','desc')->paginate();
                
            if ($data->count() > 0) {

                foreach($data as $d){
                  $d->lang = $request->language;
                }


                $response       = SuggestionBookResource::Collection($data);

                return $this->sendPaginateResponse($response, trans('products.book_list_success'));
            } else {

                return $this->sendResponse($this->array, trans('products.book_list_empty'));
            }
        } catch (\Exception $e) {
            return $this->sendError($this->array, trans('common.api_error'));
        }
    }
}
