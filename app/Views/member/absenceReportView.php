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
            url: window.location.origin + "/member/absence/get_absence",
            selectID: "id",
            colModel: [{
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
                    display: 'Kelas',
                    name: 'class',
                    align: 'left',
                },


                {
                    display: "Tanggal",
                    name: "waktu_mulai",
                    align: "left",
                    render: (data, args) => {
                        return `${data}`
                    }
                },
                {
                    display: 'Waktu',
                    name: 'waktu_mulai_time',
                    align: 'left',
                    render: (data, args) => {
                        return `${data} - ${args.waktu_akhir_time}`
                    }
                },
                {
                    display: 'Sifat',
                    name: 'status_courses',
                    align: 'left',
                },
                {
                    display: 'Status',
                    name: 'kehadiran',
                    align: 'left',
                },

            ],
            options: {
                limit: [10, 15, 20, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: "Pencarian",
            searchItems: [
                // waktu_mulai
                {
                    display: 'Waktu Mulai',
                    name: 'waktu_mulai',
                    type: 'date'
                },

                {
                    display: 'Kode',
                    name: 'kode',
                    type: 'text'
                },
                {
                    display: 'Matkul',
                    name: 'nama_matkul',
                    type: 'text'
                },
                {
                    display: 'Status',
                    name: 'kehadiran',
                    type: 'text'
                },

            ],
            sortName: "scheduled_at",
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


<?= $this->include('templates/main') ?>