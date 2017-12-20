@extends('layouts.app')

@section('content')

    <input id="points" type="text" value="{{ $points }}" hidden>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">
        var ctx = document.getElementById("chart").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Points',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1,
                    pointRadius: 0
                }]
            },
            options: {}
        });

        $(jQuery.parseJSON($("#points").val())).each(function (index, point) {
            chart.data.labels.push(point.date);
            chart.data.datasets[0].data.push(point.value);
        });
        chart.update();
    </script>
@endsection
