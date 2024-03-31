@extends('layouts.admin.app')

@section('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/extensions/tether-theme-arrows.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/extensions/tether.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/extensions/shepherd-theme-default.css')}}">
@endsection

@section('css')
@endsection

@section('content')
<!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Analytics Start -->
            <section id="dashboard-analytics">
              <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                          <h2 class="content-header-title float-left mb-0">{{trans('dashboard.total_statistics')}} </h2>
                        </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-info p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                      <a href="{{route('customers.index')}}" title="customers">  <i class="feather text-info icon-users font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_customers}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_customers')}}</p>
                                <p>
                                  Dealer: <b>{{@$total->total_dealer }}</b><br>
                                  Retailer: <b>{{@$total->total_retailer}}</b><br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-warning p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <a href="{{route('orders.index')}}" title="order_placed"> <i class="feather text-primary icon-book font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_ordered}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_ordered')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('wish_list.index')}}" title="books">
                                        <i class="feather text-primary icon-book font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_wish_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_wish_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('order_return.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_return_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_return_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('wish_list.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_return_wish_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_return_wish_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('tickets.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_ticket_raised}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_ticket_raised')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('tickets.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700">{{$total->total_customer_suggestion}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.total_suggestion_by_customer')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </section>

            <section id="dashboard-analytics">
              <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-md-5">
                          <h2 class="content-header-title float-left mb-0">{{trans('dashboard.today_statistics')}} </h2>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                            {{-- <label class="content-label">Start Date</label> --}}
                            <input id="filter_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="" value="">
                        </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-info p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                      <a href="{{route('customers.index')}}" title="customers">  <i class="feather text-info icon-users font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_customer">{{$today->today_customers}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_customers')}}</p>
                                <p>
                                  Dealer: <b id="date_dealer">{{@$today->today_dealer }}</b><br>
                                  Retailer: <b id="date_retailer">{{@$today->today_retailer}}</b><br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-warning p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                        <a href="{{route('orders.index')}}" title="order_placed"> <i class="feather text-primary icon-book font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_ordered">{{$today->today_ordered}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_ordered')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('wish_list.index')}}" title="books">
                                        <i class="feather text-primary icon-book font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_wish_ordered">{{$today->today_wish_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_wish_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('order_return.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_return_ordered">{{$today->today_return_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_return_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('wish_list.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_return_wish_ordered">{{$today->today_return_wish_order}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_return_wish_order')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('tickets.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_ticket_raised">{{$today->today_ticket_raised}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_ticket_raised')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="card text-center">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                    <div class="avatar-content">
                                       <a href="{{route('tickets.index')}}" title="tickets">
                                        <i class="feather text-danger icon-file-minus font-medium-5"></i></a>
                                    </div>
                                </div>
                                <h2 class="text-bold-700" id="date_customer_suggestion">{{$today->today_customer_suggestion}}</h2>
                                <p class="mb-0 line-ellipsis">{{trans('dashboard.today_suggestion_by_customer')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </section>
            <hr>
            <div class="row">
              <div class="col-lg-4 col-12">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between pb-0">
                          <h4 class="card-title">Normal order vs Wish order</h4>
                      </div>
                      <div class="card-content">
                          <div class="card-body py-0">
                              <div id="order-chart"></div>
                              <input type="hidden" name="normal_order" id="normal_order" value="{{$chart->normal_order}}">
                              <input type="hidden" name="wish_order" id="wish_order" value="{{$chart->wish_order}}">
                          </div>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4 col-12">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between pb-0">
                          <h4 class="card-title">Return order vs Wish return order</h4>
                      </div>
                      <div class="card-content">
                          <div class="card-body py-0">
                              <div id="return_order-chart"></div>
                              <input type="hidden" name="return_order" id="return_order" value="{{$chart->return_order}}">
                              <input type="hidden" name="wish_return_order" id="wish_return_order" value="{{$chart->return_wish_order}}">
                          </div>
                       </div>
                  </div>
              </div>

            </div>

        </div>
    </div>
  </div>
<!-- END: Content-->
@endsection
@section('js')
<script src="{{asset('admin_assets/app-assets/vendors/js/charts/apexcharts.js')}}"></script>
<script>

    $('#filter_date').datetimepicker().on('dp.change', function (event) {
        
      $date =  $('#filter_date').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
        $.ajax({
                  url: '{{route("date_wise_dashboard_data")}}',
                  data: {
                          "date_filter" : $date,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#filter_date').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                      $('#filter_date').prop('disabled',false);
                    }
                    else if (response.success){
                        // alert(response.data.today_retailer);
                        $('#date_customer').text(response.data.today_customers)
                        $('#date_retailer').text(response.data.today_retailer)
                        $('#date_dealer').text(response.data.today_dealer)
                        
                        $('#date_ordered').text(response.data.today_ordered)
                        $('#date_wish_ordered').text(response.data.today_wish_order)
                        $('#date_return_ordered').text(response.data.today_return_order)
                        $('#date_return_wish_ordered').text(response.data.today_return_wish_order)
                        $('#date_ticket_raised').text(response.data.today_ticket_raised)
                        $('#date_customer_suggestion').text(response.data.today_customer_suggestion)

                        $('#filter_date').prop('disabled',false);
                    //  location.reload();
                    }

                  }
          });
    })


// chart script
//Get the context of the Chart canvas element we want to select
  var $primary = '#7367F0';
  var $danger = '#EA5455';
  var $warning = '#FF9F43';
  var $info = '#00cfe8';
  var $success = '#00db89';
  var $primary_light = '#9c8cfc';
  var $warning_light = '#FFC085';
  var $danger_light = '#f29292';
  var $info_light = '#1edec5';
  var $strok_color = '#b9c3cd';
  var $label_color = '#e7eef7';
  var $purple = '#df87f2';
  var $white = '#fff';

  var normal_order = (document.getElementById('normal_order').value);
  var wish_order = (document.getElementById("wish_order").value);


  // apex chart
    var orderChartoptions = {
        chart: {
            type: 'pie',
            height: 325,
            dropShadow: {
                enabled: false,
                blur: 5,
                left: 1,
                top: 1,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        labels: ['Normal Order', 'Wish Order'],
        series: [parseInt(normal_order),parseInt(wish_order)],
        dataLabels: {
            enabled: false
        },
        legend: { show: false },
        stroke: {
            width: 5
        },
        colors: [$primary, $warning, $danger],
        fill: {
            type: 'gradient',
            gradient: {
                gradientToColors: [$primary_light, $warning_light, $danger_light]
            }
        }
    }
    var OrderChart = new ApexCharts(
    document.querySelector("#order-chart"),
    orderChartoptions
    );
    OrderChart.render();
  // apex chart

  var return_order = (document.getElementById('return_order').value);
  var wish_return_order = (document.getElementById("wish_return_order").value);

   var returnOrderChartOptions = {
        chart: {
            type: 'pie',
            height: 325,
            dropShadow: {
                enabled: false,
                blur: 5,
                left: 1,
                top: 1,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        labels: ['Return Order', 'Wish Return Order'],
        series: [parseInt(return_order),parseInt(wish_return_order)],
        dataLabels: {
            enabled: false
        },
        legend: { show: false },
        stroke: {
            width: 5
        },
        colors: [$info, $success],
        fill: {
            type: 'gradient',
            gradient: {
                gradientToColors: [$info_light, $strok_color, $danger_light]
            }
        }
    }
    var returnOrderChart = new ApexCharts(
    document.querySelector("#return_order-chart"),
    returnOrderChartOptions
    );
    returnOrderChart.render();

</script>

@endsection
