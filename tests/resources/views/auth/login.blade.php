@extends('layouts.app')

@section('content')
<!-- BEGIN: Content-->
<style type="text/css">
    .logo { max-width: 300px; }
    /* .has-icon-left .form-control-position i { top: 10px; } */
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
       
        <div class="content-body">
            <section class="row flexbox-container">
                <div class="col-xl-7 col-11 d-flex justify-content-center">
                    <div class="card bg-authentication rounded-0 mb-0 w-100">
                        <div class="row m-0">
                            <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                <img class="logo" src="{{asset('admin_signin_logo.svg')}}" alt="branding logo">
                            </div>
                            <div class="col-lg-6 col-12 p-0">

                                <div class="card rounded-0 mb-0 px-2">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="mb-0">{{trans('auth.login')}}</h4>
                                        </div>
                                    </div>

                                    <p class="px-2">{{trans('auth.welcome_back')}}</p>

                                    <div class="card-content">
                                         
                                        <div class="card-body pt-1">
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
                                            <br>
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                    <input type="email" class="form-control" id="email" name = "email" value="{{ old('email') }}" placeholder="{{trans('auth.email_address')}}" required>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-user"></i>
                                                    </div>
                                                    <label for="user-name">{{trans('auth.email_address')}}</label>
                                                    @error('email')
                                                        <!-- <span class="invalid-feedback" role="alert">
                                                            <strong>{{$message }}</strong>
                                                        </span> -->
                                                    @enderror
                                                </fieldset>
                                                <fieldset class="form-label-group position-relative has-icon-left">
                                                    <input type="password" class="form-control " id="password" placeholder="Password" name="password" required>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-lock"></i>
                                                    </div>
                                                    <label for="user-password">{{trans('auth.passwords')}}</label>
                                                    @error('password')
                                                        <!-- <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span> -->
                                                    @enderror
                                                </fieldset>
                                                <div class="form-group d-flex justify-content-between align-items-center">
                                                    <div class="text-left">
                                                      <fieldset class="checkbox">
                                                          <div class="vs-checkbox-con vs-checkbox-primary">
                                                              <input type="checkbox" checked name="remember" value="{{old('remember')}}">
                                                              <span class="vs-checkbox">
                                                                  <span class="vs-checkbox--check">
                                                                      <i class="vs-icon feather icon-check"></i>
                                                                  </span>
                                                              </span>
                                                              <span class=""> {{trans('auth.remember_me')}}</span>
                                                          </div>
                                                      </fieldset>
                                                    </div>
                                                    @if (Route::has('password.request'))
                                                    <div class="text-right"><a href="{{ route('password.request') }}" class="card-link">{{trans('auth.forgot_password')}}</a></div>
                                                    @endif
                                                </div>
                                                <!-- <a href="{{route('register')}}" class="btn btn-outline-primary float-left btn-inline">{{trans('auth.register')}}</a> -->
                                                <button type="submit" class="btn btn-primary float-right btn-inline"> {{trans('auth.login')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="login-footer">
                                        <div class="divider">
                                            <!-- <div class="divider-text"></div> -->
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
