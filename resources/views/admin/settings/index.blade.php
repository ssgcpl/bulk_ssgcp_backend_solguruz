@extends('layouts.admin.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ trans('settings.list') }} </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('common.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ trans('settings.settings') }}</li>
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
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('settings.update') }}" method="POST" id="setting"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <h3>{{ trans('settings.app_setting') }}</h3>
                                        <br />
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.app_name') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" class="form-control" name="app_name"
                                                            placeholder="{{ trans('settings.app_name') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ old('app_name') ? old('app_name') : $settings['app_name'] }}"
                                                            required maxlength="20">

                                                        @if ($errors->has('app_name'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('app_name') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.contact_email') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="email"
                                                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                                                            title="Please enter valid email." class="form-control"
                                                            name="contact_email"
                                                            placeholder="{{ trans('settings.contact_email') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['contact_email'] }}" required
                                                            maxlength="50">

                                                        @if ($errors->has('contact_email'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('contact_email') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.customer_care_no') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">+91</span>
                                                            </div>
                                                            <input type="tel" pattern="\d*" minlength="10"
                                                                maxlength="10" title="You can only enter numbers."
                                                                class="form-control" name="customer_care_no"
                                                                placeholder="{{ trans('settings.customer_care_no') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['customer_care_no'] }}" required>
                                                        </div>

                                                        @if ($errors->has('customer_care_no'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('customer_care_no') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.contact_address') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <div class="input-group">
                                                            <input type="text" 
                                                                class="form-control" name="contact_address"
                                                                placeholder="{{ trans('settings.contact_address') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['contact_address'] }}" required>
                                                        </div>

                                                        @if ($errors->has('contact_address'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('contact_address') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Company Logo<span
                                                                class="text-danger custom_asterisk">*</span></label>

                                                        <div class="form-group">
                                                            <input type="file" id="company_logo" name="company_logo"
                                                                class="form-control" accept=".png,.jpg,.jpeg,.gif">
                                                            @if ($errors->has('company_logo'))
                                                                <strong class="invalid-feedback">
                                                                    {{ @$errors->first('company_logo') }}
                                                                </strong>
                                                            @endif
                                                        </div>
                                                        @if ($settings['company_logo'])
                                                            <img name="company_logo" aria-describedby="basic-addon1"
                                                                src="{{ asset($settings['company_logo']) }}" width="20%"
                                                                height="20%">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Time (Working Hours)<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" class="form-control" name="working_hours"
                                                            placeholder="{{ trans('settings.working_hours') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['working_hours'] }}">

                                                        @if ($errors->has('working_hours'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('working_hours') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ trans('settings.admin_email')}}<span class="text-danger custom_asterisk">*</span></label>             
                                                      <input type="text" class="form-control" name="admin_email" placeholder="{{trans('settings.admin_email')}}" aria-describedby="basic-addon1" value="{{$settings['admin_email']}}" required>

                                                    @if ($errors->has('admin_email')) 
                                                     <strong class="help-block alert-danger">{{ $errors->first('admin_email') }}</strong>
                                                    @endif
                                                </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.currency') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" class="form-control" name="currency"
                                                            placeholder="{{ trans('settings.currency') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['currency'] }}"  disabled>

                                                        @if ($errors->has('currency'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('currency') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.currency_symbol') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" class="form-control" name="currency_symbol"
                                                            placeholder="{{ trans('settings.currency_symbol') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['currency_symbol'] }}" disabled>

                                                        @if ($errors->has('currency_symbol'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('currency_symbol') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.order_return_address') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" 
                                                            title="Please enter address." class="form-control"
                                                            name="order_return_address"
                                                            placeholder="{{ trans('settings.order_return_address') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['order_return_address'] }}" required>

                                                        @if ($errors->has('order_return_address'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('order_return_address') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.order_return_contact_number') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">+91</span>
                                                            </div>
                                                            <input type="tel" pattern="\d*" minlength="10"
                                                                maxlength="10" title="You can only enter numbers."
                                                                class="form-control" name="order_return_contact_number"
                                                                placeholder="{{ trans('settings.order_return_contact_number') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['order_return_contact_number'] }}"
                                                                required>
                                                        </div>

                                                        @if ($errors->has('order_return_contact_number'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('order_return_contact_number') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
						<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.payu_job_delay_in_seconds') }}</label>
                                                        <input type="text" class="form-control" name="payu_job_delay_in_seconds"
                                                            placeholder="{{ trans('settings.payu_job_delay_in_seconds') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['payu_job_delay_in_seconds'] }}" >

                                                        @if ($errors->has('payu_job_delay_in_seconds'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('payu_job_delay_in_seconds') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.facebook_url') }}</label>
                                                        <input type="text" class="form-control" name="facebook_url"
                                                            placeholder="{{ trans('settings.facebook_url') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['facebook_url'] }}" >

                                                        @if ($errors->has('facebook_url'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('facebook_url') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.twitter_url') }}</label>
                                                        <input type="text" class="form-control" name="twitter_url"
                                                            placeholder="{{ trans('settings.twitter_url') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['twitter_url'] }}" >

                                                        @if ($errors->has('twitter_url'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('twitter_url') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>{{ trans('settings.instagram_url') }}</label>
                                                        <input type="text" class="form-control" name="instagram_url"
                                                            placeholder="{{ trans('settings.instagram_url') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['instagram_url'] }}" >

                                                        @if ($errors->has('instagram_url'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('instagram_url') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>{{ trans('settings.telegram_url') }}</label>
                                                        <input type="text" class="form-control" name="telegram_url"
                                                            placeholder="{{ trans('settings.telegram_url') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['telegram_url'] }}" >

                                                        @if ($errors->has('telegram_url'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('telegram_url') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                      <div class="col-md-6">
                                                <div class="form-group">
                                                        <label>{{ trans('settings.whatsapp_url') }}</label>
                                                        <input type="text" class="form-control" name="whatsapp_url"
                                                            placeholder="{{ trans('settings.whatsapp_url') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['whatsapp_url'] }}" >

                                                        @if ($errors->has('whatsapp_url'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('whatsapp_url') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                {{-- comments wishlist  --}}
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.wish_return_days_limit') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" pattern=""
                                                            title="Please enter wish return day limit." class="form-control"
                                                            name="wish_return_days_limit"
                                                            placeholder="{{ trans('settings.wish_return_days_limit') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['wish_return_days_limit'] }}" required
                                                            maxlength="3">

                                                        @if ($errors->has('wish_return_days_limit'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('wish_return_days_limit') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('settings.wishlist_days_limit') }}<span
                                                                class="text-danger custom_asterisk">*</span></label>
                                                        <input type="text" pattern="\d*"
                                                            title="Please enter address." class="form-control"
                                                            name="wishlist_days_limit"
                                                            placeholder="{{ trans('settings.wishlist_days_limit') }}"
                                                            aria-describedby="basic-addon1"
                                                            value="{{ $settings['wishlist_days_limit'] }}" required
                                                            maxlength="3">

                                                        @if ($errors->has('wishlist_days_limit'))
                                                            <strong
                                                                class="help-block alert-danger">{{ $errors->first('wishlist_days_limit') }}</strong>
                                                        @endif
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>Price Management</h3>
                                            <br />
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Delivery Charges Applicable<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <div
                                                                class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                                                <input type="checkbox"class="status custom-control-input"
                                                                    @if ($settings['is_delivery_charges_applicable'] == 'on') checked @endif
                                                                    id="is_delivery_charges_applicable"
                                                                    name="is_delivery_charges_applicable"
                                                                    data_id="{{ $settings['is_delivery_charges_applicable'] }}"><label
                                                                    class="custom-control-label"
                                                                    for="is_delivery_charges_applicable"></label></div>
                                                            @if ($errors->has('is_delivery_charges_applicable'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('is_delivery_charges_applicable') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="delivery_charges_div">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Amount Limit<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="amount_limit" placeholder="Amount Limit"
                                                                value="{{ old('amount_limit') ? old('amount_limit') : $settings['amount_limit'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('amount_limit'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('amount_limit') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Delivery Charge<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="delivery_charges" placeholder="Delivery Charge"
                                                                value="{{ old('delivery_charges') ? old('delivery_charges') : $settings['delivery_charges'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('delivery_charges'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('delivery_charges') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>Points Management</h3>
                                            <br />
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Referrer Points<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="refer_points" placeholder="Refer Points"
                                                                value="{{ old('refer_points') ? old('refer_points') : $settings['refer_points'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('refer_points'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('refer_points') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Referred Points<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="referred_points" placeholder="Referred Points"
                                                                value="{{ old('referred_points') ? old('referred_points') : $settings['referred_points'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('referred_points'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('referred_points') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Welcome Points<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="welcome_points" placeholder="Welcome Points"
                                                                value="{{ old('welcome_points') ? old('welcome_points') : $settings['welcome_points'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('welcome_points'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('welcome_points') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Wishlist Points<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="wishlist_points" placeholder="Welcome Points"
                                                                value="{{ old('wishlist_points') ? old('wishlist_points') : $settings['wishlist_points'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('wishlist_points'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('wishlist_points') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Wish Return Points<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="wish_return_points" placeholder="Welcome Points"
                                                                value="{{ old('wish_return_points') ? old('wish_return_points') : $settings['wish_return_points'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('wish_return_points'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('wish_return_points') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>Version Control</h3>
                                            <br/>
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Android<span class="text-danger custom_asterisk">*</span></label>             
                                                        <input type="text" class="form-control number_decimal" name="android_app_version" placeholder="Android" value="{{ old('android_app_version') ? old('android_app_version') : $settings['android_app_version']}}" required maxlength="100">
                                                        @if ($errors->has('android_app_version')) 
                                                        <strong class="help-block alert-danger">{{ $errors->first('android_app_version') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Update Type<span class="text-danger custom_asterisk">*</span></label>             
                                                        <select name="android_app_version_update_type" class="form-control" required>
                                                        <option value="soft" @if($settings['android_app_version_update_type'] == 'soft') selected @endif>Soft</option>
                                                        <option value="hard" @if($settings['android_app_version_update_type'] == 'hard') selected @endif>Hard</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Apple<span class="text-danger custom_asterisk">*</span></label>             
                                                        <input type="text" class="form-control number_decimal" name="apple_app_version" placeholder="Apple" value="{{ old('apple_app_version') ? old('apple_app_version') : $settings['apple_app_version']}}" required maxlength="100">
                                                        @if ($errors->has('apple_app_version')) 
                                                        <strong class="help-block alert-danger">{{ $errors->first('apple_app_version') }}</strong>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Update Type<span class="text-danger custom_asterisk">*</span></label>             
                                                        <select name="apple_app_version_update_type" class="form-control" required>
                                                        <option value="soft" @if($settings['apple_app_version_update_type'] == 'soft') selected @endif>Soft</option>
                                                        <option value="hard" @if($settings['apple_app_version_update_type'] == 'hard') selected @endif>Hard</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>{{ trans('settings.email_setting') }}</h3>
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_username') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="email_username"
                                                                placeholder="{{ trans('settings.email_username') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['email_username'] }}" required>

                                                            @if ($errors->has('email_username'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_username') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_password') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="password" class="form-control"
                                                                name="email_password"
                                                                placeholder="{{ trans('settings.email_password') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['email_password'] }}" required>

                                                            @if ($errors->has('email_password'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_password') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_host') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control" name="email_host"
                                                                placeholder="{{ trans('settings.email_host') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['email_host'] }}" required>

                                                            @if ($errors->has('email_host'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_host') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_port') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control" name="email_port"
                                                                placeholder="{{ trans('settings.email_port') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['email_port'] }}" required>

                                                            @if ($errors->has('email_port'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_port') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_encryption') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <select class="form-control" name="email_encryption">
                                                                <option value="tls"
                                                                    @if ($settings['email_encryption'] == 'tls') selected @endif>TLS
                                                                </option>
                                                                <option value="ssl"
                                                                    @if ($settings['email_encryption'] == 'ssl') selected @endif>SSL
                                                                </option>
                                                            </select>


                                                            @if ($errors->has('email_encryption'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_host') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.email_from_name') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="email_from_name"
                                                                placeholder="{{ trans('settings.email_from_name') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['email_from_name'] }}" required>

                                                            @if ($errors->has('email_from_name'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('email_from_name') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>SMS Gateway</h3>
                                            <br />
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway URL<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_url" placeholder="SMS Gateway URL"
                                                                value="{{ old('sms_gateway_url') ? old('sms_gateway_url') : $settings['sms_gateway_url'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_url'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_url') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway Auth-Key<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_authkey"
                                                                placeholder="SMS Gateway Auth-Key"
                                                                value="{{ old('sms_gateway_authkey') ? old('sms_gateway_authkey') : $settings['sms_gateway_authkey'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_authkey'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_authkey') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway Sender<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_sender" placeholder="SMS Gateway Sender"
                                                                value="{{ old('sms_gateway_sender') ? old('sms_gateway_sender') : $settings['sms_gateway_sender'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_sender'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_sender') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway OTP Flow ID<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_otp_flow_id"
                                                                placeholder="SMS Gateway OTP Flow ID"
                                                                value="{{ old('sms_gateway_otp_flow_id') ? old('sms_gateway_otp_flow_id') : $settings['sms_gateway_otp_flow_id'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_otp_flow_id'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_otp_flow_id') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway Order Placed Flow ID<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_order_placed_flow_id"
                                                                placeholder="SMS Gateway Order Placed Flow ID"
                                                                value="{{ old('sms_gateway_order_placed_flow_id') ? old('sms_gateway_order_placed_flow_id') : $settings['sms_gateway_order_placed_flow_id'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_order_placed_flow_id'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_order_placed_flow_id') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SMS Gateway Order Delivered Flow ID<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="sms_gateway_order_delivered_flow_id"
                                                                placeholder="SMS Gateway Order Delivered Flow ID"
                                                                value="{{ old('sms_gateway_order_delivered_flow_id') ? old('sms_gateway_order_delivered_flow_id') : $settings['sms_gateway_order_delivered_flow_id'] }}"
                                                                required maxlength="100">
                                                            @if ($errors->has('sms_gateway_order_delivered_flow_id'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('sms_gateway_order_delivered_flow_id') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>{{ trans('settings.ccavenue_setting') }}</h3>
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_merchant_id') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="ccavenue_merchant_id"
                                                                placeholder="{{ trans('settings.ccavenue_merchant_id') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['ccavenue_merchant_id'] }}" required
                                                                maxlength="10">

                                                            @if ($errors->has('ccavenue_merchant_id'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('ccavenue_merchant_id') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_working_key') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="ccavenue_working_key"
                                                                placeholder="{{ trans('settings.ccavenue_working_key') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['ccavenue_working_key'] }}" required
                                                                >

                                                            @if ($errors->has('ccavenue_working_key'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('ccavenue_working_key') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_access_code') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="ccavenue_access_code"
                                                                placeholder="{{ trans('settings.ccavenue_access_code') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['ccavenue_access_code'] }}" required
                                                                maxlength="20">

                                                            @if ($errors->has('ccavenue_access_code'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('ccavenue_access_code') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_sandbox_url') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="ccavenue_sandbox_url"
                                                                placeholder="{{ trans('settings.ccavenue_sandbox_url') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['ccavenue_sandbox_url'] }}" required
                                                                maxlength="300">

                                                            @if ($errors->has('ccavenue_sandbox_url'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('ccavenue_sandbox_url') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_production_url') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="ccavenue_production_url"
                                                                placeholder="{{ trans('settings.ccavenue_production_url') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['ccavenue_production_url'] }}"
                                                                required maxlength="300">

                                                            @if ($errors->has('ccavenue_production_url'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('ccavenue_production_url') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.ccavenue_mode') }}</label>
                                                            <select name="ccavenue_mode" id="ccavenue_mode"
                                                                class="form-control">

                                                                <option value="sandbox"
                                                                    @if ($settings['ccavenue_mode'] == 'sandbox') selected @endif>
                                                                    {{ trans('settings.sandbox') }}</option>
                                                                <option value="production"
                                                                    @if ($settings['ccavenue_mode'] == 'production') selected @endif>
                                                                    {{ trans('settings.live') }}</option>

                                                            </select>

                                                            @error('ccavenue_mode')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr style="border-top: 3px solid #434345;">
                                            <h3>{{ trans('settings.payu_setting') }}</h3>
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_sandbox_key') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_sandbox_key"
                                                                placeholder="{{ trans('settings.payu_sandbox_key') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_sandbox_key'] }}" required
                                                                maxlength="10">

                                                            @if ($errors->has('payu_sandbox_key'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_sandbox_key') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_sandbox_salt') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_sandbox_salt"
                                                                placeholder="{{ trans('settings.payu_sandbox_salt') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_sandbox_salt'] }}" required
                                                                maxlength="10">

                                                            @if ($errors->has('payu_sandbox_salt'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_sandbox_salt') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_live_key') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_live_key"
                                                                placeholder="{{ trans('settings.payu_live_key') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_live_key'] }}" required
                                                                maxlength="10">

                                                            @if ($errors->has('payu_live_key'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_live_key') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_live_salt') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_live_salt"
                                                                placeholder="{{ trans('settings.payu_live_salt') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_live_salt'] }}" required
                                                                maxlength="10">

                                                            @if ($errors->has('payu_live_salt'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_live_salt') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_sandbox_url') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_sandbox_url"
                                                                placeholder="{{ trans('settings.payu_sandbox_url') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_sandbox_url'] }}" required
                                                                maxlength="300">

                                                            @if ($errors->has('payu_sandbox_url'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_sandbox_url') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_production_url') }}<span
                                                                    class="text-danger custom_asterisk">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="payu_production_url"
                                                                placeholder="{{ trans('settings.payu_production_url') }}"
                                                                aria-describedby="basic-addon1"
                                                                value="{{ $settings['payu_production_url'] }}" required
                                                                maxlength="300">

                                                            @if ($errors->has('payu_production_url'))
                                                                <strong
                                                                    class="help-block alert-danger">{{ $errors->first('payu_production_url') }}</strong>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>{{ trans('settings.payu_mode') }}</label>
                                                            <select name="payu_mode" id="payu_mode" class="form-control">

                                                                <option value="sandbox"
                                                                    @if ($settings['payu_mode'] == 'sandbox') selected @endif>
                                                                    {{ trans('settings.sandbox') }}</option>
                                                                <option value="production"
                                                                    @if ($settings['payu_mode'] == 'production') selected @endif>
                                                                    {{ trans('settings.live') }}</option>

                                                            </select>

                                                            @error('payu_mode')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button id="edit_btn" type="submit"
                                                    class="btn btn-success btn-fill btn-wd">{{ trans('settings.save') }}</button>
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.12.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.12.0/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
            var val = $("#is_delivery_charges_applicable").attr('data_id');
            if (val == 'off') {
                $("#delivery_charges_div").hide();
            }
            $(document).on('click', '.status', function() {
                if ($(".status").is(':checked')) {
                    $("#delivery_charges_div").show();
                } else {
                    $("#delivery_charges_div").hide();
                }
            })


        });
    </script>
@endsection
