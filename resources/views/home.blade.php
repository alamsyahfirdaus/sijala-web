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

    <div class="col-12">
        <div class="card card-outline card-warning mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    Grafik Tren Hasil Skrining
                </h3>
            </div>

            <div class="card-body">
                <div id="trend-chart"></div>
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
        const fallRiskData = @json($fallRiskChart);
        const empowermentData = @json($empowermentChart);

        function getFallRiskColor(score) {

            // Rendah
            if (score <= 6)
                return '#22C55E';

            // Sedang
            if (score <= 7)
                return '#FACC15';

            // Tinggi
            return '#DC2626';
        }

        function getEmpowermentColor(score) {

            // Rendah
            if (score <= 70)
                return '#DC2626';

            // Sedang
            if (score <= 105)
                return '#FACC15';

            // Tinggi
            return '#22C55E';
        }

        function shuffleColors(colors) {
            return colors
                .map(value => ({
                    value,
                    sort: Math.random()
                }))
                .sort((a, b) => a.sort - b.sort)
                .map(item => item.value);
        }

        const colorPalette = [
            '#0d6efd', // Blue
            '#198754', // Green
            '#ffc107', // Yellow
            '#dc3545', // Red
            '#6f42c1', // Purple
            '#20c997', // Teal
            '#fd7e14', // Orange
            '#6610f2', // Indigo
            '#0dcaf0', // Cyan
            '#d63384', // Pink
            '#198754', // Green
            '#6c757d'  // Gray
        ];

        const randomColors = shuffleColors(colorPalette);

        document.addEventListener("DOMContentLoaded", function() {

            new ApexCharts(document.querySelector("#trend-chart"), {

                chart: {
                    type: 'line',
                    height: 420,

                    toolbar: {
                        show: false
                    },

                    zoom: {
                        enabled: false
                    },

                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },

                // ===============================
                // DATA
                // ===============================

                series: [{
                        name: 'Risiko Jatuh',
                        data: @json($fallRiskChart)
                    },
                    {
                        name: 'Kemandirian Keluarga',
                        data: @json($empowermentChart)
                    }
                ],

                // ===============================
                // WARNA GARIS
                // ===============================

                colors: [
                    '#F59E0B', // Orange
                    '#2563EB' // Blue
                ],

                // ===============================
                // GARIS
                // ===============================

                stroke: {
                    curve: 'smooth',
                    width: 4
                },

                // ===============================
                // MARKER SESUAI GRADE
                // ===============================

                markers: {

                    size: 8,
                    strokeWidth: 3,

                    hover: {
                        size: 10
                    },

                    discrete: [

                        // ===============================
                        // RISIKO JATUH
                        // ===============================

                        {
                            seriesIndex: 0,
                            dataPointIndex: 0,
                            fillColor: getFallRiskColor(fallRiskData[0]),
                            strokeColor: getFallRiskColor(fallRiskData[0]),
                            size: 10
                        },

                        {
                            seriesIndex: 0,
                            dataPointIndex: 1,
                            fillColor: getFallRiskColor(fallRiskData[1]),
                            strokeColor: getFallRiskColor(fallRiskData[1]),
                            size: 10
                        },

                        // ===============================
                        // KEMANDIRIAN KELUARGA
                        // ===============================

                        {
                            seriesIndex: 1,
                            dataPointIndex: 0,
                            fillColor: getEmpowermentColor(empowermentData[0]),
                            strokeColor: getEmpowermentColor(empowermentData[0]),
                            size: 10
                        },

                        {
                            seriesIndex: 1,
                            dataPointIndex: 1,
                            fillColor: getEmpowermentColor(empowermentData[1]),
                            strokeColor: getEmpowermentColor(empowermentData[1]),
                            size: 10
                        }

                    ]

                },

                // ===============================
                // AREA
                // ===============================

                fill: {

                    type: 'gradient',

                    gradient: {

                        shadeIntensity: 0.2,
                        opacityFrom: 0.30,
                        opacityTo: 0.05,
                        stops: [0, 100]

                    }

                },

                // ===============================
                // LABEL NILAI
                // ===============================

                dataLabels: {

                    enabled: true,

                    style: {

                        fontSize: '12px',
                        fontWeight: 'bold'

                    },

                    formatter: function(val) {
                        return val.toFixed(2);
                    }

                },

                // ===============================
                // X AXIS
                // ===============================

                xaxis: {

                    categories: @json($testCategories),
                    labels: {

                        style: {
                            fontSize: '12px'
                        }

                    }

                },

                // ===============================
                // Y AXIS
                // ===============================

                yaxis: {

                    min: 0,

                    title: {
                        text: 'Rata-rata Skor'
                    },

                    labels: {

                        formatter: function(val) {
                            return val.toFixed(2);
                        }

                    }

                },

                // ===============================
                // TOOLTIP
                // ===============================

                tooltip: {

                    shared: true,
                    intersect: false,

                    x: {
                        show: true
                    },

                    y: {

                        formatter: function(val) {
                            return val.toFixed(2);
                        }

                    }

                },

                // ===============================
                // LEGEND
                // ===============================

                legend: {

                    show: true,
                    position: 'top',
                    horizontalAlign: 'center',

                    fontSize: '13px',

                    markers: {
                        radius: 12
                    }

                },

                // ===============================
                // GRID
                // ===============================

                grid: {

                    borderColor: '#E5E7EB',
                    strokeDashArray: 4,

                    padding: {

                        left: 10,
                        right: 10

                    }

                },

                // ===============================
                // NO DATA
                // ===============================

                noData: {

                    text: 'Belum tersedia data',

                    align: 'center',
                    verticalAlign: 'middle',

                    style: {

                        fontSize: '14px'

                    }

                },

                // ===============================
                // RESPONSIVE
                // ===============================

                responsive: [

                    {

                        breakpoint: 768,

                        options: {

                            chart: {
                                height: 320
                            },

                            legend: {
                                position: 'bottom'
                            }

                        }

                    }

                ]

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

                colors: randomColors,

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
