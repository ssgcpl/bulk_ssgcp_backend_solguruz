@extends('layouts.admin.app')
@section('css')

<style type="text/css">
  .add_new_category {cursor: pointer;}
  .form-group label {width:100%;}
  .close_cat_div {float: right; position: relative; padding: 2px; top: 1px}
</style>

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
                    <h2 class="content-header-title float-left mb-0">{{trans('nested_categories.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('nested_categories.index')}}">{{ trans('nested_categories.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('nested_categories.add_new') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--     @if ($errors->any())
      <div class="alert alert-danger">
        <b>{{trans('common.whoops')}}</b>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
 -->    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
              {{ trans('nested_categories.details')}}
              </h4>
              <a href="{{route('nested_categories.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="NestedCategoryForm" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <div class="form-body">
                    <div class="row">
                          <div class="col-md-4" id="">
                            <div id="multiple_categories_div">
                              <div class="form-group">
                                <label for="nested_category_name" class="content-label">{{trans('nested_categories.name')}}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('nested_category_name') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('nested_categories.name')}}" name="category_name" value="{{$nested_category->category_name}}" required >
                                @if ($errors->has('category_name')) 
                                  <strong class="invalid-feedback">{{ $errors->first('category_name') }}</strong>
                                @endif
                              </div>  
                              <div class="form-group">
                                <label for="nested_category_parent" class="content-label">{{trans('nested_categories.parent')}}</label>
                                <input class="form-control @error('nested_category_parent') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('nested_categories.name')}}" name="" value="{{isset($nested_category->parent) ? $nested_category->parent->category_name : '-'}}" disabled >
                              </div>  

                              <div class="form-group">
                                <label for="nested_category_level" class="content-label">{{trans('nested_categories.level')}}</label>
                                <input class="form-control @error('nested_category_level') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('nested_categories.level')}}" name="" value="{{$nested_category->level}}" disabled >
                              </div>  
                            </div>
                            
                            <input name="publish" type="checkbox" value="1" @if($nested_category->is_live == '1') checked @endif id="publish">&nbsp;&nbsp; Publish
                          </div>
                          <div class="col-md-8">
                            <h3 class="text-info">Select the Level if you want to change</h3>
                            <h4 class="text-danger">Please take a note: Changing the level, could be affect the Hierarchy</h4>
                            <hr>
                            <div id="category_level_1">
                              <div class="form-group categories_levels_div" >
                                <input type="hidden" name="level" id="level" value="{{$nested_category->level}}">
                                <label for="nested_category_level" class="content-label">Level 1</label>
                                <p class="text-danger">Do not select any category from the drop-down, if you want to add at "Level 1 (Master Category)"</p>
                                <select class="form-control" name="parent_id" id="l1_categories">
                                    <option value="">Select Parent</option>
                                    @foreach($master_categories as $mcategory)
                                      <option value="{{$mcategory->id}}">{{$mcategory->category_name}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div> 
                            <div id="category_level_2"></div> 
                            <div id="category_level_3"></div> 
                            <div id="category_level_4"></div> 
                            <div id="category_level_5"></div> 
                            <div id="category_level_6"></div> 
                            <div id="category_level_7"></div> 
                          </div>
                    </div>
                  </div>


                  </div>
                </div>
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success">{{trans('common.submit')}}</button>
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

<script>

  // For Multiple Categories
  $('.add_new_category').click(function() {
      var html = `<div class="form-group">
                    <label for="nested_category_name" class="content-label">{{trans('nested_categories.name')}}<span class="text-danger custom_asterisk">*</span> <i class="fa fa-close close_cat_div"></i></label>
                    <input class="form-control @error('category_name') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('nested_categories.name')}}" name="category_name[]" required >
                  </div>
                  @if ($errors->has('category_name')) 
                                  <strong class="invalid-feedback">{{ $errors->first('category_name') }}</strong>
                                @endif`;
      $('#multiple_categories_div').append(html);                          
  });

  $('body').on('click', '.close_cat_div', function(){
    $(this).parent().parent().remove();
  })

  function append_categories_dropdown(data, level, value){
    var categories = data.success;
    if(value == ''){
      $('#level').val(level-1);
    }else{
      $('#level').val(level);
    }
    // console.log('categories.length', categories.length); console.log('level', level);
    $('#category_level_'+level).html('');
    if(categories && !categories.length){
      for (let i = level+1; i < 8; i++) {
        // console.log('id', i);
        $('#category_level_'+i).html('');
      }
      return false;
    }
    var html = `<div class="form-group categories_levels_div_`+level+`" >
                  <label for="nested_category_level" class="content-label">Level `+level+`</label>
                  <p class="text-danger">Do not select any category from the drop-down, if you want to add at "Level `+(level)+`"</p>
                  <select class="form-control" id="l`+level+`_categories" name="parent_id">`;

    html = html+"<option value=''>Select Parent</option>";
    $.each(categories, function( index, value ) {
      html = html+`<option value="`+value.id+`">`+value.category_name+`</option>`;
    });
    html = html+"</select></div>";
    $('#category_level_'+level).append(html);
  }


  // For Nested Categories
  $('body').on('change', '#l1_categories', function(){
    var data = get_childs($(this).val());
    append_categories_dropdown(data, 2, $(this).val());
  });

  $('body').on('change', '#l2_categories', function(){
    var data = get_childs($(this).val());
    append_categories_dropdown(data, 3, $(this).val());
  });

  $('body').on('change', '#l3_categories', function(){
    var data = get_childs($(this).val());
    append_categories_dropdown(data, 4, $(this).val());
  });

  $('body').on('change', '#l4_categories', function(){
    var data = get_childs($(this).val());
    append_categories_dropdown(data, 5, $(this).val());
  });

  $('body').on('change', '#l5_categories', function(){
    var data = get_childs($(this).val());
    append_categories_dropdown(data, 6, $(this).val());
  });

  // $('body').on('change', '#l6_categories', function(){
  //   var data = get_childs($(this).val());
  //   append_categories_dropdown(data, 7, $(this).val());
  // });



  function get_childs(category_id) {
      var return_data;
      $.ajax({
            type:'post',
            url: "{{route('get_category_childs')}}",
            data: {
                    "category_id": category_id, 
                    "_token": "{{ csrf_token() }}"
                  },
            async: false,
            beforeSend: function () {
                // element.next('.loading').css('visibility', 'visible');
            },
            success: function (data) {
              return_data = data;
              // console.log(data);
              // return data;
            },
            error: function () {
              toastr.error(data.error);
            }
      });
      return return_data;
  }


  //Save Categories
  $('#NestedCategoryForm').on('submit', function (e) {
          
          e.preventDefault();
          var data = $('#NestedCategoryForm').serialize();
          data = data.replace(/&?[^=]+=&|&[^=]+=$/g,'');
          //console.log(data); return false;
          var route = "{{route('nested_categories.update',':category_id')}}";
          route = route.replace(':category_id', '{{$nested_category->id}}');
          $.ajax({
                   url: route,
                   data: data,
                   type: "PUT",
                   beforeSend: function(xhr){
                      $('#loader').show();
                    },
                   success: function(response) {
                      if(response.success) {
                          toastr.success(response.success);
                          setTimeout(function(){
                            location.href =  "{{route('nested_categories.index')}}";   
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#loader').hide();
                   }
          });
          //console.log('data',data)
      })

</script>

@endsection
