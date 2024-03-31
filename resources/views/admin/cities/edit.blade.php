@extends('layouts.admin.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('cities.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('cities.index')}}">{{ trans('cities.plural') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('cities.update')}}
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
              {{ trans('cities.details')}}
              </h4>
              <a href="{{route('cities.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form id="stateForm" method="POST" action="{{route('cities.update',$city->id)}}" accept-charset="UTF-8">
                  <input name="_method" type="hidden" value="PUT">
                  @csrf
                  <div class="modal-body">
                
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group ">
                          <label id="image-error" for="countries" class ="content-label">
                          {{trans('cities.countries')}}<span class="text-danger custom_asterisk">*</span>
                          </label>
                          <select class="form-control" name="country_name" id="country_id" required>
                            <option value="">{{trans('common.select')}}</option>
                            @foreach($countries as $country)
                              <option value="{{$country->id}}" @if(old('country_name',$country_id) == $country->id) selected @endif>{{$country->name}}</option>`
                            @endforeach
                          </select>
                          @if ($errors->has('country_name')) 
                             <strong class="invalid-feedback">{{ $errors->first('country_name') }}</strong>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group filter">
                          <label for="states" class="content-label">{{trans('cities.state_name')}}<span class="text-danger custom_asterisk">*</span></label>
                            <select id="state_name" class = "form-control" name='state_name' required>
                            <option value="">{{trans("common.select")}} </option>
                            </select>
                        </div>
                      </div>
                    </div>

                        <div class="tab-content">
                         
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="name" class="content-label">{{trans('cities.name')}}<span class="text-danger custom_asterisk">*</span></label>
                                    <input class="form-control @error('city_name') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('cities.name')}}" name="city_name" type="text" value="{{ old( 'city_name',$city->name)}}" required>
                                    @if ($errors->has('city_name')) 
                                      <strong class="invalid-feedback">{{ $errors->first('city_name') }}</strong>
                                    @endif
                                  </div>  
                                </div>
                              </div>
                            </div>    
                        
                        </div>

                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success btn-fill btn-wd">{{trans('common.submit')}}</button>
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
<script type="text/javascript">

    $( document ).ready(function() {
      // alert("{{$state_id}}");
      get_stateList('{{$country_id}}');
      setTimeout(function(){ 
        $("#country_id option[value='{{$country_id}}']").prop('selected', true);
        $("#state_name option[value='{{$state_id}}']").prop('selected', true);
      }, 400);
    });

  $('#country_id').change(function(){
      var country_id = $(this).val();
      var url = '{{ route("state_list", ["country_id" => ":country_id"]) }}';  
      if(country_id){
        $.ajax({
            type:"GET",
            url:url.replace(':country_id', country_id),
            success:function(res){   
              if(res.success == "1"){
                $("#state_name").empty()
                $("#state_name").append('<option value="">'+'{{trans("common.select")}}'+'</option>');
                $.each(res.data,function(key,value){
                    $("#state_name").append('<option value="'+value.id + '">'+value.name+'</option>');
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
          $("#state_name").append('<option>'+'{{trans("common.select")}}'+'</option>');
          $.each(res.data,function(key,value){
            $("#state_name").append('<option value="'+value.id+'">'+value.name+'</option>');
              // take state value which has been selected in data attribute 
                var state_nameVal = $("#state_name").attr("data-selected-state_name");
                if(value.id == "{{$state_id}}")
                {
                  // assign chosen data attribute value to select
                  $("#state_name option[value='{{$state_id}}']").prop('selected', true);
                }
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
</script>
@endsection