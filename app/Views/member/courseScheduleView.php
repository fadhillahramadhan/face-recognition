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
            colModel: [
                // KODE
                {
                    display: 'Kode Matkul',
                    name: 'code',
                    align: 'left',
                },
                {
                    display: 'Matkul',
                    name: 'name',
                    align: 'left',

                },



                // jadwal 
                {
                    display: 'Tanggal',
                    name: 'scheduled_at',
                    align: 'left',
                    render: (data, args) => {
                        return `${data} `
                    }
                },
                // (jam ${args.scheduled_at_time} - ${args.expired_at_time})
                // waktu
                {
                    display: 'Waktu',
                    name: 'scheduled_at_time',
                    align: 'left',
                    render: (data, args) => {
                        return `${data} - ${args.expired_at_time}`
                    }
                },


                {
                    display: 'Presensi',
                    name: 'is_enable',
                    align: 'center',
                    render: (data, args) => {
                        if (data == 1) {
                            return ` <a href="<?= base_url() ?>member/absence/add/${args.id}"
                            class="btn btn-primary"> Presensi </a>`
                        } else {
                            return `<button class="btn btn-secondary" disabled>Presensi</button>`
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
            }, ],
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