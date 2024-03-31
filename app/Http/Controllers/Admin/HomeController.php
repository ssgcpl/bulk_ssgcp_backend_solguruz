<?php

namespace App\Http\Controllers\Admin;

use DB;
use App;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Profile;
use App\Models\Setting;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\SsgcSuggestion;
use App\Models\WishSuggestion;
use App\Models\WishList;
use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\WishReturn;
use stdClass;

class HomeController extends Controller
{
		/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     // $this->middleware(['auth','profile_updated']);
    }

    public function index()
    {
      $user = auth()->user();
      // ADMIN DASHBOARD
      $page_title = trans('dashboard.title');

      if($user->user_type == 'customer'){

        Auth::logout();
        return redirect()->route('login')->with('error',trans('auth.invalid_user_type'));
      }

      // Total data

      $total           = new stdClass;
      $total->total_customers  = User::whereIn('user_type',['dealer','retailer'])->count();
      $total->total_retailer  = User::where('user_type','retailer')->count();
      $total->total_dealer  = User::where('user_type','dealer')->count();


      $total->total_ordered     = Order::where('is_cart','0')->count();
      $total->total_wish_order    = WishList::query()->count();
      $total->total_return_order      = OrderReturn::where('is_cart','0')->count();
      $total->total_return_wish_order      = WishReturn::query()->count();
      //$total->total_ticket_raised      = Ticket::whereIn('user_type',['customer','guest'])->count();
      $total->total_ticket_raised      = Ticket::query()->count();
      $wish_suggestion      = WishSuggestion::query()->count();
      $ssgc_suggestion      = SsgcSuggestion::query()->count();
      $total->total_customer_suggestion      = $wish_suggestion + $ssgc_suggestion;

      // Today Statistics
      $_today = date('Y-m-d');
      $today           = new stdClass;
      $today->today_customers  = User::whereDate('created_at','=', $_today)->whereIn('user_type',['dealer','retailer'])->count();
      $today->today_retailer  = User::whereDate('created_at','=', $_today)->where('user_type','retailer')->count();
      $today->today_dealer  = User::whereDate('created_at','=', $_today)->where('user_type','dealer')->count();
      $today->today_ordered     = Order::where('is_cart','0')->whereDate('created_at','=', $_today)->count();
      $today->today_wish_order    = WishList::whereDate('created_at','=', $_today)->count();
      $today->today_return_order      = OrderReturn::where('is_cart','0')->whereDate('returned_at','=', $_today)->count();
      $today->today_return_wish_order      = WishReturn::query()->whereDate('created_at','=', $_today)->count();
      $today->today_ticket_raised      = Ticket::query()->whereDate('created_at','=', $_today)->count();
      $wish_suggestion      = WishSuggestion::query()->whereDate('created_at','=', $_today)->count();
      $ssgc_suggestion      = SsgcSuggestion::query()->whereDate('created_at','=', $_today)->count();
      $today->today_customer_suggestion      = $wish_suggestion + $ssgc_suggestion;


      // chart
      $chart           = new stdClass;
      $chart->normal_order = Order::where('is_cart','0')->count();
      $chart->wish_order = WishList::query()->count();
      $chart->return_order = OrderReturn::where('is_cart','0')->count();
      $chart->return_wish_order = WishList::query()->count();

    return view('admin.dashboard.admin',compact('page_title','today','total','chart'));

    }

    public function date_wise_dashboard_data(Request $request)
    {

      DB::beginTransaction();
      try {

        // Today Statistics
        $_today = $request->date_filter;
        $_today = date("Y-m-d",strtotime($_today));
        $today           = new stdClass;
        $today->today_customers  = User::whereDate('created_at','=', $_today)->whereIn('user_type',['dealer','retailer'])->count();

        $today->today_ordered     = Order::where('is_cart','0')->whereDate('created_at','=', $_today)->count();
        $today->today_retailer  = User::whereDate('created_at','=', $_today)->where('user_type','retailer')->count();
        $today->today_dealer  = User::whereDate('created_at','=', $_today)->where('user_type','dealer')->count();

        $today->today_wish_order    = WishList::whereDate('created_at','=', $_today)->count();
        $today->today_return_order      = OrderReturn::where('is_cart','0')->whereDate('returned_at','=', $_today)->count();
        $today->today_return_wish_order      = WishReturn::query()->whereDate('created_at','=', $_today)->count();
        $today->today_ticket_raised      = Ticket::query()->whereDate('created_at','=', $_today)->count();
        $wish_suggestion      = WishSuggestion::query()->whereDate('created_at','=', $_today)->count();
        $ssgc_suggestion      = SsgcSuggestion::query()->whereDate('created_at','=', $_today)->count();
        $today->today_customer_suggestion      = $wish_suggestion + $ssgc_suggestion;

        return response()->json(['success' => '1', 'data' => $today, 'message' => 'dashboard_details']);

      }catch (\Exception $e) {

        DB::rollback();
        return response()->json(['type' => 'error','message' => $e->getMessage()]);
      }
    }








}
