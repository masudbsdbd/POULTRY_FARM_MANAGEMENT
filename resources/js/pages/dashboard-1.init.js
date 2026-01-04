/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Dashboard 1 init
*/

import 'flatpickr/dist/flatpickr.min.css';

import 'flatpickr/dist/flatpickr.min.js';
import ApexCharts from 'apexcharts';

//
// Total Revenue
//
var colors = ['#f1556c'];
var dataColors = $("#total-revenue").data('colors');
if (dataColors) {
	colors = dataColors.split(",");
}
var options = {
	series: [68],
	chart: {
		height: 242,
		type: 'radialBar',
	},
	plotOptions: {
		radialBar: {
			hollow: {
				size: '65%',
			}
		},
	},
	colors: colors,
	labels: ['Revenue'],
};

var chart = new ApexCharts(document.querySelector("#total-revenue"), options);
chart.render();


//
// Sales Analytics
//
	var colors = ['#1abc9c', '#4a81d4'];
	var dataColors = $("#sales-analytics").data('colors');
	if (dataColors) {
		colors = dataColors.split(",");
	}

	var salesQtyData = window.salesQtyData || [];
	var salesDateLabels = window.salesDateLabels || [];

	var revenueData = Array(salesQtyData.length).fill(0);


	var options = {
		series: [{
			name: 'Revenue',
			type: 'column',
			data: salesRevenueData 
		}, {
			name: 'Sales',
			type: 'line',
			data: salesQtyData
		}],
		chart: {
			height: 378,
			type: 'line',
			offsetY: 10
		},
		stroke: {
			width: [2, 3]
		},
		plotOptions: {
			bar: {
				columnWidth: '50%'
			}
		},
		colors: colors,
		dataLabels: {
			enabled: true,
			enabledOnSeries: [1]
		},
		labels: window.salesDateLabels,
		xaxis: {
			type: 'category'
		},
		legend: {
			offsetY: 7,
		},
		grid: {
			padding: {
			bottom: 20
			}
		},
		fill: {
			type: 'gradient',
			gradient: {
				shade: 'light',
				type: "horizontal",
				shadeIntensity: 0.25,
				gradientToColors: undefined,
				inverseColors: true,
				opacityFrom: 0.75,
				opacityTo: 0.75,
				stops: [0, 0, 0]
			},
		},
		yaxis: [{
			title: {
				text: 'Net Revenue',
			},

		}, {
			opposite: true,
			title: {
				text: 'Number of Sales'
			}
		}]
	};

	var chart = new ApexCharts(document.querySelector("#sales-analytics"), options);
	chart.render();

	$('#dash-daterange').flatpickr({
		altInput: true,
		mode: "range",
		altFormat: "F j, y",
		defaultDate: 'today'
	});

		// Purchase/Sell===============>
        var options = {
          series: [{
          name: 'Purchase',
          data: purchaseTotalPrice
        }, {
          name: 'Sell',
          data: salesRevenueData
        }],
          chart: {
          height: 400,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: window.salesDateLabels,
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#profit-expense"), options);
        chart.render();

		// cash flow ========================>
		var options = {
			chart: {
				type: 'area',
				height: 400,
				toolbar: { show: false }
			},
			colors: ['#3b82f6', '#f97316'],
			dataLabels: { enabled: false },
			stroke: { curve: 'smooth' },
			series: [
				{ name: 'Credit', data: totalCredit },
				{ name: 'Debit', data: totalDebit }
			],
			xaxis: {
				type: 'datetime',
				categories: creditDebitDateLabels
			},
			yaxis: {
				labels: {
					formatter: function (value) {
						return "৳" + value.toLocaleString();
					}
				}
			},
			tooltip: {
				y: {
					formatter: function (value) {
						return "৳" + value.toLocaleString();
					}
				}
			},
			legend: { position: 'top' }
		};
	
		var chart = new ApexCharts(document.querySelector("#cashFlowChart"), options);
		chart.render();


		// Profit and loss summary ========================>
			var options = {
				chart: {
					type: 'donut',
				},
				labels: ['Total Profit', 'Total Loss'],
				series: [profitAmount, lossAmount],
				colors: ['#2E93fA', '#F45B69'],
				title: {
					text: 'Profit and Loss Summary',
					align: 'center',
					style: {
						fontSize: '20px',
						fontWeight: 'bold',
						color: '#263238'
					}
				},
				legend: {
					position: 'right',
				},
				dataLabels: {
					enabled: true,
					formatter: function (val) {
						return val.toFixed(0) + "%";
					}
				},
				plotOptions: {
					pie: {
						donut: {
							size: '65%',
							labels: {
								show: true,
								total: {
									show: true,
									label: 'Summary',
									fontSize: '16px',
									color: '#373d3f'
								}
							}
						}
					}
				}
			};
			
			var chart = new ApexCharts(document.querySelector("#profitOrLossChart"), options);
			chart.render();

			// bank balances ========================>
			var options = {
				chart: {
					type: 'bar',
					height: 400
				},
				title: {
					text: 'Bank Account Balances',
					align: 'center',
					style: {
						fontSize: '20px',
						fontWeight: 'bold',
						color: '#263238'
					}
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: '50%',
						endingShape: 'rounded'
					},
				},
				dataLabels: {
					enabled: true,
					formatter: function (val) {
						return val.toLocaleString() + " ৳"; 
					}
				},
				xaxis: {
					categories: bankNames,
				},
				colors: ['#00E396'],
				series: [{
					name: 'Balance',
					data: bankAmounts
				}]
			};
		
			var chart = new ApexCharts(document.querySelector("#bankBalancesChart"), options);
			chart.render();