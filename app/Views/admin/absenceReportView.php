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
                    display: 'Nama Dosen',
                    name: 'user_name',
                    align: 'left',
                    render: (data, args) => {
                        return `<p> <b>${data}</b> <br> ${args.user_email} </p>`
                    }
                },
                {
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


<?= $this->include('templates_admin/main') ?>