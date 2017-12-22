@extends('layouts.app')

@section('content')

    <input id="userObjective" type="text" value="{{ $userObjective }}" hidden>
    <input id="userAlert" type="text" value="{{ $userAlert }}" hidden>

    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <span id="nativeBalanceAmount" style="font-size: 25px;">Loading ...</span>
                            <span id="nativeBalanceCurrency" style="font-size: 25px;"></span>
                            <p>NATIVE BALANCE</p>
                        </div>
                        <div class="text-center">
                            <span id="status" style="font-size: 25px;"></span>
                            <span id="statusValue" style="font-size: 13px;"></span>
                            <p>STATUS</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <span id="lastBuyAmount" style="font-size: 25px;">Loading ...</span>
                            <span id="lastBuyCurrency" style="font-size: 25px;"></span>
                            <p>LAST BUY</p>
                        </div>
                        <div class="text-center">
                            <span id="lastSellAmount" style="font-size: 25px;">Loading ...</span>
                            <span id="lastSellCurrency" style="font-size: 25px;"></span>
                            <p>LAST SELL</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <canvas id="chartDelta"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">

        var lastBuyAmount = 0;
        var nativeBalanceAmount = 0;
        var userObjective = $("#userObjective").val();
        var userAlert = $("#userAlert").val();

        getPrimaryAccount();
        getLastBuy();
        updateChart();
        getLastSell();

        setInterval(function () {
            getPrimaryAccount();
            getLastBuy();
            getLastSell();
            updateChart();
            updateChartDelta();
        }, 10000);

        var ctxChart = document.getElementById("chart").getContext('2d');
        var chart = new Chart(ctxChart, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: "Spot",
                    borderColor: 'rgba(52, 152, 219, 0.7)',
                    fill: false,
                    data: []
                }, {
                    label: "Sell",
                    borderColor: 'rgba(231, 76, 60, 0.7)',
                    fill: false,
                    data: []
                }, {
                    label: "Buy",
                    borderColor: 'rgba(46, 204, 113, 0.7)',
                    fill: false,
                    data: []
                }]
            },
            options: {}
        });

        var ctxChartDelta = document.getElementById("chartDelta").getContext('2d');
        var chartDelta = new Chart(ctxChartDelta, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: "Current",
                    backgroundColor: 'rgba(241, 196, 15, 0.7)',
                    data: []
                }, {
                    label: "Objective",
                    backgroundColor: 'rgba(24, 196, 15, 0.7)',
                    data: []
                }]
            },
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });

        function updateChart() {
            $.ajax({
                url: './money',
                cache: false,
                method: 'GET',
                success: function (response) {
                    chart.data.labels = [];
                    chart.data.datasets[0].data = [];
                    chart.data.datasets[1].data = [];
                    chart.data.datasets[2].data = [];

                    response.spot.forEach(function (spot) {
                        chart.data.labels.push(spot.date.substr(11, 5));
                        chart.data.datasets[0].data.push(spot.value);
                    });

                    response.sell.forEach(function (sell) {
                        chart.data.datasets[1].data.push(sell.value);
                    });

                    response.buy.forEach(function (buy) {
                        chart.data.datasets[2].data.push(buy.value);
                    });
                    chart.update();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function getPrimaryAccount() {
            $.ajax({
                url: './primaryaccount',
                cache: false,
                method: 'GET',
                success: function (response) {
                    nativeBalanceAmount = response.nativeBalanceAmount;
                    $("#nativeBalanceAmount").removeClass().text(response.nativeBalanceAmount);
                    $("#nativeBalanceCurrency").removeClass().text(response.nativeBalanceCurrency);
                    updateChartDelta();
                },
                error: function (error) {
                    $("#nativeBalanceAmount").attr('class', 'text-danger').text("Error");
                    $("#nativeBalanceCurrency").text("");
                }
            });
        }

        function getLastBuy() {
            $.ajax({
                url: './lastbuy',
                cache: false,
                method: 'GET',
                success: function (response) {
                    lastBuyAmount = response.amount;
                    $("#lastBuyAmount").removeClass().text(response.amount);
                    $("#lastBuyCurrency").removeClass().text(response.currency);
                    updateChartDelta();
                    updateStatus();
                },
                error: function (error) {
                    $("#lastBuyAmount").attr('class', 'text-danger').text("Error");
                    $("#lastBuyCurrency").text("");
                }
            });
        }

        function getLastSell() {
            $.ajax({
                url: './lastsell',
                cache: false,
                method: 'GET',
                success: function (response) {
                    $("#lastSellAmount").removeClass().text(response.amount);
                    $("#lastSellCurrency").removeClass().text(response.currency);
                },
                error: function (error) {
                    $("#lastSellAmount").attr('class', 'text-danger').text("Error");
                    $("#lastSellCurrency").text("");
                }
            });
        }

        function updateChartDelta() {
            var delta = nativeBalanceAmount - lastBuyAmount;
            chartDelta.data.labels = [new Date().toTimeString().substr(0, 8)];
            chartDelta.data.datasets[0].data = [delta];
            chartDelta.data.datasets[1].data = [delta > 0 ? userObjective - delta : userObjective];
            chartDelta.update();
        }

        function updateStatus() {
            var delta = nativeBalanceAmount - lastBuyAmount;
            $("#statusValue").text("(" + delta.toString().substr(0, 4) + ")");

            if (delta >= userObjective) {
                $("#status").attr('class', 'text-success').text("Perfect !");
            } else if (delta < userObjective && delta > userAlert) {
                $("#status").attr('class', 'text-success').text("Normal");
            } else if (delta <= userAlert) {
                $("#status").attr('class', 'text-danger').text("Bad !");
            }
        }
    </script>
@endsection