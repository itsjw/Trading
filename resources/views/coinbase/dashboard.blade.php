@extends('layouts.app')

@section('content')

    <input id="userObjective" type="text" value="{{ $userObjective }}" hidden>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="btn-group btn-group-justified" role="group">
                                    <div class="btn-group" role="group">
                                        <button id="button-hour" type="button" class="btn btn-default" onclick="updateChart('hour')">Hour</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button id="button-day" type="button" class="btn btn-default" onclick="updateChart('day')">Day</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <canvas id="chart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Wallet</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <span id="balanceAmount" style="font-size: 20px;">Loading ...</span>
                                    <span id="balanceCurrency" style="font-size: 13px;"></span>
                                    <p>WALLET</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <span id="bitcoinAmount" style="font-size: 20px;">Loading ...</span>
                                    <p>BITCOIN</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Objective <span id="delta"></span></div>
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

        var fees = 2;
        var bitcoinAmount = 0;
        var sellPrice = 0;
        var balance = 0;
        var lastBuy = 0;
        var userObjective = $("#userObjective").val();

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

        updateChart('hour');

        getSellPrice();
        getLastBuy();
        getPrimaryAccount();

        setInterval(function () {
            updateChart('hour');
        }, 1000 * 30);

        setInterval(function () {
            getSellPrice();
            getLastBuy();
            getPrimaryAccount();
        }, 10000);

        function getPrimaryAccount() {
            $.ajax({
                url: './primaryaccount',
                cache: false,
                method: 'GET',
                success: function (response) {
                    updateBitcoinAmount(response.balanceAmount);
                    updateBalance();
                },
                error: function (error) {
                    updateBitcoinAmount("ERROR", "");
                }
            });
        }

        function getSellPrice() {
            $.ajax({
                url: './sellprice',
                cache: false,
                method: 'GET',
                success: function (response) {
                    updateSellPrice(response.amount);
                    updateBalance();
                },
                error: function (error) {
                    updateSellPrice("ERROR");
                }
            });
        }

        function getLastBuy() {
            $.ajax({
                url: './lastbuy',
                cache: false,
                method: 'GET',
                success: function (response) {
                    updateLastBuy(response.amount);
                },
                error: function (error) {
                    updateLastBuy("ERROR", "");
                }
            });
        }

        function updateSellPrice(amount) {
            sellPrice = amount;
        }

        function updateLastBuy(amount) {
            lastBuy = amount;
            updateChartDelta();
        }

        function updateBitcoinAmount(amount) {
            bitcoinAmount = amount;
            $("#bitcoinAmount").text(bitcoinAmount);
        }

        function updateBalance() {
            balance = (bitcoinAmount * sellPrice) - fees;
            updateChartDelta();
            $("#balanceAmount").text(balance.toString().substr(0, 6));
            $("#balanceCurrency").text("EUR");
        }

        function updateChart(interval) {
            $("#button-day").removeClass().attr('class', 'btn btn-default');
            $("#button-hour").removeClass().attr('class', 'btn btn-default');
            $("#button-" + interval).removeClass().attr('class', 'btn btn-default active');

            $.ajax({
                url: './money?interval=' + interval,
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

        function updateChartDelta() {
            var delta = balance - lastBuy;
            $("#delta").text(delta.toString().substr(0, 6));

            chartDelta.data.labels = [new Date().toTimeString().substr(0, 8)];
            chartDelta.data.datasets[0].data = [delta];
            chartDelta.data.datasets[1].data = [delta > 0 ? userObjective - delta : userObjective];
            chartDelta.update();
        }

    </script>
@endsection