<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\Translations\CmsTranslation;
use App\Models\Helpers\CommonHelper;
use DB;


class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:cms-list', ['only' => ['index']]);
       
    
    }
    
    use CommonHelper;

    public function index(Request $request)
    {
        $page_title = trans('cms.cms_index');
        $cms = Cms::all();
        
        return view('admin.cms.index',compact('cms','page_title'));
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = trans('cms.edit_detail');
        $cms = Cms::find($id);
        return view('admin.cms.edit',compact('cms','page_title'));
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

        $data = $request->all();
        $cms  = Cms::find($id);
        
        if(empty($cms)){
            return redirect()->route('cms.index')->with('error',trans('admincms.error'));
        }

        $validator = $request->validate([

         
          'content'       => 'required',
          'page_name'   => 'required|max:190',
           
        ]);

        
        DB::beginTransaction();
        try{

            if($cms->update($data)){
                 DB::commit();
                return redirect()->route('cms.index')->with('success',trans('cms.cms_update_success'));
            } else {
                DB::rollback();
                return redirect()->route('cms.index')->with('error',trans('cms.error'));
            }
        }catch(\Exception $e){

            DB::rollback();
            return redirect()->route('cms.index')->with('error',trans('cms.error'));
        }
        
    }

}
   