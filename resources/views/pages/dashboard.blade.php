@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid dashboard-cards">
    <div class="row">
        <!-- page statustic chart start -->
        <div class="col-xl-6 col-md-6">
            <a href="users">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{$total_users}}</h4>
                                <p class="mb-0">{{ __('Total Users')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

        </div>
        <!-- <div class="col-xl-3 col-md-6">
            <a href="users">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0"></h4>
                                <p class="mb-0">{{ __('Free Users')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        </div> -->
        <!-- <div class="col-xl-3 col-md-6">
            <a href="users">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0"></h4>
                                <p class="mb-0">{{ __('Premium Users')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </a>
        </div> -->
        <div class="col-xl-6 col-md-6">
            <a href="">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{config('constants.currency')}}{{$total_transtation_amont}}</h4>
                                <p class="mb-0">{{ __('Wallet Amount')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-map f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Latest Users -->
        <div class="col-md-6 col-xl-6">
            <div class="card sale-card">
                <div class="card-header row align-items-center ">
                    <div class="col-sm-6 col-6">
                        <h3>{{ __('Latest Users')}}</h3>
                    </div>
                    <div class="col-sm-6 text-right col-6">
                        <a class="btn btn-outline-primary btn-rounded-20" href="users">
                            <i class="ik ik-eye"></i>View More
                        </a>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="dashboard-users-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                    <th>Mobile No.</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($latest_users) && count($latest_users) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach($latest_users as $user)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{(!empty($user->country_code) ? $user->country_code.'-' : '').$user->phone}}</td>
                                    <td>{{date('m-d-Y',strtotime($user->created_at))}}</td>
                                </tr>
                                @php
                                $i++
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4"> Users not found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Latest Users -->
        <!-- Latest Subscrtiption -->
        <div class="col-md-6 col-xl-6">
            <div class="card sale-card">
                <div class="card-header row align-items-center">
                    <div class="col-sm-6 col-6">
                        <h3>{{ __('Latest Payments')}}</h3>
                    </div>
                    <div class="col-sm-6 text-right col-6 col-6">
                            <a class="btn btn-outline-primary btn-rounded-20" href="subscription">
                                <i class="ik ik-eye"></i>View More
                            </a>
                       
                    </div>
                </div>
                <div class="card-block text-center">
                    <div class="dashboard-users-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Transaction Id</th>
                                    <th>User Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($latest_subscription) && count($latest_subscription) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach($latest_subscription as $subscription)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                   
                                    @if(strlen($subscription->transaction_id) > 15)
                                        
                                        {{substr($subscription->transaction_id, 0,15)."..."}}
                                        
                                    @else
                                            {{$subscription->transaction_id}}
                                    @endif
                                    </td>
                                    <td>{{$subscription->first_name}}</td>
                                    <td>{{config('constants.currency')}}{{$subscription->total_amount}}</td>
                                    <td>{{date('m-d-Y',strtotime($subscription->created_at))}}</td>
                                </tr>
                                @php
                                $i++
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">No subscription found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Latest Subscrtiption -->

        @php $month = date('m');
        $year = date('Y')
        @endphp
        <div class="col-md-6 col-xl-6">
            <div class="card sale-card">
                <div class="card-header row align-items-center ">
                    <div class="col-sm-6 col-6">
                        <h3>{{ __('Users')}}</h3>
                    </div>
                    <div class="col-sm-6 col-6">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="form-group">
                                <select id="users_year" name="users_year" class="form-control">
                                    @for($years = $year; $years >= $year-2; $years--)
                                    <option value="{{$years}}">{{$years}}</option>
                                    @endfor
                                    <!-- <option value="{{$year}}">{{$year}}</option>
                                                <option value="2021">{{$year-1}}</option>
                                                <option value="2020">{{$year-2}}</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div id="myChartOne"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-6">
            <div class="card sale-card">
                <div class="card-header row align-items-center">
                    <div class="col-sm-6 col-6">
                        <h3>{{ __('Payments')}}</h3>
                    </div>
                    <div class="col-sm-6 col-6">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="form-group">
                                <select id="subscription_year" name="subscription_year" class="form-control">
                                    @for($years = $year; $years >= $year-2; $years--)
                                    <option value="{{$years}}">{{$years}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block text-center">
                    <div id="myChartSubscription"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<!-- push external js -->
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>

<!-- linechart-js-one -->
<script src='https://www.gstatic.com/charts/loader.js'></script>

<style type="text/css">
    .form-group {
        margin-bottom: 0;
    }

    select.form-control {
        height: 30px !important;
        margin: 0;
        margin-left: auto;
        width: 80px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        var users_year = $(document).find('#users_year').val();
        get_user_data(users_year);
        get_subscription_data(users_year);
    });

    $("#users_year").change(function() {
        var users_year = $(this).val();
        get_user_data(users_year);
    });

    $("#subscription_year").change(function() {
        var subscription_year = $(this).val();
        get_subscription_data(subscription_year);
    });


    function get_user_data(year) {
        $.ajax({
            type: 'post',
            url: "dashboard/get_users",
            data: {
                'year': year,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(data) {
                drawUserChart(data.month_name)
            }
        });
    }


    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawUserChart);

    function drawUserChart(data = '') {
        var dataNew = [];
        var Header = ['Users', 'Months'];
        dataNew.push(Header);
        for (var i = 0; i < data.length; i++) {
            dataNew.push([data[i]['month_name'], data[i]['count']]);
        }
        //console.log(dataNew);
        var chartdata = google.visualization.arrayToDataTable(dataNew);

        var options = {
            title: 'Total User Month Wise',
            curveType: 'function',
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('myChartOne'));

        chart.draw(chartdata, options);
    }


    function get_subscription_data(year) {
        $.ajax({
            type: 'post',
            url: "dashboard/get_subscription",
            data: {
                'year': year,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(data) {
                drawSubscriptionChart(data.month_name)
            }
        });
    }

    // google.charts.load('current', {
    //     'packages': ['corechart']
    // });
    google.charts.setOnLoadCallback(drawSubscriptionChart);

    function drawSubscriptionChart(data = '') {
        var dataNew = [];
        var Header = ['Users', 'Months'];
        dataNew.push(Header);
        for (var i = 0; i < data.length; i++) {
            dataNew.push([data[i]['month_name'], data[i]['count']]);
        }
        //console.log(dataNew);
        var chartdata = google.visualization.arrayToDataTable(dataNew);

        var options = {
            title: 'Total Payment Month Wise',
            curveType: 'function',
            legend: {
                position: 'bottom'
            }            
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('myChartSubscription'));

        chart.draw(chartdata, options);
    }
</script>
@endpush