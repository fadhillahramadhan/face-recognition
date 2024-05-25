<style>
    .loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    #canvas {
        display: none;
    }

    .responsive-video {
        width: 100%;
    }

    .responsive-canvas {
        width: 100%;
    }
</style>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">Grafik Absen</h5>
                    </div>
                </div>
                <div id="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-12">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Total Kehadiran </h5>
                                <h4 class="fw-semibold mb-3"><?= $absence ?></h4>
                                <div class="d-flex align-items-center pb-1">

                                    <!-- <span class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-down-right text-danger"></i>
                                    </span> -->

                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user-plus fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Total Tidak Hadir </h5>
                                <h4 class="fw-semibold mb-3"><?= $not_absence ?></h4>
                                <div class="d-flex align-items-center pb-1">

                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user-minus fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>

<script>
    const getAbsence = (month, year) => {
        var chart = {
            series: [{
                    name: 'Absen',
                    data: <?= json_encode($chart['absence']) ?>
                },
                {
                    name: 'Tidak Absen',
                    data: <?= json_encode($chart['not_absence']) ?>
                },
            ],
            labels: <?= json_encode($chart['date']) ?>,
            chart: {
                type: 'bar',
                height: 345,
                offsetX: -15,
                toolbar: {
                    show: true
                },
                foreColor: '#adb0bb',
                fontFamily: 'inherit',
                sparkline: {
                    enabled: false
                },
            },

            colors: ['#5fb41b', '#49BEFF'],

            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '35%',
                    borderRadius: [6],
                    borderRadiusApplication: 'end',
                    borderRadiusWhenStacked: 'all',
                },
            },
            markers: {
                size: 0
            },

            dataLabels: {
                enabled: false,
            },

            legend: {
                show: false,
            },

            grid: {
                borderColor: 'rgba(0,0,0,0.1)',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
            },

            xaxis: {
                type: 'category',
                categories: <?= json_encode($chart['date']) ?>,
                labels: {
                    style: {
                        cssClass: 'grey--text lighten-2--text fill-color'
                    },
                },
            },


            stroke: {
                show: true,
                width: 3,
                lineCap: 'butt',
                colors: ['transparent'],
            },

            tooltip: {
                theme: 'light'
            },

            responsive: [{
                breakpoint: 600,
                options: {
                    plotOptions: {
                        bar: {
                            borderRadius: 3,
                        },
                    },
                },
            }, ],
        };

        $('#chart').html('');
        // change jquery to vanilla js
        // let chartElement = document.getElementById('chart');
        // chartElement.innerHTML = '';

        var chart = new ApexCharts(document.querySelector('#chart'), chart);
        chart.render();
    }

    getAbsence();
</script>
<?= $this->endSection() ?>


<?= $this->include('templates/main') ?>