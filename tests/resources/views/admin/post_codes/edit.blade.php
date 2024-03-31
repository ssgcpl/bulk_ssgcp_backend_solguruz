@extends('layouts.admin.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('post_codes.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('post_codes.index')}}">{{ trans('post_codes.plural') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('post_codes.update')}}
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
              {{ trans('post_codes.details')}}
              </h4>
              <a href="{{route('post_codes.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">  
                <form method="POST" id="cityForm" action="{{route('post_codes.update',$post_code->id)}}" accept-charset="UTF-8">
                     <input name="_method" type="hidden" value="PUT">
                  @csrf
                 <div class="modal-body">
                      <div class="row">
                      <div class="col-md-6">
                      <div class="form-group">
                        <label id="image-error" for="country_name" class ="content-label">
                          {{trans('cities.countries')}}<span class="text-danger custom_asterisk">*</span>
                          </label>
                          <select class="form-control" name="country_name" id="country_id" required>
                            <option value="">{{trans('common.select')}}</option>
                            @foreach($countries as $country)
                              <option value="{{$country->id}}" {{ (old('country_name')==$country->id)? 'selected':'' }}>{{$country->name}}</option>
                            @endforeach
                          </select>
                          @if ($errors->has('country_name')) 
                            <strong class="help-block">{{ $errors->first('country_name') }}</strong>
                          @endif
                      </div>  
                    </div>
                
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="states" class="content-label">{{trans('post_codes.state_name')}}<span class="text-danger custom_asterisk">*</span></label>
                            <select id="state_name" class = "form-control" name='state_name' required>
                                <option value="">{{trans("common.select")}} </option>
                            </select>
                        </div>
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                          <label for="city_name" class="content-label">{{trans('post_codes.city_name')}}<span class="text-danger custom_asterisk">*</span></label>
                            <select id="city_name" class = "form-control" name='city_name' required>
                            <option value="">{{trans("common.select")}} </option>
                            </select>
                        </div>
                      </div>
                  
                    
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="name" class="content-label" class ="content-label"> {{trans('post_codes.postal_code')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control  @error('postcode') ? is-invalid : ''  @enderror" placeholder="{{trans('post_codes.pl_postal_code')}}"  name="postcode" type="text" id="postcode" value="{{old('postcode',$post_code->postcode)}}" autocomplete="off" required >
                           @if ($errors->has('postcode')) 
                                  <strong class="invalid-feedback">{{ $errors->first('postcode') }}</strong>
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
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $( document ).ready(function() {
      get_stateList('{{$country_id}}');
      get_cityList('{{$state_id}}');
    
        setTimeout(function(){ 
        $("#country_id option[value='{{$country_id}}']").prop('selected', true);
        $("#state_name option[value='{{$state_id}}']").prop('selected', true);
        $("#city_name option[value='{{$city_id}}']").prop('selected', true);
    
      }, 500);
    });


  // GET STATE LIST
  function get_stateList(countryID = null){
    var url = '{{ route("state_list", ["country_id" => ":country_id"]) }}';  
    if(countryID){
      $.ajax({
        type:"GET",
        url:url.replace(':country_id', countryID),
        success:function(res){   
        if(res.success == "1"){
          $("#state_name").empty()
          $("#state_name").append('<option value="">'+'{{trans("common.select")}}'+'</option>');
          $.each(res.data,function(key,value){
            if('{{$state_id}}' == value.id)
            {
              var selected = "selected";
            }
            else
            {
              var selected = '';
            }
            $("#state_name").append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
          });
        } else {
          toaster.error(res.message);
          $("#state_name").empty();
        }
         }
      });
    } else{
      $("#state_name").empty();
    }
  }

  // GET  CITY LIST
  function get_cityList(state_id = null){
    var url = '{{ route("city_list", ["state_id" => ":state_id"]) }}';  
    if(state_id){
      $.ajax({
        type:"GET",
        url:url.replace(':state_id', state_id),
        success:function(res){   
        if(res.success == "1"){
          $("#city_name").empty()
          $("#city_name").append('<option value="">'+'{{trans("common.select")}}'+'</option>');
          $.each(res.data,function(key,value){
            if('{{$city_id}}' == value.id)
            {
              var selected = "selected";
            }
            else
            {
              var selected = '';
            }
            $("#city_name").append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
          });
        } else {
          toaster.error(res.message);
          $("#city_name").empty();
        }
         }
      });
    } else{
      $("#city_name").empty();
    }
  }


//GET State List

 $('#country_id').change(function(){
      var country_id = $(this).val();
      var url = '{{ route("state_list", ["country_id" => ":country_id"]) }}';  
     // alert(country_id);
      if(country_id!=null){

        $.ajax({
            type:"GET",
            url:url.replace(':country_id', country_id),
            dataType: "json",
            success:function(res){   
            console.log(res);
              if(res.success == "1"){
                $("#state_name").empty()
                $("#state_name").append('<option value="">'+'{{trans("common.select")}}'+'</option>');
                $.each(res.data,function(key,value){
                    $("#state_name").append('<option value="'+value.id + '">'+value.name+'</option>');
                });
              } else {
              //  toaster.error(res.message);
                $("#state_name").empty();
              }
             },
             error: function function_name(data) {
               // body...
                console.log(data);
             
             }
          });
      } else{
        $("#state_name").empty();
      }      
    });



  // GET CITIES LIST 
  $('#state_name').change(function(){
    var state_id = $(this).val();
    var url = '{{ route("city_list", ["state_id" => ":state_id"]) }}';  
    if(state_id){
      $.ajax({
          type:"GET",
          url:url.replace(':state_id',state_id),
          success:function(res){   
            if(res.success == "1"){
              $("#city_name").empty()
              $("#city_name").append('<option value="">'+'{{trans("common.select")}}'+'</option>');
              $.each(res.data,function(key,value){
                  $("#city_name").append('<option value="'+value.id + '">'+value.name+'</option>');
              });
            } else {
              toaster.error(res.message);
              $("#city_name").empty();
            }
           }
        });
    } else{
      $("#city_name").empty();
     
    }      
  });


  
</script>
@endsection