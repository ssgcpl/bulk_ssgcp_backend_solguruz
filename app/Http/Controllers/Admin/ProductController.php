<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCoverImage;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use App\Models\DealerRetailer;
use App\Models\BusinessCategory;
use App\Models\Notification;
use App\Models\OrderReturnItem;
use App\Models\StockTransfer;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class ProductController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:product-list', ['only' => ['index','show']]);
      $this->middleware('permission:product-create', ['only' => ['create','store']]);
      $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('products.heading');
      $type = BusinessCategory::where(['layout'=>'books','status'=>'active'])->get();
      return view ('admin.products.index',compact('page_title','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

        $query           = Product::query();
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value


        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;


        ## Filter by Stock Status
          if($request['stock_status'] ==''){
            $query = $query;
          }else if ($request['stock_status'] =='in_stock'){
              $query = $query->where('stock_status','in_stock');
          }else if ($request['stock_status'] =='out_of_stock'){
             $query = $query->where('stock_status','out_of_stock');
          }

          ## Filter by visible to
          if($request['visible_to'] ==''){
            $query = $query;
          }else if ($request['visible_to'] =='dealer'){
              $query = $query->whereIn('visible_to',['dealer','both']);
          }else if ($request['visible_to'] =='retailer'){
             $query = $query->whereIn('visible_to',['retailer','both']);
          }

           ## Filter by type
          if(isset($request['business_category_id'])){
             $query = $query->where('business_category_id',$request['business_category_id']);
          }

          ## from/to date filter
          if($request['start_date'] != '' && $request['end_date'] != ''){
            $from_date       =    date("Y-m-d",strtotime($request['start_date']));
            $to_date         =    date("Y-m-d",strtotime($request['end_date']));
            /*$from_date = $request['start_date'];
            $end_date = $request['end_date'];
            */$query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
          }

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('name_english','like','%'.$searchValue.'%')
                            ->orWhere('sku_id','like','%'.$searchValue.'%')
                            ->orWhere('name_hindi','like','%'.$searchValue.'%')
                            ->orWhere('status','like','%'.$searchValue.'%')
                            ->orWhere('id','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

        	$emp['image'] = ($emp->image) ? '<img src="'.asset($emp->image).'"
        	width="80%" height="80%">' : '';
        	//$emp['sub_heading'] = $emp->get_sub_heading();
          $emp['name'] = $emp->get_name();
        	//$emp['sub_heading'] = $emp->name_hindi ? $emp->name_hindi : $emp->name_english;
        	$emp['sku_id'] = $emp->sku_id;
          
          $stock = $this->getStockData($emp->id);
          $emp['stock'] = $stock['total_balance'];
          if($emp->stock_status == 'out_of_stock')
          {
            $emp['stock_status'] = '<p style="color:red;">'.trans('products.'.$emp->stock_status).'</p>';
          }
          else if($emp->stock_status == 'in_stock')
          {
            $emp['stock_status'] = '<p style="color:green;">'.trans('products.'.$emp->stock_status).'</p>';
          }else{
            $emp['stock_status'] = '';
          }
        	$emp['visible_to'] = ucfirst($emp->visible_to);
        	$emp['mrp'] = $emp->mrp;
        	$emp['dealer_sale_price'] = $emp['dealer_sale_price'];
        	$emp['retailer_sale_price'] = $emp['retailer_sale_price'];
        	//$emp['created_date'] = date('d-m-Y H:i A',strtotime($emp['created_at']));
          if($emp['republished_at'] != ''){
           // $emp['created_at'] = date('d-m-Y H:i A',strtotime($emp['published_at']));
            $emp['republished_at'] = $emp['republished_at'];
          }else {
            $emp['republished_at'] = $emp['created_at'];
          }

          //  $emp['created_at'] = date('d-m-Y H:i A',strtotime($emp->created_at));
          
        	  $type = BusinessCategory::where('id',$emp->business_category_id)->first();
            $emp['type'] = $type->category_name;
            $emp['status'] = $emp->status;
            ## Set dynamic route for action buttons
            $emp['show']= route("products.show",$emp["id"]);
            $emp['edit']= route("products.edit",$emp["id"]);
           $data[]=$emp;
        }
      //  print_r($data['status_hide']); die;

        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }

    public function create(){
        $page_title = trans('products.admin_heading');
        $categories = Category::WhereNull('parent_id')->where('is_live','1')->where('status','active')->get();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();
        $related_products = Product::where(['status'=>'active','is_live'=>'1'])->get();
        return view('admin.products.create', compact('page_title','categories', 'business_categories','related_products'));
    }

    public function store(Request $request)
    {

    	if(isset($data['language_hindi']) && isset($data['language_english'])){

    	  $validator = Validator::make($request->all(),[
          'name_english' => 'required|min:2|max:99',
          'sub_heading_english' => 'required|min:2|max:99',
          'description_english' => 'required|min:2|max:99',
          'additional_info_english' =>'required|min:2|max:99',

          'name_hindi' => 'required|min:2|max:99',
		  'sub_heading_hindi' => 'required|min:2|max:99',
          'description_hindi' => 'required|min:2|max:99',
          'additional_info_hindi' =>'required|min:2|max:99',
 		  ]);
    	}
    	else if(isset($data['language_hindi'])){
    		 $validator = Validator::make($request->all(),[

	          'name_hindi' => 'required|min:2|max:99',
			  'sub_heading_hindi' => 'required|min:2|max:99',
	          'description_hindi' => 'required|min:2|max:99',
	          'additional_info_hindi' =>'required|min:2|max:99',
	 		]);
    	}
    	else if(isset($data['language_english'])){
    		 $validator = Validator::make($request->all(),[
		          'name_english' => 'required|min:2|max:99',
				  'sub_heading_english' => 'required|min:2|max:99',
		          'description_english' => 'required|min:2|max:99',
		          'additional_info_english' =>'required|min:2|max:99',
		 		]);
    	}

        $validator = Validator::make($request->all(),[
         // 'language' => 'required',
          'business_category_id' => 'required',
          //'mrp' => 'required|numeric|min:0|gte:dealer_sale_price',
          'mrp' => 'required|numeric|min:0',
          'dealer_sale_price' => 'required|numeric|min:0|lte:mrp',
          'retailer_sale_price' => 'required|numeric|min:0|lte:mrp',
          'sku_id' => 'required|nullable|min:1|max:99',
          'weight' => 'required|nullable|min:1|max:99',
          'length' => 'required|nullable|min:1|max:99',
          'height' => 'required|nullable|min:1|max:99',
          'width' => 'required|nullable|min:1|max:99',
          'image' => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
          'category'  =>  'required',
          'visible_to'=> 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }


        DB::beginTransaction();
        try {

            $data = $request->all();
            $user = Auth::user();


            $data['added_by'] = $user->id;
            $data['last_returnable_date'] = date("Y-m-d",strtotime($data['last_returnable_date']));


            if(isset($data['is_live'])) {
              $data['is_live']="1";
            }
            else  {
              $data['is_live'] = "0";
            }

            if(count($data['visible_to']) == '2'){
              $data['visible_to'] = 'both';
            } else {
              $data['visible_to'] = $data['visible_to'][0];
            }

            $data['published_at'] = date('Y-m-d H:i:s'); 
            $data['republished_at'] = date('Y-m-d H:i:s'); 

                if(isset($data['language_english']) && isset($data['language_hindi'])) {    	$data['language'] = 'both';
                }
                else if(isset($data['language_hindi'])) {
                	$data['language'] = $data['language_hindi'];
                }
                else if(isset($data['language_english'])) {
                	$data['language'] = $data['language_english'];
                }

            if($data['image'] != null){
              	$data['image'] = $this->saveMedia($data['image']);
            }


            $product = new Product();
            $product->fill($data);
            $product->save();

            if($product){
                if($request->category){
                    $categories = $request->category;
                    foreach ($categories as $category=>$value){
                        ProductCategory::create([
                            "category_id" => $value,
                            "product_id"=> $product->id,
                        ]);
                    }
                }

                if($data['product_cover_images'] != null) {
                  if(count($data['product_cover_images'])) {
                      foreach ($data['product_cover_images'] as $cvr_img) {
                        ProductCoverImage::create([
                            "product_id"=> $product->id,
                            "image" => $this->saveMedia($cvr_img)
                        ]);
                      }
                  }
                }

                if(isset($data['related_products']) && $data['related_products'] != null) {
                      foreach ($data['related_products'] as $rp) {
                        RelatedProduct::create([
                            "product_id"=> $product->id,
                            'related_product_id'=>$rp,
                            "user_id" => Auth::user()->id,
                        ]);
                      }

                }
            }

            if($product) {
                DB::commit();
                return response()->json(['success' => trans('products.added')]);
            } else {
                DB::rollback();
                return response()->json(['error' => trans('products.error')]);

            }

        }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $page_title  = trans('products.update');
        $categories = Category::WhereNull('parent_id')->where('is_live','1')->where('status','active')->get();

        $product        = Product::find($id);
        if(empty($product)){
            return redirect()->route('products.index')->with('error',trans('common.no_data'));
        }
        $product_category_ids = ProductCategory::where('product_id',$id)->pluck('category_id')->toArray();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();
        $related_product_ids = RelatedProduct::where('product_id',$id)->pluck('related_product_id')->toArray();
        $related_products = Product::where(['status'=>'active','is_live'=>'1'])->get();
        return view('admin.products.edit',compact('product','page_title','categories','product_category_ids','business_categories','related_products','related_product_ids'));
    }

    public function update(Request $request, $id)
    {
      // print_r($request->all());die;
      try {
          DB::beginTransaction();
          $data       =  $request->all();
         if(isset($data['language_hindi']) && isset($data['language_english'])){

    	  $validator = Validator::make($request->all(),[
          'name_english' => 'required|min:2|max:99',
          'sub_heading_english' => 'required|min:2|max:99',
          'description_english' => 'required|min:2|max:99',
          'additional_info_english' =>'required|min:2|max:99',

          'name_hindi' => 'required|min:2|max:99',
		      'sub_heading_hindi' => 'required|min:2|max:99',
          'description_hindi' => 'required|min:2|max:99',
          'additional_info_hindi' =>'required|min:2|max:99',
 		  ]);
    	}
    	else if(isset($data['language_hindi'])){
    		 $validator = Validator::make($request->all(),[

	          'name_hindi' => 'required|min:2|max:99',
    			  'sub_heading_hindi' => 'required|min:2|max:99',
	          'description_hindi' => 'required|min:2|max:99',
	          'additional_info_hindi' =>'required|min:2|max:99',
	 		]);
    	}
    	else if(isset($data['language_english'])){
    		 $validator = Validator::make($request->all(),[
		          'name_english' => 'required|min:2|max:99',
				  'sub_heading_english' => 'required|min:2|max:99',
		          'description_english' => 'required|min:2|max:99',
		          'additional_info_english' =>'required|min:2|max:99',
		 		]);
    	}

        $validator = Validator::make($request->all(),[
         // 'language' => 'required',
          'business_category_id' => 'required',
          //'mrp' => 'required|numeric|min:0|gte:dealer_sale_price',
          'mrp' => 'required|numeric|min:0',
          'dealer_sale_price' => 'required|numeric|min:0|lte:mrp',
          'retailer_sale_price' => 'required|numeric|min:0|lte:mrp',
          'sku_id' => 'required|nullable|min:1|max:99',
          'weight' => 'required|nullable|min:1|max:99',
          'length' => 'required|nullable|min:1|max:99',
          'height' => 'required|nullable|min:1|max:99',
          'width' => 'required|nullable|min:1|max:99',
          'image' => 'sometimes|image|mimes:png,jpg,jpeg,svg|max:10000',
          'category'  =>  'required',
          'visible_to'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }

          $product = Product::find($id);

          if(empty($product)){
              return redirect()->route('products.index')->with('error',trans('admin.error'));
          }

            $user = Auth::user();
            $data['added_by'] = $user->id;
            $data['last_returnable_date'] = date("Y-m-d",strtotime($data['last_returnable_date']));
            
            if(count($data['visible_to']) == '2'){
              $data['visible_to'] = 'both';
            } else {
              $data['visible_to'] = $data['visible_to'][0];
            } 

             		/*if(isset($data['visible_to_dealer']) && isset($data['visible_to_retailer'])) {
           		    	$data['visible_to'] = 'both';
                }
                else if(isset($data['visible_to_dealer'])) {
                	$data['visible_to'] = $data['visible_to_dealer'];
                }
                else if(isset($data['visible_to_retailer'])) {
                	$data['visible_to'] = $data['visible_to_retailer'];
                }*/

                if(isset($data['language_english']) && isset($data['language_hindi'])) {    	$data['language'] = 'both';
                }
                else if(isset($data['language_hindi'])) {
                	$data['language'] = $data['language_hindi'];
                	$data['name_english'] = '';
                	$data['sub_heading_english'] = '';
                	$data['description_english'] = '';
                	$data['additional_info_english'] = '';
                }
                else if(isset($data['language_english'])) {
                	$data['language'] = $data['language_english'];
                	$data['name_hindi'] = '';
                	$data['sub_heading_hindi'] = '';
                	$data['description_hindi'] = '';
                	$data['additional_info_hindi'] = '';
                }

            if(isset($data['image']) && $data['image'] != null){
              	$this->deleteMedia($product->image);
              	$data['image'] = $this->saveMedia($data['image']);
            }

          if(isset($data['is_live'])) {
            $data['is_live']="1";
          }
          else  {
            $data['is_live'] = "0";
          }

           if($request->publish_date == 1){
                $data['is_live'] ="1";
                //$data['published_at']= date("Y-m-d H:i:s");
                $data['republished_at']= date("Y-m-d H:i:s");
              }


          // print_r($data); die;
          $product->fill($data);
          $product->save();

          if($product){
              if($request->category){
                  ProductCategory::where('product_id',$id)->delete();
                  $categories = $request->category;
                  foreach ($categories as $category=>$value){
                      ProductCategory::create([
                          "category_id" => $value,
                          "product_id"=> $product->id,
                      ]);
                  }
              }

              if(isset($data['related_products']) && $data['related_products'] != null) {
              		$ids = RelatedProduct::where('product_id',$id)->delete();
                  foreach ($data['related_products'] as $rp) {
                        RelatedProduct::create([
                            "product_id"=> $product->id,
                            'related_product_id'=>$rp,
                            "user_id" => Auth::user()->id,
                        ]);
                      }
              }else{
                 $ids = RelatedProduct::where('product_id',$id)->delete();
              }

              if(!empty($data['product_cover_images']))  {
                if(count($data['product_cover_images'])) {
                    foreach ($data['product_cover_images'] as $cvr_img) {
                      ProductCoverImage::create([
                          "product_id"=> $product->id,
                          "image" => $this->saveMedia($cvr_img)
                      ]);
                    }
                }
              }else{ // atlease 1 cover image
                $cover_imgs = ProductCoverImage::where('product_id',$product->id)->get()->count();
                if($cover_imgs == 0) {
                  return response()->json(['error' => 'Need atleast one cover image']);
                }
              }
          }
          if($product) {
              DB::commit();
              return response()->json(['success' => trans('products.updated')]);
          } else {
              DB::rollback();
              return response()->json(['error' => trans('products.error')]);

          }
      }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
      }
    }

    public function show($id)
    {
        $page_title  = trans('products.show');
        $categories = Category::WhereNull('parent_id')->where('is_live','1')->where('status','active')->get();

        $product        = Product::find($id);
        if(empty($product)){
            return redirect()->route('products.index')->with('error',trans('common.no_data'));
        }
        $product_category_ids = ProductCategory::where('product_id',$id)->pluck('category_id')->toArray();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();
        $related_product_ids = RelatedProduct::where('product_id',$id)->pluck('related_product_id')->toArray();
        $related_products = Product::where(['status'=>'active','is_live'=>'1'])->get();
        return view('admin.products.show',compact('product','page_title','categories','product_category_ids','business_categories','related_products','related_product_ids'));
    }


    public function status(Request $request)
    {

        $product = Product::where('id',$request->id)->update(['status'=>$request->status]);
        if($product) {
            return response()->json(['success' => trans('products.status_updated')]);
        } else {
            return response()->json(['error' => trans('products.error')]);
        }
    }

    public function is_live(Request $request)
    {
        $page =  Product::where('id',$request->id)
                  ->update(['is_live'=>$request->is_live]);

        if($page) {

            return response()->json(['success' => trans('products.is_live_updated')]);

        } else {

            return response()->json(['error' => trans('products.error')]);
        }
    }
    public function remove_cover_image(Request $request) {
      // print_r($request->all());die;
          $product_cover = ProductCoverImage::where('id',$request->id)->first();
          $cover_imgs = ProductCoverImage::where('product_id',$product_cover->product_id)->get()->count();
          if($cover_imgs ==1) {
            return response()->json(['error' => 'Need atleast one cover image']);
          }
          else
          {
                if(file_exists($product_cover->image)){
                    unlink($product_cover->image);
                }
                if($product_cover) {
                  $product_cover->delete();
                  return true;
                }else{
                  return false;
                }
          }
    }



}
