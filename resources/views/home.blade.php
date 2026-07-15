@extends('layouts.app')

@section('title', 'Beranda')

@section('page_title')
    Beranda
@endsection

@section('content')
    <div class="row">

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm text-white">
                    <i class="bi bi-people-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Konseli</span>
                    <span class="info-box-number">
                        {{ $totalKonseli ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm text-white">
                    <i class="bi bi-person-wheelchair"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Lansia</span>
                    <span class="info-box-number">
                        {{ $totalLansia ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm text-white">
                    <i class="bi bi-person-workspace"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Konselor</span>
                    <span class="info-box-number">
                        {{ $totalKonselor ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm text-white">
                    <i class="bi bi-hospital-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Puskesmas</span>
                    <span class="info-box-number">
                        {{ $totalPuskesmas ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card card-outline card-warning mb-4">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Grafik Skrining Risiko Jatuh</h3>
                    </div>
                </div>
                <div class="card-body" style="height: 450px; overflow-y: auto;">
                    <div id="screening-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card card-outline card-warning mb-4">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Grafik Pemberdayaan Keluarga</h3>
                    </div>
                </div>
                <div class="card-body" style="height:450px;">
                    <div id="empowerment-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-outline card-warning mb-4">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Grafik Hasil Evaluasi</h3>
                </div>
            </div>
            <div class="card-body" style="height: 450px; overflow-y: auto;">
                <div id="evaluation-chart"></div>
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
        </script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                new ApexCharts(document.querySelector("#screening-chart"), {

                    chart: {
                        type: 'bar',
                        height: 380,
                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '40%',
                            borderRadius: 6
                        }
                    },

                    series: [{
                        name: 'Jumlah Skrining',
                        data: @json($fallRiskChart)
                    }],

                    xaxis: {
                        categories: @json($testCategories)
                    },

                    yaxis: {
                        title: {
                            text: 'Jumlah Skrining'
                        }
                    },

                    colors: ['#ffc107'],

                    dataLabels: {
                        enabled: true
                    },

                    legend: {
                        show: false
                    },

                    grid: {
                        borderColor: '#ececec'
                    }

                }).render();

                new ApexCharts(document.querySelector("#empowerment-chart"), {

                    chart: {
                        type: 'bar',
                        height: 380,
                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '40%',
                            borderRadius: 6
                        }
                    },

                    series: [{
                        name: 'Jumlah Skrining',
                        data: @json($empowermentChart)
                    }],

                    xaxis: {
                        categories: @json($testCategories)
                    },

                    yaxis: {
                        title: {
                            text: 'Jumlah Skrining'
                        }
                    },

                    colors: ['#198754'],

                    dataLabels: {
                        enabled: true
                    },

                    legend: {
                        show: false
                    },

                    grid: {
                        borderColor: '#ececec'
                    }

                }).render();
                new ApexCharts(document.querySelector("#evaluation-chart"), {

                    chart: {
                        type: 'bar',
                        height: 380,
                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            borderRadius: 6,
                            distributed: true,
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },

                    series: [{
                        name: 'Rata-rata Nilai',
                        data: @json($evaluationChart)
                    }],

                    xaxis: {
                        categories: @json($evaluationCategories),
                        labels: {
                            rotate: -25,
                            trim: true,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },

                    yaxis: {
                        min: 0,
                        max: 100,
                        tickAmount: 5,
                        title: {
                            text: 'Nilai Rata-rata'
                        }
                    },

                    colors: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#20c997',
                        '#fd7e14',
                        '#6610f2'
                    ],

                    dataLabels: {
                        enabled: true,
                        offsetY: -18,
                        formatter: function(val) {
                            return val.toFixed(1);
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#333']
                        }
                    },

                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2);
                            }
                        }
                    },

                    legend: {
                        show: false
                    },

                    grid: {
                        borderColor: '#ececec'
                    }

                }).render();

            });
        </script>
    @endsection
