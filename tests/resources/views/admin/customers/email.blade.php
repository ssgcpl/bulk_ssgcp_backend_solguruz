@extends('layouts.admin.app')
@section('css')


<meta name="viewport" content="width=device-width, initial-scale=1">
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.0/cropper.css">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('customers.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('customers.index')}}">{{ trans('customers.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('customers.send_email') }}
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
              {{ trans('customers.send_email_details')}}
              </h4>
              @can('customer-list')
                <a href="{{route('customers.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="ReasonForm" action="{{route('send_email')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <div class="form-body">
                    <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.email')}}<span class="text-danger custom_asterisk">*</span></label>
                        <select name="user_id"
                         class="form-control @error('user_id') ? is-invalid : ''  @enderror">
                         <option value="{{ $customer->id }}" {{ (old('user_id')==$customer->id) ?'selected':''}} >{{ $customer->email }}</option>
                         <option value="all" {{ (old('user_id')=='all') ?'selected':'' }}>{{ trans('customers.all') }}</option>
                       </select>
                          @error('user_id')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('user_id') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.subject')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control @error('subject') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('customers.pl_enter_subject')}}" name="subject" value="{{ old('subject') }}" required>
                          @error('subject')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('subject') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>

                    <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="template" class="content-label">{{trans('customers.template')}}<span class="text-danger custom_asterisk">*</span></label>
                        <select name="template" id="template" 
                         class="form-control @error('template') ? is-invalid : ''  @enderror">
                         <option value="">{{ trans('customers.choose_template') }}</option>
                         @foreach($templates as $template) 
                         <option value="{{ $template->id }}" {{ old('template')? 'selected':'' }}>{{ $template->name }}</option>
                        @endforeach
                        <option value="0">Custom</option>
                       </select>
                          @error('template')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('template') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group @error('message') ? has-error : ''  @enderror">
                        <label for="message" class="content-label">{{trans('customers.message')}}<span class="text-danger custom_asterisk">*</span></label>
                        <textarea  class="form-control  @error('message') ? is-invalid : '' @enderror" id="message" name="message">{{old('message')}}</textarea>
                          @if ($errors->has('message')) 
                          <strong class="invalid-feedback">{{ $errors->first('message') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>
                </div>
                  
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-info btn-fill btn-wd">{{trans('common.submit')}}</button>
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

@section('js')
<!-- <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script> -->

<script>
//CKEDITOR.replaceAll();
    $("#template").change(function(){
    var template_id = $(this).val();
     $.ajax({
            type:'post',
            url: "{{route('template_message')}}",
            dataType : 'json',
            data: {
                    "id" : template_id,
                    "_token": "{{ csrf_token() }}"
                  },
            beforeSend: function () {
            },
            success: function (response) {
             console.log(response);
             if(response.data != null) {
              tinymce.get("message").setContent(response.data);
              }
              else if(response.data == 'custom'){
                tinymce.get("message").setContent();
                toastr.error("No Content Found");
              }
             
            },
            error: function () {
              toastr.error(data.error);
            }
        })
  })
</script>
@endsection
