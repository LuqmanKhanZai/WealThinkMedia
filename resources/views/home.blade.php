@extends('layouts.app')

@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-2">
            <a href="{{ route('item.index') }}">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h3 class="font-bold">Item Setups</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Graph -->
    {{-- <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Monthly Reports</h5>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-white active">Today</button>
                            <button type="button" class="btn btn-xs btn-white">Monthly</button>
                            <button type="button" class="btn btn-xs btn-white">Annual</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-dashboard-chart"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins">2,346</h2>
                                    <small>Total orders in period</small>
                                    <div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i>
                                    </div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">4,422</h2>
                                    <small>Orders in last month</small>
                                    <div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i>
                                    </div>
                                    <div class="progress progress-mini">
                                        <div style="width: 60%;" class="progress-bar"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">9,180</h2>
                                    <small>Monthly income from orders</small>
                                    <div class="stat-percent">22% <i class="fa fa-bolt text-navy"></i></div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}
@endsection
