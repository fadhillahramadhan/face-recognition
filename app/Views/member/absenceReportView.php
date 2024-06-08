<?= $this->section('content') ?>

<div id="table"></div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(() => {
        getTable()
    })
    getTable = () => {
        // {
        //         "kode": "FB10000001",
        //         "nama_matkul": "Web Mobile",
        //         "sks": "5",
        //         "waktu_mulai": "09-06-2024 04:46",
        //         "waktu_akhir": "09-06-2024 06:47",
        //         "kehadiran": "Hadir",
        //         "status_online": "offline"
        //     },
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
                    display: 'Waktu Mulai',
                    name: 'waktu_mulai',
                    align: 'left',
                },
                {
                    display: 'Waktu Akhir',
                    name: 'waktu_akhir',
                    align: 'left',
                },
                {
                    display: 'Kehadiran',
                    name: 'kehadiran',
                    align: 'left',
                },
                {
                    display: 'Status Online',
                    name: 'status_online',
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
                    display: 'Kehadiran',
                    name: 'kehadiran',
                    type: 'text'
                },
                {
                    display: 'Status Online',
                    name: 'status_online',
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