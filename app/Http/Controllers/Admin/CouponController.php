<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Models\User;
use Validator, Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\ItemType;
use App\Models\OrderItem;
use App\Models\SubCoupon;
use App\Models\CouponMaster;
use App\Models\CouponQrCode;
use Illuminate\Http\Request;
use App\Models\SubCouponImage;
use App\Models\BusinessCategory;
use App\Models\SubCouponCategory;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;

class CouponController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:coupon-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:coupon-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coupon-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coupon-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $page_title = trans('coupons.heading');
        $item_types = ItemType::where('status', 'active')->get();
        return view('admin.coupons.index', compact('page_title', 'item_types'));
    }


    public function create()
    {
        $page_title = trans('products.admin_heading');
        $item_types = ItemType::where('status', 'active')->get();
        $categories = Category::WhereNull('parent_id')->where('is_live', '1')->where('status', 'active')->get();
        $business_categories = BusinessCategory::whereIn('layout',['digital_coupons'])->where(['is_live'=>'1', 'status'=>'active'])->get();

        return view('admin.coupons.create', compact('page_title', 'item_types', 'categories','business_categories'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_master_id'     => 'required|unique:sub_coupons',
            'business_category_id' => 'required',
            'mrp'                  => 'required|numeric|min:0|gte:sale_price',
            'sale_price'           => 'required|numeric|min:0',
            'image'                => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
            'description'          => 'required|min:2|max:99',
            'category'             => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        DB::beginTransaction();
        try {

            $data = $request->all();
            $user = Auth::user();

            if ($data['image'] != null) {
                $data['image'] = $this->saveMedia($data['image']);
            }
            $coupon_master  = CouponMaster::find($request->coupon_master_id);
            $sub_coupon = new SubCoupon();
            $data['status'] = 'active';
            $data['coupon_id'] = $coupon_master->coupon_id;
            $data['coupon_master_id'] = $request->coupon_master_id;
            $data['available_quantity'] = $coupon_master->quantity;
            $sub_coupon->fill($data);
            $sub_coupon->save();

            if ($sub_coupon) {
                if ($request->category) {
                    $categories = $request->category;
                    foreach ($categories as $category => $value) {
                        SubCouponCategory::create([
                            "category_id" => $value,
                            "sub_coupon_id" => $sub_coupon->id,
                        ]);
                    }
                }

                if ($data['cover_image'] != null) {
                    if (count($data['cover_image'])) {
                        foreach ($data['cover_image'] as $cvr_img) {
                            SubCouponImage::create([
                                "sub_coupon_id" => $sub_coupon->id,
                                "cover_image" => $this->saveMedia($cvr_img)
                            ]);
                        }
                    }
                }
            }

            if ($sub_coupon) {
                DB::commit();
                return response()->json(['success' => trans('coupons.added')]);
            } else {
                DB::rollback();
                return response()->json(['error' => trans('coupons.error')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request)
    {

        //  $query           =    SubCoupon::query()->groupBy('product_id');
        $query = SubCoupon::query();
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

        ## from/to date filter
        $from_date       =    $request['start_date'];
        $to_date         =    $request['end_date'];
        if ($from_date != '' && $to_date != '') {
            $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
            $query = $query->whereBetween('created_at', [$from_date." 00:00:00", $to_date." 23:59:00"]);
        }

        ## Filter by Status
        if ($request['status'] == '') {
            $query = $query;
        } else if ($request['status'] == 'inactive') {
            $query = $query->where('status', 'inactive');
        } else if ($request['status'] == 'active') {
            $query = $query->where('status', 'active');
        }

        ## Filter by type
        $type = $request['type'];
        if ($request['type'] == '') {
            $query = $query;
        } else {
            $query = $query->whereHas('coupon', function ($q) use ($type) {
                $q->where('item_type', $type);
            });
        }

        ## Filter by state
        $state = $request['coupon_state'];
        if ($state == '') {
            $query = $query;
        } else if ($state == 'available') {
            $query = $query->whereHas('coupon', function ($q) use ($state) {
                $q->where('end_date', '>=', Carbon::now()->format('Y-m-d'));
            });
        } else if ($state == 'expired') {
            $query = $query->whereHas('coupon', function ($q) use ($state) {
                $q->where('end_date', '<', Carbon::now()->format('Y-m-d'));
            });
        }

        if(isset($request['business_category_id'])){
            $query = $query->where('business_category_id',$request['business_category_id']);
        }


        ## Total number of record with filtering
        $filter = $query;

        if ($searchValue != '') {
            $filter =  $filter->whereHas('coupon', function ($q) use ($searchValue) {
                $q->Where('coupon_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('name', 'like', '%' . $searchValue . '%');
            })
                ->orWhere('id', 'like', '%' . $searchValue . '%');
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $i = 1;
        foreach ($empQuery as $emp) {
            ## Set dynamic route for action buttons
            $emp['coupon_id'] = $emp->coupon_id;
            $emp['coupon_name'] = $emp->coupon ? $emp->coupon->name : '';
            $emp['coupon_image'] = '<img src="' . asset($emp->image) . '" width="100%" height="100%">';
            $emp['mrp'] = $emp->mrp;
            $emp['sale_price'] = $emp->sale_price;
            $emp['item_type'] = $emp->coupon ? trans('coupons.' . $emp->coupon->item_type . '') : '';
            $emp['available_quantity'] = $emp->available_quantity;
            $emp['created_date']  = date("d-m-Y h:i A", strtotime($emp->created_at));
            if ($emp->coupon->end_date > Carbon::now()->format('Y-m-d H:i')) {
                $emp['state'] = trans('coupons.available');
            } else {
                $emp['state'] = trans('coupons.expired');
            }
            $business_category = BusinessCategory::where('id',$emp->business_category_id)->first();
            $emp['business_category'] = $business_category->category_name;

            $emp['show'] = route("coupons.show", $emp->id);
            $emp['edit'] = route("coupons.edit", $emp->id);
            $emp['sub_coupon_id'] = $emp->id;
            $emp['id'] = $i;
            $data[] = $emp;
            $i++;
        }
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        echo json_encode($response);
    }



    public function edit($id)
    {
        $page_title  = trans('coupons.update');
        $categories = Category::WhereNull('parent_id')->where('is_live', '1')->where('status', 'active')->get();

        $coupons       = SubCoupon::find($id);
        $coupon_master  = CouponMaster::find($coupons->coupon_master_id);
        if (empty($coupons)) {
            return redirect()->route('coupons.index')->with('error', trans('common.no_data'));
        }
        $coupon_category_ids = SubCouponCategory::where('sub_coupon_id', $id)->pluck('category_id')->toArray();

        $business_categories = BusinessCategory::whereIn('layout',['digital_coupons'])->where(['is_live'=>'1', 'status'=>'active'])->get();

        return view('admin.coupons.edit', compact('coupons', 'page_title', 'categories', 'coupon_category_ids', 'coupon_master', 'business_categories'));
    }


    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data       =  $request->all();

            $validator = Validator::make($request->all(), [
                'mrp'                  => 'required|numeric|min:0|gte:sale_price',
                'business_category_id' => 'required',
                'sale_price'           => 'required|numeric|min:0',
                'image'                => 'nullable|image|mimes:png,jpg,jpeg,svg|max:10000',
                'description'          => 'required|min:2|max:99',
                'category'             => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()]);
            }

            $sub_coupon = SubCoupon::find($id);

            if (empty($sub_coupon)) {
                return redirect()->route('coupons.index')->with('error', trans('admin.error'));
            }
            if (isset($data['image']) && $data['image'] != null) {
                $this->deleteMedia($sub_coupon->image);
                $data['image'] = $this->saveMedia($data['image']);
            }

            $sub_coupon->fill($data);
            $sub_coupon->save();

            if (!empty($data['cover_images'])) {
                if (count($data['cover_images'])) {
                    foreach ($data['cover_images'] as $cvr_img) {
                        SubCouponImage::create([
                            "sub_coupon_id" => $sub_coupon->id,
                            "cover_image" => $this->saveMedia($cvr_img)
                        ]);
                    }
                }
            } else { // atlease 1 cover image
                $cover_imgs = SubCouponImage::where('sub_coupon_id', $sub_coupon->id)->get()->count();
                if ($cover_imgs == 0) {
                    return response()->json(['error' => 'Need atleast one cover image']);
                }
            }

            if ($sub_coupon) {
                if ($request->category) {
                    SubCouponCategory::where('sub_coupon_id', $id)->delete();
                    $categories = $request->category;
                    foreach ($categories as $category => $value) {
                        SubCouponCategory::create([
                            "category_id" => $value,
                            "sub_coupon_id" => $sub_coupon->id,
                        ]);
                    }
                }
            }

            if ($sub_coupon) {
                DB::commit();
                return response()->json(['success' => trans('coupons.updated')]);
            } else {
                DB::rollback();
                return response()->json(['error' => trans('coupons.error')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show(Request $request, $id)
    {
        $page_title  = trans('coupons.details');
        $categories = Category::WhereNull('parent_id')->where('is_live', '1')->where('status', 'active')->get();

        $coupons       = SubCoupon::find($id);
        $coupon_master  = CouponMaster::find($coupons->coupon_master_id);
        if (empty($coupons)) {
            return redirect()->route('coupons.index')->with('error', trans('common.no_data'));
        }
        $coupon_category_ids = SubCouponCategory::where('sub_coupon_id', $id)->pluck('category_id')->toArray();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();

        return view('admin.coupons.show', compact('page_title','categories','coupons','coupon_master','coupon_category_ids','business_categories'));
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = SubCoupon::find($id);
        if ($wishlist->delete()) {
            return redirect()->back()->with('success', trans('wish_list.deleted'));
        } else {
            return redirect()->back()->with('error', trans('wish_list.error'));
        }
    }

    public function status(Request $request)
    {
        $coupon = SubCoupon::where('id', $request->id)->update(['status' => $request->status]);
        if ($coupon) {
            return response()->json(['success' => trans('coupons.status_updated')]);
        } else {
            return response()->json(['error' => trans('coupons.error')]);
        }
    }

    public function qr_coupon_ajax(Request $request)
    {
        $sub_coupon      = SubCoupon::find($request->id);
        $query           =    CouponQrCode::where('coupon_master_id',$sub_coupon->coupon_master_id);
        $totalRecords    =    $query->count();
        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value
        ## Total number of record with filtering
        $filter= $query;

        if($searchValue != ''){
          $filter = $filter->Where('qr_code_value','like',$searchValue.'%');
         /*->whereHas('coupon_master',function($q) use($searchValue){
                               // $q->Where('state','like',$searchValue.'%')
                                //->orwhere('item_type','%'.$searchValue.'%');
                            })*/
        }


        $filter_data           = $filter->count();
        $totalRecordwithFilter = $filter_data;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();

        $i = 1;
        foreach ($empQuery as $emp) {

            $emp['qr_code_image']    = '<img src="'.asset($emp->qr_code).'" style="width:70px">';
            // $emp['unique_code']    = $emp->qr_code_value;
            $emp['end_date']   =$emp->coupon_master->end_date ? date('d/m/Y',strtotime($emp->coupon_master->end_date)) : '';
            $emp['number']    =  $row + $i;
            // $emp['for']        = $emp->coupon->for;
            $emp['for']        = ucfirst(str_replace('_', ' ', $emp->coupon_master->item_type));
            // $emp['status']     = ucfirst($emp->coupon_master->status);
            //$emp['state']      = ucfirst($emp->coupon_master->state);
	    $emp['state']      = ucfirst($emp->state);

            $data[] = $emp;
            $i++;
        }

        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }





    // data fetch in create module
    public function coupon_master_list($item_type)
    {
        try {
            $sub_coupon = SubCoupon::pluck('coupon_master_id');
          //  $sub_coupon = SubCoupon::pluck('coupon_id');
            $coupons = CouponMaster::where('item_type', $item_type)->where('is_live', '1')->where('state', 'fresh')->where('end_date', '>=', Carbon::now()->format('Y-m-d h:i'))->whereNotIn('id', $sub_coupon)->where('is_deleted','0')->get();
        
            return response()->json(['success' => '1', 'data' => $coupons, 'message' => 'coupon_list']);
        } catch (Exception $e) {
            return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
        }
        }

    public function coupon_master_detail($id)
    {

        try {
            $coupons = CouponMaster::where('id', $id)->first();
            $coupons->expire_date = date("d-m-Y h:i A",strtotime($coupons->end_date));
            return response()->json(['success' => '1', 'data' => $coupons, 'message' => 'coupon_details']);
        } catch (\Exception $e) {
            return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
        }
    }


    public function remove_coupon_cover_image(Request $request)
    {
        $coupon_cover = SubCouponImage::where('id', $request->id)->first();
        $cover_imgs = SubCouponImage::where('sub_coupon_id', $coupon_cover->product_id)->get()->count();
        if ($cover_imgs == 1) {
            return response()->json(['error' => 'Need atleast one cover image']);
        } else {
            if (file_exists($coupon_cover->cover_image)) {
                unlink($coupon_cover->cover_image);
            }
            if ($coupon_cover) {
                $coupon_cover->delete();
                return true;
            } else {
                return false;
            }
        }
    }

}
