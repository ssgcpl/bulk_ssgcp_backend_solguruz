@extends('layouts.app')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="row flexbox-container">
                <div class="col-xl-8 col-10 d-flex justify-content-center">
                    <div class="card bg-authentication rounded-0 mb-0">
                        <div class="row m-0">
                            <div class="col-lg-6 d-lg-block d-none text-center align-self-center pl-0 pr-3 py-0">
                                <img src="{{asset('admin_assets/app-assets/images/pages/register.jpg')}}" alt="branding logo">
                            </div>
                            <div class="col-lg-6 col-12 p-0">
                                <div class="card rounded-0 mb-0 p-2">
                                    <div class="card-header pt-50 pb-1">
                                        <div class="card-title">
                                            <h4 class="mb-0">{{trans('auth.create_account')}}</h4>
                                        </div>
                                    </div>
                                    <p class="px-2">{{trans('auth.step_1')}}</p>
                                    <div class="card-content">
                                        <div class="card-body pt-0">
                                            <form  method="POST" action="{{route('register') }}">
                                                @csrf
                                                <div class="form-label-group">
                                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" placeholder="{{ trans('auth.full_name') }}"  name="full_name"  value="{{ old('full_name') }}" id="full_name" required>
                                                    <label for="full_name">{{trans('auth.full_name')}}</label>
                                                    @error('full_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                               
                                                <div class="form-label-group">
                                                    <input type="mobile_number" id="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="{{trans('auth.mobile_number')}}" name="mobile_number" value="{{ old('mobile_number') }}"  required>
                                                    <label for="mobile_number">{{trans('auth.mobile_number')}}</label>
                                                    @error('mobile_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-label-group">
                                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}"  required>
                                                    <label for="inputEmail">{{trans('auth.email')}}</label>
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-label-group">
                                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required>
                                                    <label for="password">{{trans('auth.passwords')}}</label>
                                                </div>
                                                <div class="form-label-group">
                                                    <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" name="password_confirmation" required>
                                                    <label for="password_confirmation">{{trans('auth.c_password')}}</label>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <fieldset class="checkbox">
                                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                                <input type="checkbox" name='is_terms' checked required style="width: 20px">
                                                                <span class="vs-checkbox">
                                                                    <span class="vs-checkbox--check">
                                                                        <i class="vs-icon feather icon-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="">I accept the <a href="#">terms & conditions</a>.</span>
                                                            </div>

                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <a href="{{route('login')}}" class="btn btn-outline-primary float-left btn-inline mb-50">{{trans('auth.login')}}</a>
                                                <button type="submit" class="btn btn-primary float-right btn-inline mb-50">{{trans('auth.register')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection
