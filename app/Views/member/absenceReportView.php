<?= $this->section('content') ?>
<div class="card">
    <div class="card-body ">
        <h5 class="card-title align-self-start">Presensi</h5>
        <p class="card-text align-self-start">Daftar presensi yang tersedia</p>
        <div id="table"></div>
    </div>
</div>
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
                    display: 'Nama Matkul',
                    name: 'course_name',
                    align: 'left',
                },
                {
                    display: 'Tanggal Absensi',
                    name: 'created_at',
                    align: 'left',
                },


            ],
            options: {
                limit: [10, 15, 20, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: "Pencarian",
            searchItems: [{
                display: 'Jadwal',
                name: 'created_at',
                type: 'date'
            }, ],
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


<?= $this->include('templates/main') ?>