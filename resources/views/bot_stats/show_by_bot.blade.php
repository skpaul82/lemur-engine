<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">



    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>


        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bot Rating</h3>
                </div>
                <div class="box-body">
                    @include('bot_ratings.show_by_bot')
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Today's Stats</h3>
                </div>
                <div class="box-body">
                    <p>Conversation Count: {!! $todayConversationStat !!}</p>
                    <p>Turns Count: {!! $todayTurnStat !!}</p>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">All Time Stats</h3>
                </div>
                <div class="box-body">
                    <p>Conversation Count: {!! $allTimeConversationStat !!}</p>
                    <p>Turns Count: {!! $allTimeTurnStat !!}</p>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">This Months Conversations</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartMonthConversations" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">This Months Turns</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartMonthTurns" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Last 12 Months Conversations</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartYearConversations" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Last 12 Months Turns</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartYearTurns" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>





    </div>
</section>



@push('scripts')
    {{ Html::script('js/Chart.js') }}
    <script>
        $(function () {




            var barChartOptions                  = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero        : true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines      : true,
                //String - Colour of the grid lines
                scaleGridLineColor      : 'rgba(0,0,0,.05)',
                //Number - Width of the grid lines
                scaleGridLineWidth      : 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines  : true,
                //Boolean - If there is a stroke on each bar
                barShowStroke           : true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth          : 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing         : 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing       : 1,
                //String - A legend template
                legendTemplate          : '',
                //Boolean - whether to make the chart responsive
                responsive              : true,
                maintainAspectRatio     : true
            }


    //-------------
    //- BAR CHART -
    //-------------
   /* var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)*/

            //-------------
            //- BAR CHART YEAR CONVERSATIONS -
            //-------------
            var barChartYearConversationsData = {
                labels  : [@foreach ($monthsInYearKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Conversations',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($yearlyConversationStat as $stat)"{{ $stat['data'] }}",@endforeach]
                    }
                ]
            }
            var barChartYearConversationsCanvas                   = $('#barChartYearConversations').get(0).getContext('2d')
            var barChartYearConversations                         = new Chart(barChartYearConversationsCanvas)

            barChartOptions.datasetFill = false
            barChartYearConversations.Bar(barChartYearConversationsData, barChartOptions)


            //-------------
            //- BAR CHART YEAR TURNS -
            //-------------
            var barChartYearTurnsData = {
                labels  : [@foreach ($monthsInYearKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Turns',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($yearlyTurnStat as $stat)"{{ $stat['data'] }}",@endforeach]
                    }
                ]
            }
            var barChartYearTurnsCanvas                   = $('#barChartYearTurns').get(0).getContext('2d')
            var barChartYearTurns                         = new Chart(barChartYearTurnsCanvas)

            barChartOptions.datasetFill = false
            barChartYearTurns.Bar(barChartYearTurnsData, barChartOptions)


            //-------------
            //- BAR CHART MONTH CONVERSATIONS -
            //-------------
            var barChartMonthConversationsData = {
                labels  : [@foreach ($daysInMonthKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Conversations',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($monthlyConversationStat as $stat)"{{ $stat }}",@endforeach]
                    }
                ]
            }
            var barChartMonthConversationsCanvas                   = $('#barChartMonthConversations').get(0).getContext('2d')
            var barChartMonthConversations                         = new Chart(barChartMonthConversationsCanvas)

            barChartOptions.datasetFill = false
            barChartMonthConversations.Bar(barChartMonthConversationsData, barChartOptions)

            //-------------
            //- BAR CHART MONTH TURNS -
            //-------------
            var barChartMonthTurnsData = {
                labels  : [@foreach ($daysInMonthKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Turns',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($monthlyTurnStat as $stat)"{{ $stat }}",@endforeach]
                    }
                ]
            }
            var barChartMonthTurnsCanvas                   = $('#barChartMonthTurns').get(0).getContext('2d')
            var barChartMonthTurns                         = new Chart(barChartMonthTurnsCanvas)

            barChartOptions.datasetFill = false
            barChartMonthTurns.Bar(barChartMonthTurnsData, barChartOptions)

  })
</script>
@endpush

