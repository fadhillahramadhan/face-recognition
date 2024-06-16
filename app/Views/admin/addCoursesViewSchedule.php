<?= $this->section('content') ?>

<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <!-- Monthly Earnings -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-start">

                    <div class="col-12 col-md-6">
                        <h6 class="fw-semibold mb-3">Detail Dosen</h6>
                        <h6 class="fw-semibold mb-3">
                            <?= $user['name'] ?><br>
                            <small class="text-muted"><?= $user['email'] ?></small>
                        </h6>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12">

        <div id="table"></div>
    </div>
</div>

<div class="modal fade" id="addUpdateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Nama Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class=" mb-3">
                        <label class="form-label">Nama Jadwal</label>
                        <select class="form-select" name="course_id">
                            <option value="">Pilih Jadwal</option>
                            <?php foreach ($courses as $course) : ?>
                                <option value="<?= $course['id'] ?>"><?= $course['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Waktu Mulai</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                        <small class="form-text text-muted">Masukkan Waktu mulai</small>
                        <!-- error -->
                        <div id="scheduled_at_error" class="invalid-feedback">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Waktu Selesai</label>
                        <input type="datetime-local" name="expired_at" class="form-control">
                        <small class="form-text text-muted">Masukkan Waktu selesai</small>
                        <!-- error -->
                        <div id="expired_at_error" class="invalid-feedback">
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
            url: window.location.origin + "/admin/addcourses/get_courses_users/" + "<?= $id ?>",
            selectID: "id",
            colModel: [{
                    display: 'Nama Mata Kuliah',
                    name: 'code',
                    align: 'left',

                },
                {
                    display: 'Nama Mata Kuliah',
                    name: 'name',
                    align: 'left',

                },
                {
                    display: 'Kelas',
                    name: 'class',
                    align: 'left',

                },
                {
                    display: 'SKS',
                    name: 'sks',
                    align: 'left',

                },

                {
                    display: "Jadwal",
                    name: "scheduled_at",
                    align: "left",
                    render: (data, args) => {
                        return `${data} (jam ${args.scheduled_at_time} - ${args.expired_at_time})`
                    }
                },
                {
                    display: 'Aksi',
                    name: 'id',
                    align: 'center',
                    render(data) {
                        return `<a href="javascript:void(0)" onclick="updateData(${data})" class="btn btn-warning btn-sm">Update</a>
                        <a href="javascript:void(0)" onclick="remove(${data})" class="btn btn-danger btn-sm">Hapus</a>
                        
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
                    name: "name",
                    title: "Nama Mata Kuliah",
                    type: "text"
                },

            ],
            sortName: "created_at",
            sortOrder: "DESC",
            tableIsResponsive: true,
            select: false,
            multiSelect: false,
            buttonAction: [
                // kembali
                {
                    display: 'Kembali',
                    icon: 'ti ti-arrow-left',
                    style: "secondary",
                    action: "back"

                },
                {
                    display: 'Tambah',
                    icon: 'bx bx-plus',
                    style: "info",
                    action: "addData"
                },
            ],
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

        $(".modal-title").html("Tambah Jadwal")

        new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
    }

    saveData = () => {
        let url = update ? window.location.origin + "/admin/addcourses/update_course" : window.location.origin + "/admin/addcourses/add_course"

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: current_id,
                course_id: $("select[name=course_id]").val(),
                user_id: '<?= $id ?>',
                scheduled_at: $("input[name=scheduled_at]").val(),
                expired_at: $("input[name=expired_at]").val(),
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
        $(".modal-title").html("Update Jadwal")

        getCourse(id).then((res) => {
            let data = res.data

            $("select[name=course_id]").val(data.course_id)
            $("select[name=study_id]").val(data.study_id)
            $("select[name=room_id]").val(data.room_id)

            new bootstrap.Modal(document.getElementById('addUpdateModal')).show()
        }).error((err) => {
            console.log(err)
        })
    }

    getCourse = (id) => {
        return $.ajax({
            url: window.location.origin + "/admin/addcourses/get_course/" + id,
            type: "GET",
            data: {
                id: id
            },
        })
    }

    back = () => {
        window.location.href = window.location.origin + "/admin/addcourses"
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
                    url: window.location.origin + "/admin/addcourses/delete_course",
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