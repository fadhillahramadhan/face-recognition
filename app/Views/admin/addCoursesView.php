<?= $this->section('content') ?>

<div id="table"></div>

<div class="modal fade" id="addUpdateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class=" mb-3">
                        <label class="form-label">Nama Dosen</label>
                        <input type="text" name="name" class="form-control">
                        <small class="form-text text-muted">Masukkan nama dosen</small>
                        <!-- error -->
                        <div id="name_error" class="invalid-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control">
                        <small class="form-text text-muted">Masukkan email dosen misalnya: test@gmail.com </small>
                        <!-- error -->
                        <div id="email_error" class="invalid-feedback">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control">
                        <small class="form-text text-muted">Masukkan password dosen</small>
                        <!-- error -->
                        <div id="password_error" class="invalid-feedback">
                        </div>
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
                        return `
                        <a href="javascript:void(0)" onclick="addCourses(${data})" class="btn btn-primary btn-sm">
                        Tambah Jadwal</a>
                        <a href="javascript:void(0)" onclick="updateData(${data})" class="btn btn-warning btn-sm">Update</a>
                        
                        `
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


    addCourses = (id) => {
        window.location = window.location.origin + "/admin/addcourses/add/" + id
    }

    addData = () => {
        update = false
        current_id = null

        $(".modal-title").html("Tambah Dosen")

        new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
    }

    saveData = () => {
        let url = update ? window.location.origin + "/admin/user/update_user" : window.location.origin + "/admin/user/add_user"

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: current_id,
                name: $("input[name=name]").val(),
                email: $("input[name=email]").val(),
                password: $("input[name=password]").val(),
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
        $(".modal-title").html("Update Dosen")

        getUserData(id).then((res) => {
            let data = res.data

            $("input[name=name]").val(data.name)
            $("input[name=email]").val(data.email)


            new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
        }).error((err) => {
            console.log(err)
        })
    }

    getUserData = (id) => {
        return $.ajax({
            url: window.location.origin + "/admin/user/get_user/" + id,
            type: "GET",
            data: {
                id: id
            },
        })
    }
</script>
<?= $this->endSection() ?>


<?= $this->include('templates_admin/main') ?>