@extends('admin.layouts.global')

@section('custom_css')
<link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
<link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
<style>
.widget-four .widget-heading { margin-bottom: 15px; }
.widget.widget-one .w-chart .w-chart-section .w-detail {
    position: relative;
    color: #fff;
    margin-bottom: 30px;
}
</style>
@endsection

@section('custom_js')
<script src="plugins/apex/apexcharts.min.js"></script>
<!--<script src="assets/js/dashboard/dash_2.js"></script>-->
<script>

    /*
        ======================================
            Visitor Statistics | Options
        ======================================
    */


    // Total Visits

    var spark1 = {
        chart: {
            id: 'unique-visits',
            group: 'sparks2',
            type: 'line',
            height: 80,
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                top: 1,
                left: 1,
                blur: 2,
                color: '#e2a03f',
                opacity: 0.7,
            }
        },
        series: [{
            data: [21, 9, 36, 12, 44, 25, 59, 41, 66, 25]
        }],
        stroke: {
          curve: 'smooth',
          width: 2,
        },
        markers: {
            size: 0
        },
        grid: {
          padding: {
            top: 35,
            bottom: 0,
            left: 40
          }
        },
        colors: ['#e2a03f'],
        tooltip: {
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function formatter(val) {
                        return '';
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 1351,
            options: {
               chart: {
                  height: 95,
              },
              grid: {
                  padding: {
                    top: 35,
                    bottom: 0,
                    left: 0
                  }
              },
            },
        },
        {
            breakpoint: 1200,
            options: {
               chart: {
                  height: 80,
              },
              grid: {
                  padding: {
                    top: 35,
                    bottom: 0,
                    left: 40
                  }
              },
            },
        },
        {
            breakpoint: 576,
            options: {
               chart: {
                  height: 95,
              },
              grid: {
                  padding: {
                    top: 45,
                    bottom: 0,
                    left: 0
                  }
              },
            },
        }

        ]
    }

    // Paid Visits

    var spark2 = {
        chart: {
          id: 'total-users',
          group: 'sparks1',
          type: 'line',
          height: 80,
          sparkline: {
            enabled: true
          },
          dropShadow: {
            enabled: true,
            top: 3,
            left: 1,
            blur: 3,
            color: '#009688',
            opacity: 0.7,
          }
        },
        series: [{
          data: [22, 19, 30, 47, 32, 44, 34, 55, 41, 69]
        }],
        stroke: {
          curve: 'smooth',
          width: 2,
        },
        markers: {
          size: 0
        },
        grid: {
          padding: {
            top: 35,
            bottom: 0,
            left: 40
          }
        },
        colors: ['#009688'],
        tooltip: {
          x: {
            show: false
          },
          y: {
            title: {
              formatter: function formatter(val) {
                return '';
              }
            }
          }
        },
        responsive: [{
            breakpoint: 1351,
            options: {
               chart: {
                  height: 95,
              },
              grid: {
                  padding: {
                    top: 35,
                    bottom: 0,
                    left: 0
                  }
              },
            },
        },
        {
            breakpoint: 1200,
            options: {
               chart: {
                  height: 80,
              },
              grid: {
                  padding: {
                    top: 35,
                    bottom: 0,
                    left: 40
                  }
              },
            },
        },
        {
            breakpoint: 576,
            options: {
               chart: {
                  height: 95,
              },
              grid: {
                  padding: {
                    top: 35,
                    bottom: 0,
                    left: 0
                  }
              },
            },
        }
        ]
    }

    /*
        ======================================
            Visitor Statistics | Script
        ======================================
    */

    // Total Visits
    /*
    d_1C_1 = new ApexCharts(document.querySelector("#total-users"), spark1);
    d_1C_1.render();*/

    // Paid Visits
    /*
    d_1C_2 = new ApexCharts(document.querySelector("#paid-visits"), spark2);
    d_1C_2.render();*/




</script>
@endsection



@section('content_html')
    @parent

                <div class="row layout-top-spacing">

                    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-one">
                            <div class="widget-heading">
                                <h6 class="">活動統計</h6>
                            </div>
                            <div class="w-chart">
                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">不重複抽獎人次</p>
                                        <p class="w-stats">{!! $report['UNI_MAX_MILK'] !!}</p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <!--<div id="total-users"></div>-->
                                    </div>
                                </div>

                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">總抽獎數</p>
                                        <p class="w-stats">{!! $report['ALL_MAX_MILK'] !!}</p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <!--<div id="paid-visits"></div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"> {!! $report['DAILY_MILK_TAKE'] !!}/{!! $report['DAILY_MAX_MILK'] !!} </h6>
                                        <p class="">當日已發送/當日總數</p>
                                    </div>
                                    <div class="">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {!! $report['progressbar'] !!}%" aria-valuenow="{!! $report['progressbar'] !!}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget-four">
                            <div class="widget-heading">
                                <h5 class="">已發送比例</h5>
                            </div>
                            <div class="widget-content">
                                <div class="vistorsBrowser">
                                    <div class="browser-list">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chrome"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg>
                                        </div>
                                        <div class="w-browser-details">
                                            <div class="w-browser-info">
                                                <h6>木瓜牛奶</h6>
                                                <p class="browser-count">{!! $report['DAILY_MILK_TAKE_PERCENT_A'] !!}%</p>
                                            </div>
                                            <div class="w-browser-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {!! $report['DAILY_MILK_TAKE_PERCENT_A'] !!}%" aria-valuenow="{!! $report['DAILY_MILK_TAKE_PERCENT_A'] !!}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="browser-list">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-compass"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
                                        </div>
                                        <div class="w-browser-details">

                                            <div class="w-browser-info">
                                                <h6>西瓜牛奶</h6>
                                                <p class="browser-count">{!! $report['DAILY_MILK_TAKE_PERCENT_B'] !!}%</p>
                                            </div>

                                            <div class="w-browser-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: {!! $report['DAILY_MILK_TAKE_PERCENT_B'] !!}%" aria-valuenow="{!! $report['DAILY_MILK_TAKE_PERCENT_B'] !!}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>




                    <!--
                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="row widget-statistic">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="widget widget-one_hybrid widget-followers">
                                    <div class="widget-heading">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        </div>
                                        <p class="w-value">31.6K</p>
                                        <h5 class="">Followers</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div class="w-chart">
                                            <div id="hybrid_followers"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="widget widget-one_hybrid widget-referral">
                                    <div class="widget-heading">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                        </div>
                                        <p class="w-value">1,900</p>
                                        <h5 class="">Referral</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div class="w-chart">
                                            <div id="hybrid_followers1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="widget widget-one_hybrid widget-engagement">
                                    <div class="widget-heading">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                        </div>
                                        <p class="w-value">18.2%</p>
                                        <h5 class="">Engagement</h5>
                                    </div>
                                    <div class="widget-content">
                                        <div class="w-chart">
                                            <div id="hybrid_followers3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->



                </div>


@endsection
