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
                    align: 'left',
                    render: (data, args) => {
                        return `<p class="text-left"><span><b>${data}</b></span><br><span>${args.description}</span></p>`
                    }
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
                {
                    display: 'Absen',
                    name: 'is_enable',
                    align: 'center',
                    render: (data, args) => {
                        if (data == 1) {
                            return `<a href="<?= base_url() ?>member/absence/add/${args.id}" class="btn btn-primary">Absen</a>`
                        } else {
                            return `<button class="btn btn-secondary" disabled>Absen</button>`
                        }
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