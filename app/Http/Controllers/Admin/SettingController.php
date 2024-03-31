<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use App\Models\Helpers\CommonHelper;

class SettingController extends Controller
{   
     use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page_title = trans('settings.settings');
        $settings = Setting::pluck('value','name')->all();
        return view('admin.settings.index',compact(['settings','page_title']));
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
    public function update(Request $request)
    {   
        $settings = $request->all();
        $vreq = [];
        foreach ($settings as $sk => $sv) {
            if($sk == 'app_name'){
                $validation = 'required|max:99';
            }
            else if($sk == 'google_map_api_key'){
                $validation = 'required|max:50';
            } 
            else if($sk == 'company_logo'){
                $validation = 'image|mimes:png,jpg,jpeg,svg|max:10000';
            }
            else if($sk == 'facebook_url' || $sk == 'twitter_url' || $sk == 'telegram_url' || $sk == 'instagram_url' ){
                $validation = 'nullable';
            }
            else{
                $validation = 'required';
            }
            $vreq[$sk] = $validation;

        }
        $request->validate($vreq);
        if(isset($settings['is_delivery_charges_applicable']))
        {
            Setting::where('name','is_delivery_charges_applicable')->update(['value'=> 'on']);
        }else {
            Setting::where('name','is_delivery_charges_applicable')->update(['value'=> 'off']); 
        }
        foreach ($settings as $key => $value) {
            if($key == 'company_logo' && $value != null){
               // $this->deleteMedia($value);
               $image = $this->saveMedia($value);
               Setting::where('name', 'company_logo')->update(['value'=> $image]);
            }
            else{
             Setting::where('name', $key)->update(['value'=> $value]);
            }
        }  
        return redirect()->route('settings.index')->with('success','Settings updated successfully');
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
