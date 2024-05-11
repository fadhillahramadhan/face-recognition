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
            url: window.location.origin + "/member/course/get_courses",
            selectID: "id",
            colModel: [{
                    display: 'Nama Matkul',
                    name: 'name',
                    align: 'center',
                },
                {
                    display: 'Deskripsi',
                    name: 'description',
                    align: 'center',
                },
                {
                    display: 'Jadwal',
                    name: 'scheduled_at',
                    align: 'center',
                },
                {
                    display: 'Jadwal Berakhir',
                    name: 'expired_at',
                    align: 'center',
                },

            ],
            options: {
                limit: [10, 15, 20, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: "Pencarian",
            searchItems: [{
                    display: 'Nama Matkul',
                    name: 'name',
                    type: 'text'
                },
                {
                    display: 'Deskripsi',
                    name: 'description',
                    type: 'text'
                },
                {
                    display: 'Jadwal',
                    name: 'scheduled_at',
                    type: 'date'
                },
                {
                    display: 'Jadwal Berakhir',
                    name: 'expired_at',
                    type: 'date'
                },


            ],
            sortName: "bonus_log_date",
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