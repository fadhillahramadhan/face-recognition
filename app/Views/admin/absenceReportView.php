<?= $this->section('content') ?>

<div id="table"></div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(() => {
        getTable()
    })
    getTable = () => {
        $("#table").dataTableLib({
            url: window.location.origin + "/admin/absence/get_absence",
            selectID: "id",
            colModel: [{
                    display: 'Tahun',
                    name: 'tahun',
                    align: 'left',
                    // render bold
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                {
                    display: 'Bulan',
                    name: 'bulan',
                    align: 'left',
                    // render bold
                    render: (data) => {
                        return `${data}`
                    }
                },

                {
                    display: 'Nama Dosen',
                    name: 'user_name',
                    align: 'left',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                {
                    display: 'Kode Matkul',
                    name: 'course_code',
                    align: 'left',
                },
                {
                    display: 'Nama Matkul',
                    name: 'course_name',
                    align: 'left',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                {
                    display: 'Jurusan',
                    name: 'study_name',
                    align: 'left',
                },
                {
                    display: 'Status',
                    name: 'status',
                    align: 'left',
                },

                {
                    display: 'SKS',
                    name: 'sks',
                    align: 'left',
                },
                {
                    display: 'Total Absensi',
                    name: 'total_absence',
                    align: 'center',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },



            ],
            options: {
                limit: [10, 15, 20, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: "Pencarian",
            searchItems: [
                //     {
                //     display: 'Jadwal',
                //     name: 'created_at',
                //     type: 'date'
                // }, 
                // bulan tahun
                // {
                //             display: 'Apakah Stockist',
                //             name: 'member_is_stockist',
                //             type: 'select',
                //             option: [{
                //                 title: 'Ya',
                //                 value: '1'
                //             }, {
                //                 title: 'Tidak',
                //                 value: '0'
                //             }]
                //         },
                {
                    display: 'Tahun',
                    name: 'tahun',
                    type: 'text'
                },
                {
                    display: 'Bulan',
                    name: 'bulan',
                    type: 'select',
                    option: [{
                            title: 'Januari',
                            value: '1'
                        },
                        {
                            title: 'Februari',
                            value: '2'
                        },
                        {
                            title: 'Maret',
                            value: '3'
                        },
                        {
                            title: 'April',
                            value: '4'
                        },
                        {
                            title: 'Mei',
                            value: '5'
                        },
                        {
                            title: 'Juni',
                            value: '6'
                        },
                        {
                            title: 'Juli',
                            value: '7'
                        },
                        {
                            title: 'Agustus',
                            value: '8'
                        },
                        {
                            title: 'September',
                            value: '9'
                        },
                        {
                            title: 'Oktober',
                            value: '10'
                        },
                        {
                            title: 'November',
                            value: '11'
                        },
                        {
                            title: 'Desember',
                            value: '12'
                        }
                    ]
                },
                {
                    display: 'Nama Dosen',
                    name: 'user_name',
                    type: 'text'
                },
                {
                    display: 'Kode Matkul',
                    name: 'course_code',
                    type: 'text'
                },
                {
                    display: 'Nama Matkul',
                    name: 'course_name',
                    type: 'text'
                },
                {
                    display: 'Jurusan',
                    name: 'study_name',
                    type: 'text'
                },
                {
                    display: 'Status',
                    name: 'status',
                    type: 'text'
                },
                {
                    display: 'SKS',
                    name: 'sks',
                    type: 'text'
                },
                {
                    display: 'Total Absensi',
                    name: 'total_absence',
                    type: 'text'
                },


            ],
            sortName: "created_at",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [],
            success: (res) => {
                data = res.data.results

                setTimeout(() => {
                    $("#loadingData").hide('fast')
                    $("#table").show('fast')
                    if (data.length <= 0) $("#data_kosong").show()
                }, 1000);
            }
        })
    }
</script>
<?= $this->endSection() ?>


<?= $this->include('templates_admin/main') ?>