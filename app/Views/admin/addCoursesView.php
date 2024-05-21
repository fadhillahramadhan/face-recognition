<?= $this->section('content') ?>

<h6>Pilih Dosen</h6>
<small>Pilih dosen yang akan ditambahkan mata kuliahnya</small>
<div id="table"></div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    let update = false;
    let current_id = null;

    $(document).ready(() => {
        getTable()
    })


    getTable = () => {
        $("#table").dataTableLib({
            url: window.location.origin + "/admin/user/get_users",
            selectID: "id",
            colModel: [{
                    display: 'Photo',
                    name: 'image',
                    align: 'center',
                    render(data) {
                        return `<img src="${data}" class="img-fluid" style="width: 50px; height: 50px; border-radius: 50%;">`
                    }
                },
                {
                    display: 'Nama Dosen',
                    name: 'name',
                    align: 'left',
                    render: (params, args) => {
                        return `<span><b>${params}</b></span> <br> ${args.email}<br>`;
                    },
                },

                {
                    display: 'Tanggal Dibuat',
                    name: 'created_at',
                    align: 'left',
                },
                {
                    display: 'Tanggal Diubah',
                    name: 'updated_at',
                    align: 'left',
                },
                // edit
                {
                    display: 'Action',
                    name: 'id',
                    align: 'center',
                    render(data) {
                        return `<a href="javascript:void(0)" onclick="addCourses(${data})" class="btn btn-warning btn-sm">
                        <i class="ti ti-pencil">
                        Tambah<br> Mata Kuliah</a>`
                    }
                }


            ],
            options: {
                limit: [10, 15, 20, 50, 100],
                currentLimit: 10,
            },
            search: true,
            searchTitle: "Pencarian",
            searchItems: [{
                    display: 'Tanggal Dibuat',
                    name: 'created_at',
                    type: 'date'
                },
                {
                    display: 'Nama Dosen',
                    name: 'name',
                    type: 'text'
                },
                {
                    display: 'Email',
                    name: 'email',
                    type: 'text'
                }

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


    addCourses = (id) => {
        window.location = window.location.origin + "/admin/addcourses/add/" + id
    }
</script>
<?= $this->endSection() ?>


<?= $this->include('templates_admin/main') ?>