@extends('layouts.admin.app')
@section('css')
<style>
  .info-box-content {padding: 8px 0px;}
</style>
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('cms.cms_index')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('cms.index')}}">{{ trans('cms.cms_index') }}</a></li>
                            <!-- <li class="breadcrumb-item active">{{ trans('cms.add_new') }} -->
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
      <div class="alert alert-danger">
        <b>{{trans('common.whoops')}}</b>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
              {{ trans('cms.title')}}
              </h4>

            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="categoryForm" action="{{route('cms.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <div class="modal-body">

                      <div class="row">
                          @foreach($cms as $cm)
                            <?php if($cm->slug == 'privacy_policy'){
                              $page_name = $cm->page_name;
                            }else if($cm->slug == 'terms_conditions'){
                              $page_name = $cm->page_name;
                            }else if($cm->slug == 'about_us'){
                              $page_name = $cm->page_name;
                            }
                            else{
                              $page_name = $cm->page_name;
                            }
                            ?>
                            <div class="col-md-6 col-sm-8 col-xs-12" >
                              <div class="info-box">
                               
                                <div class="info-box-content">
                                   <span class="info-box-text"><i class="fa fa-file-text"></i> <b>{{$page_name}}</b></span> @can('cms-edit')   
                                   <span class="pull-right"><b> <a href ="{{route('cms.edit',$cm->id)}}"><i class="fa fa-edit"></i>  {{trans('cms.edit_detail')}}</a></b></span>
                                   @endcan
                                </div>
                              </div>
                            </div>
                          @endforeach
                      </div> 
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>   
    </section>
  </div>
</div>
@endsection
