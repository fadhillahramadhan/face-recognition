<?= $this->section('content') ?>

<div id="table"></div>

<div class="modal fade" id="addUpdateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class=" mb-3">
                        <label class="form-label">Nama Prodi</label>
                        <input type="text" name="name" class="form-control">
                        <small class="form-text text-muted">Masukkan nama Prodi</small>
                        <!-- error -->
                        <div id="name_error" class="invalid-feedback">
                        </div>
                    </div>
                    <!-- KELAS OPTION A ATAU B -->
                    <div class="mb-3">
                        <label class="form-label d-block">Kelas</label>
                        <!-- SELECT -->
                        <select class="form-select" name="class">
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="saveData()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
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
            url: window.location.origin + "/admin/studies/get_studies",
            selectID: "id",
            colModel: [

                {
                    display: 'Nama Prodi',
                    name: 'name',
                    align: 'left',
                    render: (params, args) => {
                        return `<span>${params}</span><br>`;
                    },
                },
                {
                    display: 'Kelas',
                    name: 'class',
                    align: 'center',
                    render: (params) => {
                        return `<span>${params}</span>`;
                    },
                },

                {
                    display: 'Action',
                    name: 'id',
                    align: 'center',
                    render(data) {
                        return `
                        <a href="javascript:void(0)" onclick="updateData(${data})" class="btn btn-warning btn-sm">Update</a>
                        <a href="javascript:void(0)" onclick="remove(${data})" class="btn btn-danger btn-sm">Hapus</a>
                        `
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
                    name: "name",
                    title: "Nama Mata Prodi",
                    type: "text"
                },
                {
                    name: "description",
                    title: "Deskripsi",
                    type: "text"
                },

            ],
            sortName: "created_at",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [{
                display: 'Tambah',
                icon: 'bx bx-plus',
                style: "info",
                action: "addData"
            }, ],
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

    addData = () => {
        update = false
        current_id = null

        $(".modal-title").html("Tambah Mata Prodi")

        new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
    }

    saveData = () => {
        let url = update ? window.location.origin + "/admin/studies/update_study" : window.location.origin + "/admin/studies/add_study"

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: current_id,
                name: $("input[name=name]").val(),
                code: '-',
                class: $("select[name=class]").val(),
            },
            success: (res) => {
                // hide modal
                new bootstrap.Modal(document.getElementById('addUpdateModal')).hide()

                var myToastEl = document.getElementById('toastSuccess')
                var bsToast = new bootstrap.Toast(myToastEl)
                $('#toastSuccess .toast-body').html(res.message)
                bsToast.show()
                window.location.reload()
            },
            error: (err) => {
                err = err.responseJSON

                if (err.error == "validation") {
                    for (const key in err.data) {
                        $(`input[name=${key}]`).addClass('is-invalid')
                        $(`#${key}_error`).html(err.data[key])
                    }
                } else {
                    var myToastEl = document.getElementById('toastError')
                    var bsToast = new bootstrap.Toast(myToastEl)
                    $('#toastError .toast-body').html(err.message)
                    bsToast.show()
                }
            }
        })
    }


    updateData = (id) => {
        update = true
        current_id = id

        // change modal title
        $(".modal-title").html("Update Mata Prodi")

        getStudy(id).then((res) => {
            let data = res.data

            $("input[name=name]").val(data.name)
            $("select[name=class]").val(data.class)


            new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
        }).error((err) => {
            console.log(err)
        })
    }

    getStudy = (id) => {
        return $.ajax({
            url: window.location.origin + "/admin/studies/get_study/" + id,
            type: "GET",
            data: {
                id: id
            },
        })
    }

    remove = (id) => {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + "/admin/studies/delete_study",
                    type: "POST",
                    data: {
                        field: 'id',
                        data: [id]
                    },
                    success: (res) => {
                        var myToastEl = document.getElementById('toastSuccess')
                        var bsToast = new bootstrap.Toast(myToastEl)
                        $('#toastSuccess .toast-body').html(res.message)
                        bsToast.show()
                        window.location.reload()
                    },
                    error: (err) => {
                        err = err.responseJSON

                        var myToastEl = document.getElementById('toastError')
                        var bsToast = new bootstrap.Toast(myToastEl)
                        $('#toastError .toast-body').html(err.message)
                        bsToast.show()
                    }
                })
            }
        })
    }
</script>
<?= $this->endSection() ?>


<?= $this->include('templates_admin/main') ?>