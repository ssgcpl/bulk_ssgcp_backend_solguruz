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
                    <h2 class="content-header-title float-left mb-0">{{trans('stocks.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('stocks.index')}}?prod_id={{$_REQUEST['prod_id']}}">{{ trans('stocks.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('stocks.add_new') }}
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
              {{ trans('stocks.details')}}
              </h4>
              <a href="{{route('stocks.index')}}?prod_id={{$_REQUEST['prod_id']}}" class="btn btn-success">
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
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="pof_no" class="content-label">{{ trans('stocks.pof_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('pof_no') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks.pof_no')}}" name="pof_no" type="text" value="{{$stock->pof_no}}" disabled>
                                @if ($errors->has('pof_no')) 
                                  <strong class="invalid-feedback">{{ $errors->first('pof_no') }}</strong>
                                @endif
                              </div>  
                      </div>
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="pof_qty" class="content-label">{{ trans('stocks.pof_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('pof_qty') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks.pof_qty')}}" name="pof_qty" type="text" value="{{$stock->pof_qty}}" disabled>
                                @if ($errors->has('pof_qty')) 
                                  <strong class="invalid-feedback">{{ $errors->first('pof_qty') }}</strong>
                                @endif
                              </div>  
                      </div>
                     </div>
                   
                      <div class="row">
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="ecm_no" class="content-label">{{ trans('stocks.ecm_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('ecm_no') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks.ecm_no')}}" name="ecm_no" type="text" value="{{$stock->ecm_no}}" disabled>
                                @if ($errors->has('ecm_no')) 
                                  <strong class="invalid-feedback">{{ $errors->first('ecm_no') }}</strong>
                                @endif
                              </div>  
                      </div>
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="ecm_qty" class="content-label">{{ trans('stocks.ecm_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('ecm_qty') ? is-invalid : ''  @enderror" placeholder="{{trans('stocks.ecm_qty')}}" name="ecm_qty" type="text" value="{{$stock->ecm_qty}}" disabled>
                                @if ($errors->has('ecm_qty')) 
                                  <strong class="invalid-feedback">{{ $errors->first('ecm_qty') }}</strong>
                                @endif
                              </div>  
                      </div>
                     </div>
                     <hr/>
                     <!--  <div class="row"><div class="col-md-12"><span class="add_new_gro text-right"><i class="fa fa-plus"></i> Add More</span></div></div> -->
                      <div id="gro_div">
                        @foreach($stock_gro as $sg)
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group ">
                                <label for="gro_no" class="content-label">{{ trans('stocks.gro_no') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('gro_no') ? is-invalid : ''  @enderror"  placeholder="{{trans('stocks.gro_no')}}" name="gro_no[]" type="text" value="{{$sg->gro_no}}" disabled="">
                                @if ($errors->has('gro_no')) 
                                  <strong class="invalid-feedback">{{ $errors->first('gro_no') }}</strong>
                                @endif
                              </div>  
                            </div>

                            <div class="col-md-6">
                              <div class="form-group ">
                                <label for="gro_qty" class="content-label">{{ trans('stocks.gro_qty') }}<span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control @error('gro_qty') ? is-invalid : ''  @enderror"  placeholder="{{trans('stocks.gro_qty')}}" name="gro_qty[]" type="text" value="{{$sg->gro_qty}}" disabled>
                                @if ($errors->has('gro_qty')) 
                                  <strong class="invalid-feedback">{{ $errors->first('gro_qty') }}</strong>
                                @endif
                              </div>  
                            </div>
                     </div>   
                     @endforeach
                     
                  </div>
                  
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
  $(document).ready(function() {

  });
</script>


@endsection