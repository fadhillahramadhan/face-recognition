<?= $this->section('content') ?>

<!-- date picker pilih tgl s/d  tgl -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group
                            ">
                            <input type="date" class="form-control" id="start_date" value="<?= $start_date_of_this_month ?>" name="start_date" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group
                            ">
                            <input type="date" class="form-control" value="<?= $end_date_of_this_month ?>" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button onclick="getTable($('#start_date').val(), $('#end_date').val())" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- end date picker pilih tgl s/d  tgl -->
<div id="table"></div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(() => {
        getTable('<?= $start_date_of_this_month ?>', '<?= $end_date_of_this_month ?>')
    })
    getTable = (start_date = null, end_date = null) => {
        $("#table").dataTableLib({
            url: window.location.origin + "/admin/absence/get_absence/" + start_date + "/" + end_date,
            selectID: "id",
            colModel: [{
                    display: 'Nama Dosen',
                    name: 'nama',
                    align: 'left',
                },
                {
                    display: 'Kode',
                    name: 'kode',
                    align: 'left',
                },
                {
                    display: 'Matkul',
                    name: 'nama_matkul',
                    align: 'left',

                },
                {
                    display: 'SKS',
                    name: 'sks',
                    align: 'left',
                },
                {
                    display: 'Sifat',
                    name: 'status',
                    align: 'left',
                },
                {
                    display: 'Kelas',
                    name: 'kelas',
                    align: 'left',
                },

                {
                    display: 'Prodi',
                    name: 'jurusan',
                    align: 'left',
                },

                {
                    display: 'Hadir',
                    name: 'total_hadir',
                    align: 'center',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                {
                    display: 'Tidak Hadir',
                    name: 'total_tidak_hadir',
                    align: 'center',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                // TOTAL ONLINE
                {
                    display: 'Online',
                    name: 'total_online',
                    align: 'center',
                    render: (data) => {
                        return `<b>${data}</b>`
                    }
                },
                // TOTAL OFFLINE
                {
                    display: 'Offline',
                    name: 'total_offline',
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
            searchItems: [{
                    display: 'Nama Dosen',
                    name: 'nama',
                    type: 'text'
                },



            ],
            sortName: "created_at",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [{
                display: 'Print PDF',
                icon: 'bx bx-plus',
                style: "info",
                action: "exportPdf",
                title: "Laporan Rekapitulasi Absensi",
                subtitle: "Tanggal " + start_date + " s/d " + end_date,
                headers: [{
                        title: "Nama Dosen",
                        key: "nama"
                    },
                    {
                        title: "Kode",
                        key: "kode"
                    },
                    {
                        title: "Matkul",
                        key: "nama_matkul"
                    },
                    {
                        title: "SKS",
                        key: "sks"
                    },
                    {
                        title: "Sifat",
                        key: "status"
                    },
                    {
                        title: "Kelas",
                        key: "kelas"
                    },
                    {
                        title: "Prodi",
                        key: "jurusan"
                    },
                    {
                        title: "Hadir",
                        key: "total_hadir"
                    },
                    {
                        title: "Tidak Hadir",
                        key: "total_tidak_hadir"
                    },
                    {
                        title: "Online",
                        key: "total_online"
                    },
                    {
                        title: "Offline",
                        key: "total_offline"
                    },

                ]
            }],
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