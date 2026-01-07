@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                                <i class="fe-dollar-sign font-22 avatar-title text-danger"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $totalCustomers }}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Total Customers</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->
            
            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                    <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $totalActivebatch }}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Total Active Batches</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-4">
                <div class="widget-rounded-circle card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                    <i class="fe-dollar-sign font-22 avatar-title text-success"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <h3 class="text-dark mt-1"> <span data-plugin="counterup">{{ $totalInActivebatch }}</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Total Inactive Batches</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div> <!-- end col-->
        </div>


        {{-- <div id="income-chart" style="margin-top: 100px;"></div> --}}


        <div class="row ">
            <div class="col-xxl-8 d-none">
                <div class="card h-100 radius-8 border-0">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <div>
                                <h6 class="mb-2 fw-bold text-lg text-neutral-600">Payment Chart <span id="revenueYear"><?php echo date('Y'); ?></span></h6>
                                <span class="text-sm fw-medium text-secondary-light">Monthly Income Chart</span>
                            </div>
                        </div>

                        <div id="income-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4">
                <div class="card h-100 radius-8 border-0">
                    <div class="card-body p-24 row">
                        <h4 class="mb-2 fw-bold text-lg text-neutral-600 col-8">
                            Paid vs Unpaid
                        </h4>
                        
                        <div class="mt-24">
                            <div id="revenue_statistics_chart" width="500" height="500">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/dashboard-1.init.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
        ];
        const amounts = [
            0,
            0,
            1000,
            2000,
            3000,
            4000,
            5000,
            6000,
            7000,
            8000,
            9000,
            10000
        ];
        console.log(months);
        console.log(amounts);
        var options = {
          series: [{
          name: 'Amount',
          data: amounts
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '10%',
            borderRadius: 5,
            borderRadiusApplication: 'end'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: months,
        },
        yaxis: {
          title: {
            text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "৳ " + val
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#income-chart"), options);
        chart.render();



        // paid vs unpaid
        const totalPaid = @json($toalPaidAmount);
        const totalUnpaid = @json($totalDueAmount);
        var options = {
        series: [parseInt(totalPaid), parseInt(totalUnpaid)],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['Paid', 'Unpaid'],
        colors: ['#28a745', '#dc3545'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };


    expenseRevenueMonthlyChart = new ApexCharts(document.querySelector("#revenue_statistics_chart"), options);
    expenseRevenueMonthlyChart.render();

</script>
@endsection
