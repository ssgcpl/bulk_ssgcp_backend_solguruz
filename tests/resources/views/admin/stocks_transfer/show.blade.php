@extends('layouts.admin.app')
@section('content')
<style type="text/css">
  .add_new_gro {cursor: pointer;}
  .form-group label {width:100%;}
  .text-right { float: right; }
  .close_gro_div {float: right; position: relative; padding: 2px; top: 1px}
</style>

<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('stocks_transfer.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('stocks_transfer.index')}}?prod_id={{$_REQUEST['prod_id']}}">{{ trans('stocks_transfer.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('stocks_transfer.details') }}
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
              {{ trans('stocks_transfer.details')}}
              </h4>
              <a href="{{route('stocks_transfer.index')}}?prod_id={{$_REQUEST['prod_id']}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">  
                <form method="POST" id="createForm" accept-charset="UTF-8">
                  @csrf
                   <input type="hidden" name="product_id" id="product_id" value="{{$_REQUEST['prod_id']}}">
                  <div class="modal-body">
                     <div class="row">
                       @if($stock_transfer->gto_in_no !='' && $stock_transfer->gto_in_qty !='')
                       
                          <div class="col-md-4">
                              <div class="form-group">
                                  <input name="type" class="transfer_type" value="gto_in" id="gto_in_gro" type="radio" checked disabled=""> {{ trans('stocks_transfer.gto_in_gro') }}
                                  &nbsp;&nbsp;
                                </div>
                              </div>
                              @endif
                             @if($stock_transfer->gto_out_no !='' && $stock_transfer->gto_out_qty !='')
                            <div class="col-md-4">
                              <div class="form-group">
                                  <input name="type"class="transfer_type" value="gto_out" id="gto_out_return" type="radio" checked disabled> {{ trans('stocks_transfer.gto_out_return') }}
                              </div>
                          </div>
                          @endif
                           @if($stock_transfer->scrap_no !='' && $stock_transfer->scrap_qty !='')
                          <div class="col-md-4">
                              <div class="form-group">
                                 
                                  <input name="type" class="transfer_type" value="scrap_return" id="scrap_return" type="radio" checked disabled> {{ trans('stocks_transfer.scrap_return') }}
                              </div>
                          </div>
                          @endif
                        </div>
                        @if($stock_transfer->gto_in_no !='' && $stock_transfer->gto_in_qty !='')
                        <div class="row" id="gto_in_div">
                          <div class="col-md-6">
                                  <div class="form-group ">
                                    <label for="gto_in_no" class="content-label">{{ trans('stocks_transfer.gto_in_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                    <input class="form-control @error('gto_in_no') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.gto_in_no')}}" name="gto_in_no" type="number" value="{{$stock_transfer->gto_in_no}}" disabled>
                                    @if ($errors->has('gto_in_no')) 
                                      <strong class="invalid-feedback">{{ $errors->first('gto_in_no') }}</strong>
                                    @endif
                                  </div>  
                          </div>
                          <div class="col-md-6">
                                  <div class="form-group ">
                                    <label for="gto_in_qty" class="content-label">{{ trans('stocks_transfer.gto_in_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                    <input class="form-control @error('gto_in_qty') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.gto_in_qty')}}" name="gto_in_qty" id="gto_in_qty" type="number" value="{{$stock_transfer->gto_in_qty}}" disabled>
                                    @if ($errors->has('gto_in_qty')) 
                                      <strong class="invalid-feedback">{{ $errors->first('gto_in_qty') }}</strong>
                                    @endif
                                  </div>  
                          </div>
                        </div>
                        @endif
                    @if($stock_transfer->gto_out_no !='' && $stock_transfer->gto_out_qty !='')
                    <div class="row" id="gto_out_div">
                        <div class="col-md-6">
                                <div class="form-group ">
                                  <label for="gto_out_no" class="content-label">{{ trans('stocks_transfer.gto_out_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                  <input class="form-control @error('gto_out_no') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.gto_out_no')}}" name="gto_out_no" id="gto_out_no" type="number" value="{{$stock_transfer->gto_out_no}}" disabled>
                                  @if ($errors->has('gto_out_no')) 
                                    <strong class="invalid-feedback">{{ $errors->first('gto_out_no') }}</strong>
                                  @endif
                                </div>  
                        </div>
                        <div class="col-md-6">
                                <div class="form-group ">
                                  <label for="gto_out_qty" class="content-label">{{ trans('stocks_transfer.gto_out_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                  <input class="form-control @error('gto_out_qty') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.gto_out_qty')}}" name="gto_out_qty" id="gto_out_qty" type="number" value="{{$stock_transfer->gto_out_qty}}" disabled>
                                  @if ($errors->has('gto_out_qty')) 
                                    <strong class="invalid-feedback">{{ $errors->first('gto_out_qty') }}</strong>
                                  @endif
                                </div>  
                        </div>
                    </div>
                    @endif
                  
                    @if($stock_transfer->scrap_no !='' && $stock_transfer->scrap_qty !='')
                    <div class="row" id="scrap_div">
                        <div class="col-md-6">
                                <div class="form-group ">
                                  <label for="scrap_no" class="content-label">{{ trans('stocks_transfer.scrap_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                  <input class="form-control @error('scrap_no') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.scrap_no')}}" name="scrap_no" id="scrap_no" type="number" value="{{$stock_transfer->scrap_no}}" disabled>
                                  @if ($errors->has('scrap_no')) 
                                    <strong class="invalid-feedback">{{ $errors->first('scrap_no') }}</strong>
                                  @endif
                                </div>  
                        </div>
                        <div class="col-md-6">
                                <div class="form-group ">
                                  <label for="scrap_qty" class="content-label">{{ trans('stocks_transfer.scrap_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                  <input class="form-control @error('scrap_qty') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks_transfer.scrap_qty')}}" name="scrap_qty" type="number" value="{{$stock_transfer->scrap_qty}}" disabled>
                                  @if ($errors->has('scrap_qty')) 
                                    <strong class="invalid-feedback">{{ $errors->first('scrap_qty') }}</strong>
                                  @endif
                                </div>  
                        </div>
                    </div>
                    @endif
                  
                 <!--  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success btn-fill btn-wd">{{trans('common.submit')}}</button>
                  </div> -->
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

</script>


@endsection