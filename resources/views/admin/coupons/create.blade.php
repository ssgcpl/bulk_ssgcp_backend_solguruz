@extends('layouts.admin.app')
@section('css')
@include('layouts.admin.elements.filepond_css')
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ trans('coupons.create_coupon') }} </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('common.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('coupons.index') }}">{{ trans('coupons.heading') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('coupons.add_new') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <b>{{ trans('common.whoops') }}</b>
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
                                    {{ trans('coupons.details') }}
                                </h4>
                                <a href="{{ route('coupons.index') }}" class="btn btn-success">
                                    <i class="fa fa-arrow-left"></i>
                                    {{ trans('common.back') }}
                                </a>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                                        @csrf
                                        <!-- <hr style="border-top: 3px solid blue;"> -->
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="tags"
                                                            class="content-label">{{ trans('coupons.categories') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <ul class="nav nav-pills" role="tablist">
                                                            @foreach ($categories as $category)
                                                                <li class="nav-item">
                                                                    <a class="nav-link" id="" data-toggle="pill"
                                                                        href="#tab_{{ $category->id }}" role="tab"
                                                                        aria-selected="true">{{ $category->category_name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach

                                                        </ul>
                                                        <div class="tab-content">
                                                            @foreach ($categories as $category)
                                                                <div class="tab-pane" id="tab_{{ $category->id }}"
                                                                    role="tabpanel">
                                                                    <div class="container">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div id="treeview-checkbox-demo">
                                                                                    <ul>
                                                                                        {{-- <li> --}}
                                                                                        <input type="checkbox"
                                                                                            id="category[]"
                                                                                            name="category[]"
                                                                                            value="{{ $category->id }}"
                                                                                            class="list"
                                                                                            {{ is_array(old('category')) && in_array($category->id, old('category')) ? 'checked' : '' }}>
                                                                                        {{ $category->category_name }}
                                                                                        @include(
                                                                                            'admin.coupons.manage_checkbox',
                                                                                            [
                                                                                                'childs' =>
                                                                                                    $category->sub_category,
                                                                                            ]
                                                                                        )
                                                                                        {{-- </li> --}}
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="published_date"
                                                            class="content-label">{{ trans('coupons.select_the_item_type') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <select class="form-control" name="type" id="item_type" required>
                                                            <option value="select">{{ trans('coupons.please_select') }}
                                                            </option>
                                                            @foreach ($item_types as $item_type)
                                                                <option value="{{ $item_type->name }}">
                                                                    {{ trans('coupons.' . $item_type->name) }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('type'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('type') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="published_date"
                                                            class="content-label">{{ trans('coupons.select_a_coupon') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <select class="form-control select2" name="coupon_master_id" id="coupon_master_id"
                                                            required>
                                                            <option value="">{{ trans('coupons.please_select') }}
                                                            </option>
                                                        </select>
                                                        @if ($errors->has('type'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('type') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="types"
                                                              class="content-label">{{trans('coupons.business_category')}}<span
                                                                  class="text-danger custom_asterisk">*</span></label><br>
                                                        <select id="business_category_id" class = "form-control" name='business_category_id' required>
                                                            <option value=''>{{trans('common.select')}}</option>
                                                            @foreach($business_categories as $bc)
                                                                <option value="{{$bc->id}}">{{ucfirst($bc->category_name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.coupon_name') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.coupon_name') }}" id="coupon_name"
                                                            type="text" value="" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.coupon_id_ssgc1') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.coupon_id_ssgc1') }}"
                                                            type="text" id="coupon_id_ssgc" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.discount_in_percent') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.discount_in_percent') }}"
                                                            type="text" id="discount" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.qty') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.qty') }}" type="text" id="qty"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.count_usage') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.count_usage') }}"
                                                            type="text" id="usage" disabled>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.applicable_on_item_name') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.applicable_on_item_name') }}"
                                                            type="text" id="item_name"  disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.expiry_date_and_time') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.expiry_date_and_time') }}"
                                                            type="text" id="expire_date" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="price"
                                                            class="content-label">{{ trans('coupons.enter_the_mrp') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control" minlength="1" maxlength="8"
                                                            placeholder="{{ trans('coupons.enter_the_mrp') }}" name="mrp"
                                                            type="number"
                                                            value="{{ old('mrp') ? old('mrp') : '0' }}" required
                                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                            id="price">
                                                        @if ($errors->has('mrp'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('mrp  ') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.enter_the_sale_price') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.enter_the_sale_price') }}" name="sale_price"
                                                            type="text" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.description') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <textarea class="form-control" id="description" name="description" type="text"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image"
                                                            class="content-label">{{ trans('coupons.listing_image') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="file"  class="filepond_img" id="image" name="image" accept="image/png, image/jpeg, image/gif" required >
                                                    </div>
                                                    <label id="image-error" for="image"></label>
                                                    <strong class="help-block alert-danger">
                                                        {{ @$errors->first('image') }}
                                                    </strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image"
                                                            class="content-label">{{ trans('coupons.cover_image') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="file" class="filepond_img2" multiple  name="cover_image[]" required>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button id="edit_btn" type="submit"
                                                class="btn btn-success btn-fill btn-wd">{{ trans('common.submit') }}</button>
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
        $(function () {
            $("ul.nav-pills li:first").find('a:first').addClass("active");
            $(".tab-content .tab-pane:first").addClass("active");
        });

        $('#item_type').change(function() {
            $("#coupon_name").val('');
            $("#coupon_id_ssgc").val('');
            $("#discount").val('');
            $("#qty").val('');
            $("#usage").val('');
            $("#item_name").val('');
            $("#expire_date").val('')
            var item_type = $(this).val();
            var url = '{{ route('coupon_master_list', ['item_type' => ':item_type']) }}';
          
            if (item_type != null) {

                $.ajax({
                    type: "GET",
                    url: url.replace(':item_type', item_type),
                    dataType: "json",
                    success: function(res) {
                        console.log(res);
                        if (res.success == "1") {
                            $("#coupon_master_id").empty()
                            $("#coupon_master_id").append('<option value="">' +
                                '{{ trans('common.select') }}' + '</option>');
                            $.each(res.data, function(key, value) {
                                $("#coupon_master_id").append('<option value="' + value.id + '">' +
                                    value.coupon_id + ' ' + value.name + '</option>');
                            });

                        } else {
                            //  toaster.error(res.message);
                            $("#coupon_master_id").empty();
                        }
                    },
                    error: function function_name(data) {
                        // body...
                        console.log(data);

                    }
                });
            } else {
                $("#coupon_master_id").empty();
            }
        });

        $('#coupon_master_id').change(function() {
            $("#coupon_name").val('');
            $("#coupon_id_ssgc").val('');
            $("#discount").val('');
            $("#qty").val('');
            $("#usage").val('');
            $("#item_name").val('');
            $("#expire_date").val('')

            var id = $(this).val();
            var url = '{{ route('coupon_master_detail', ['id' => ':id']) }}';

            if (id != null) {
                $.ajax({
                    type: "GET",
                    url: url.replace(':id', id),
                    dataType: "json",
                    success: function(res) {
                        console.log(res);
                        if (res.success == "1") {

                            $("#coupon_name").val(res.data.name);
                            $("#coupon_id_ssgc").val(res.data.coupon_id);
                            $("#discount").val(res.data.discount);
                            $("#qty").val(res.data.quantity);
                            $("#usage").val(res.data.usage_limit);
                            $("#item_type").val(res.data.item_type);
                            $("#item_name").val(res.data.item_name);
                            $("#expire_date").val(res.data.expire_date);

                        } else {
                            //  toaster.error(res.message);
                            $("#coupon_master_id").empty();
                        }
                    },
                    error: function function_name(data) {
                        // body...
                        console.log(data);

                    }
                });
            } else {
                $("#coupon_master_id").empty();
            }
        });

        $(document).ready(function() {
            //Save coupon
            $('#createForm').submit(function(evt){
                // tinyMCE.triggerSave();
                evt.preventDefault();
                var form = $('#createForm')[0];
                var data = new FormData(form);
                // console.log(data); return false;
                $.ajax({
                        url: "{{route('coupons.store')}}",
                        data: data,
                        type: "POST",
                        processData : false,
                        contentType : false,
                        dataType : 'json',
                        beforeSend: function(xhr){
                            $('#action_btn').prop('disabled',true);
                            $('#action_btn').text("{{trans('common.submitting')}}");
                            },
                        success: function(response) {
                            console.log(response);
                            if(response.success) {
                                toastr.success(response.success);
                                setTimeout(function(){
                                    location.href =  "{{route('coupons.index')}}";
                                }, 2000);
                            } else {

                                toastr.error(response.error);
                            }
                            $('#action_btn').prop('disabled',false);
                            $('#action_btn').text("{{trans('common.submit')}}");
                        }
                });
                //console.log('data',data)
            })
        })

    </script>
    @include('layouts.admin.elements.filepond_js')
@endsection
