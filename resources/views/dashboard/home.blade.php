@extends('layouts.presensi')

@section('content')
    <style>
        /* Gaya default untuk canvas */
        .canvas {
            height: 450px !important;
            width: 100% !important;
            /* Ketinggian default */
        }

        /* Media query untuk layar kecil (misalnya, lebar kurang dari 600px) */
        @media only screen and (max-width: 600px) {
            .canvas {
                height: 100% !important;
                width: 100% !important;
                /* Atur ketinggian sesuai kebutuhan untuk layar kecil */
            }
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="row">
                <div class="col-12">
                    {{-- <div class="card-header"></div> --}}
                    <div class="card-body">
                        <strong>Kas Modal</strong>
                        <canvas class="canvas" id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">Kas Yatim</div> --}}
                    <div class="card-body">
                        <strong>Kas Yatim</strong>
                        <canvas class="canvas" id="lineChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">Kas Karyawan</div> --}}
                    <div class="card-body">
                        <strong>Kas Karyawan</strong>
                        <canvas class="canvas" id="lineChart3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">Kas Karyawan</div> --}}
                    <div class="card-body">
                        <strong>Kas Oprational</strong>
                        <canvas class="canvas" id="lineChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthmodalLabels) !!},
                    datasets: [{
                        label: 'Debet',
                        data: {!! json_encode($debetmodalTotals) !!},
                        borderColor: 'rgba(0, 0, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Credit',
                        data: {!! json_encode($creditmodalTotals) !!},
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Saldo',
                        data: {!! json_encode($balancemodals) !!},
                        borderColor: 'rgba(0, 255, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('lineChart2').getContext('2d');
            var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthyatimLabels) !!},
                    datasets: [{
                        label: 'Debet',
                        data: {!! json_encode($debetyatimTotals) !!},
                        borderColor: 'rgba(0, 0, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Credit',
                        data: {!! json_encode($credityatimTotals) !!},
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Saldo',
                        data: {!! json_encode($balanceyatims) !!},
                        borderColor: 'rgba(0, 255, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('lineChart3').getContext('2d');
            var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthkasLabels) !!},
                    datasets: [{
                        label: 'Debet',
                        data: {!! json_encode($debetkasTotals) !!},
                        borderColor: 'rgba(0, 0, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Credit',
                        data: {!! json_encode($creditkasTotals) !!},
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Saldo',
                        data: {!! json_encode($balancekass) !!},
                        borderColor: 'rgba(0, 255, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('lineChart4').getContext('2d');
            var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthoprationalLabels) !!},
                    datasets: [{
                        label: 'Debet',
                        data: {!! json_encode($debetoprationalTotals) !!},
                        borderColor: 'rgba(0, 0, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Credit',
                        data: {!! json_encode($creditoprationalTotals) !!},
                        borderColor: 'rgba(255, 0, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Saldo',
                        data: {!! json_encode($balanceoprationals) !!},
                        borderColor: 'rgba(0, 255, 0, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
