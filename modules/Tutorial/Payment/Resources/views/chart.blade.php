<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    Highcharts.setOptions({
        tooltip: {
            formatter: function() {
                var formattedValue = Highcharts.numberFormat(this.y, 0, '', ',');
                if (this.x) {
                    return '<span style="font-size: 10px">' + this.x + '</span><br/>' +
                        '<span style="color:' + this.point.color + '">\u25CF</span> ' + this.series.name +
                        ': <b>' +
                        formattedValue + ' تومان' + '</b><br/>';
                } else {

                    return '<span style="font-size: 10px">' + '' + '</span><br/>' +
                        '<span style="color:' + this.point.color + '">\u25CF</span> ' + this.series.name +
                        ': <b>' +
                        formattedValue + ' تومان' + '</b><br/>';
                }

            }
        },
        yAxis: {
            labels: {
                formatter: function() {
                    var formattedValue = Highcharts.numberFormat(this.value, 0, '', ',');
                    return formattedValue + ' تومان';
                }
            }
        }
    });
    Highcharts.chart('container', {

        title: {
            text: 'نمودار فروش 30 روز گذشته',
            align: 'center'
        },


        xAxis: {
            categories: [@foreach ($dates as $date=>$value) '{{ Tutorial\Common\getJalaliFromFormat($date) }}', @endforeach]
        },
        yAxis: {
            title: {
                text: 'مبلغ'
            },

            labels: {
                formatter: function() {
                    return Highcharts.numberFormat(this.value, 0, '', ',') + " تومان "
                }
            }
        },
        tooltip: {

            valueSuffix: ' تومان'
        },
        plotOptions: {
            series: {
                borderRadius: '25%'
            }
        },
        series: [
            {
                type: 'column',
                name: 'درصد مدرس',
                data: [@foreach ($dates as $date => $value)@if($day = $summery->where('date',$date)->first()) {{ $day->totalSellerShare }}, @else 0,  @endif @endforeach]
            },
            {
                type: 'column',
                name: 'تراکنش موفق',
                data: [@foreach ($dates as $date => $value)@if($day = $summery->where('date',$date)->first()) {{ $day->totalAmount }}, @else 0 , @endif @endforeach]
            },
            {
                type: 'column',
                name: 'درصد سایت',
                data: [@foreach ($dates as $date => $value)@if($day = $summery->where('date',$date)->first()) {{ $day->totalSiteShare }}, @else 0 , @endif @endforeach]
            },

            {
                type: 'spline',
                name: 'فروش',
                data: [@foreach ($dates as $date => $value)@if($day = $summery->where('date',$date)->first()) {{ $day->totalAmount }}, @else 0 , @endif @endforeach],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[3],
                    fillColor: 'white'
                }
            }, {
                type: 'pie',
                name: 'فروش',
                data: [{
                    name: 'درصد مدرس',
                    y: {{ $last30DaysSellerShare }},
                    color: Highcharts.getOptions().colors[0], // 2020 color
                    dataLabels: {
                        enabled: true,
                        distance: -50,
                        format: '{point.total} T',
                        style: {
                            fontSize: '15px'
                        }
                    }
                }, {
                    name: 'درصد سایت',
                    y: {{ $last30DaysBenefit }},
                    color: Highcharts.getOptions().colors[2] // 2022 color
                }],
                center: [75, 65],
                size: 100,
                innerSize: '70%',
                showInLegend: false,
                dataLabels: {
                    enabled: false
                }
            }
        ]
    });
</script>
