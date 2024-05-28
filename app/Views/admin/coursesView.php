<?= $this->section('content') ?>

<div id="table"></div>

<div class="modal fade" id="addUpdateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Matakuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class=" mb-3">
                        <label class="form-label">Nama kuliah</label>
                        <input type="text" name="name" class="form-control">
                        <small class="form-text text-muted">Masukkan nama kuliah</small>
                        <!-- error -->
                        <div id="name_error" class="invalid-feedback">
                        </div>
                    </div>
                    <div class=" mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="code" class="form-control">
                        <small class="form-text text-muted">Masukkan Kode</small>
                        <!-- error -->
                        <div id="code_error" class="invalid-feedback">
                        </div>
                    </div>
                    <div class=" mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea type="text" name="description" class="form-control"> </textarea>
                        <small class="form-text text-muted">Masukkan deskripsi</small>
                        <!-- error -->
                        <div id="description_error" class="invalid-feedback">
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
            url: window.location.origin + "/admin/courses/get_courses",
            selectID: "id",
            colModel: [

                {
                    display: 'Nama Mata Kuliah',
                    name: 'name',
                    align: 'left',
                    render: (params, args) => {
                        return `<span><b>${params}</b></span><br><span>${args.code}</span>`;
                    },
                },
                {
                    display: 'Deskripsi',
                    name: 'description',
                    align: 'left',
                    render: (params, args) => {
                        return `<span>${params}</span>`;
                    },
                },
                {
                    display: 'Action',
                    name: 'id',
                    align: 'center',
                    render(data) {
                        return `<a href="javascript:void(0)" onclick="updateData(${data})" class="btn btn-warning btn-sm">Edit</a>`
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
                    name: "name",
                    title: "Nama Mata Kuliah",
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

        $(".modal-title").html("Tambah Mata Kuliah")

        new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
    }

    saveData = () => {
        let url = update ? window.location.origin + "/admin/courses/update_course" : window.location.origin + "/admin/courses/add_course"

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: current_id,
                name: $("input[name=name]").val(),
                code: $("input[name=code]").val(),
                description: $("textarea[name=description]").val(),
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
        $(".modal-title").html("Update Mata Kuliah")

        getCourse(id).then((res) => {
            let data = res.data

            $("input[name=name]").val(data.name)
            $("input[name=code]").val(data.code)
            $("textarea[name=description]").val(data.description)


            new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
        }).error((err) => {
            console.log(err)
        })
    }

    getCourse = (id) => {
        return $.ajax({
            url: window.location.origin + "/admin/courses/get_course/" + id,
            type: "GET",
            data: {
                id: id
            },
        })
    }
</script>
<?= $this->endSection() ?>


<?= $this->include('templates_admin/main') ?>