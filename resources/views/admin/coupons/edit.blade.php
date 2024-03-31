@extends('layouts.admin.app')
@section('css')
    <style type="text/css">
        .cover_images {
            width: 40%;
            margin-bottom: 10px;
        }
       
    </style>
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
                            <h2 class="content-header-title float-left mb-0">{{ trans('coupons.heading') }} </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('common.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('coupons.index') }}">{{ trans('coupons.plural') }}</a></li>
                                    <li class="breadcrumb-item active">{{ trans('coupons.update') }}
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
                                @can('product-list')
                                    <a href="{{ route('coupons.index') }}" class="btn btn-success">
                                        <i class="fa fa-arrow-left"></i>
                                        {{ trans('common.back') }}
                                    </a>
                                @endcan
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form method="POST" id="createForm" accept-charset="UTF-8"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="categories"
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
                                                                                    <ul>{{-- <li> --}}
                                                                                        <input type="checkbox"
                                                                                            id="category[]"
                                                                                            name="category[]"
                                                                                            value="{{ $category->id }}"class="list"
                                                                                            {{ in_array($category->id, $coupon_category_ids) || (is_array(old('list')) && in_array($category->id, old('list'))) ? 'checked' : '' }}>
                                                                                        {{ $category->category_name }}
                                                                                        @include(
                                                                                            'admin.coupons.manage_checkbox',
                                                                                            [
                                                                                                'childs' =>
                                                                                                    $category->sub_category,
                                                                                                'category' => $coupon_category_ids,
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
                                                        <label for="item_type"
                                                            class="content-label">{{ trans('coupons.item_type') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.item_type') }}" id="item_type"
                                                            type="text" value="{{ $coupon_master->item_type}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="coupon_name"
                                                            class="content-label">{{ trans('coupons.coupon_name') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.coupon_name') }}"
                                                            id="coupon_name" type="text" value="{{ $coupon_master->name}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="types"
                                                              class="content-label">{{trans('coupons.business_category')}}<span
                                                                  class="text-danger custom_asterisk">*</span></label><br>
                                                        <select id="business_category_id" class ="form-control" name='business_category_id' required>
                                                            @foreach($business_categories as $bc)
                                                                <option value="{{$bc->id}}" {{@($bc->id == $coupons->business_category_id)?'selected':''}}>{{ucfirst($bc->category_name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.coupon_id_ssgc1') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.coupon_id_ssgc1') }}"
                                                            type="text" value="{{ $coupon_master->coupon_id}}" id="coupon_id_ssgc" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.discount_in_percent') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.discount_in_percent') }}"
                                                            type="text" value="{{ $coupon_master->discount}}" id="discount" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.qty') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.qty') }}" type="text"
                                                            id="qty" value="{{ $coupon_master->quantity}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.count_usage') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.count_usage') }}"
                                                            type="text" value="{{ $coupon_master->usage_limit}}" id="usage" disabled>

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
                                                            type="text" value="{{ $coupon_master->item_name}}" id="item_name" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content:"
                                                            class="content-label">{{ trans('coupons.expiry_date_and_time') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <input class="form-control"
                                                            placeholder="{{ trans('coupons.expiry_date_and_time') }}"
                                                            type="text" value="{{ $coupon_master->end_date->format('d-m-Y h:i A');}}" id="expire_date" disabled>
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
                                                            placeholder="{{ trans('coupons.enter_the_mrp') }}"
                                                            name="mrp" type="number"
                                                            value="{{ $coupons->mrp }}" required
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
                                                            placeholder="{{ trans('coupons.enter_the_sale_price') }}"
                                                            name="sale_price" value="{{ $coupons->sale_price }}" type="text" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content"
                                                            class="content-label">{{ trans('coupons.description') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <textarea class="form-control" id="description" name="description" type="text">{{ $coupons->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label for="image" class="content-label">{{trans('coupons.listing_image')}}<span class="text-danger custom_asterisk">*</span></label><br>
                                                    <input type="file" class="filepond_img" id="image" name="image">
                                                    <img src="{{asset($coupons->image)}}" class="cover_images">

                                                    </div>
                                                    <label id="image-error" for="image"></label>
                                                    <strong class="help-block alert-danger">
                                                    {{ @$errors->first('image') }}
                                                    </strong>
                                                </div>
                                                <div class="col-md-6" id="coupon_cover_images">
                                                    <div class="form-group">
                                                        <label id="image-error"
                                                            for="image">{{ trans('coupons.cover_images') }} <span
                                                                class="text-danger custom_asterisk">*</span><br/></label>
                                                        <input class="filepond_img2"
                                                            type="file" multiple name="cover_images[]" >
                                                        @foreach($coupons->cover_images as $cvr_img)
                                                        <a href="javascript:void(0)" title="Remove"><img src="{{asset($cvr_img->cover_image)}}" class="cover_images"><span class="remove_cvr_img" id="{{$cvr_img->id}}"  ><i class="fa fa-window-close"></i></span></a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">

                                            <button id="action_btn"
                                                class="btn btn-info btn-fill btn-wd">{{ trans('common.submit') }}</button>

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
        /*$(function() {
            $("ul.nav-pills li:first").find('a:first').addClass("active");
            $(".tab-content .tab-pane:first").addClass("active");
        });*/
        $(document).ready(function() {
            var first = $('.tab-pane input:checkbox:checked').closest('.tab-pane').attr('id');
            $('.nav-pills a[href="#'+first+ '"]').tab('show');
            //Save Categories

                $('#createForm').submit(function(evt) {
                    // tinyMCE.triggerSave();
                    evt.preventDefault();
                    var form = $('#createForm')[0];
                    var formData = new FormData(form);
                    formData.append('_method', 'put');
                    var route = "{{ route('coupons.update',':id') }}";
                    route = route.replace(':id', '{{ $coupons->id }}');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    $.ajax({
                        url: route,
                        data: formData,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            $('#action_btn').prop('disabled', true);
                            $('#action_btn').text("{{ trans('common.submitting') }}");
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                toastr.success(response.success);
                                setTimeout(function() {
                                    location.href = "{{ route('coupons.index') }}";
                                }, 2000);
                            } else {
                                toastr.error(response.error);
                            }
                            $('#action_btn').prop('disabled', false);
                            $('#action_btn').text("{{ trans('common.submit') }}");
                        }
                    });
                    //console.log('data',data)
                })


            $('.remove_cvr_img').click(function(evt) {
                evt.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: '{{ route('remove_coupon_cover_image') }}',
                    data: {
                        "id": $(this).attr('id')
                    },
                    type: "POST",
                    async: false,
                    beforeSend: function(xhr) {
                        $('.remove_cvr_img').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.error);
                        } else {
                            location.reload();
                        }

                    }
                });
            })
        })
    </script>
    @include('layouts.admin.elements.filepond_js')
@endsection
