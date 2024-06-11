<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-3 col-12">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card border p-2 mb-50">
                    <input type="file" class="d-none" id="input_image" />
                    <img src="<?= $user['image'] == '' ? base_url('assets/images/profile/user-1.jpg') : $user['image'] ?>" class="w-100" id="img_image" />
                </div>
                <span class="text-danger alert-input" id="alert_input_member_image" style="display: none;"></span>
            </div>

            <div class="col-md-12 col-12">
                <a href="<?= base_url('member/profile/update_image') ?>" type="button" class="btn btn-primary mr-1 mb-2 w-100" id="btn_image">Ubah Foto Profil</a>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-12">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card mb-2 p-3">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="pb-1"><b>Biodata Anda</b></h5>
                            <hr>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="d-block mb-2">
                                <h6 class="text-left">Nama</h6>
                                <fieldset class="form-group position-relative has-icon-left text-left">
                                    <input type="text" class="form-control" value="<?= $user['name'] ?>" placeholder="Silahkan masukkan nama" name="name">
                                    <div id="name_error" class="invalid-feedback">
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="d-block mb-2">
                                <h6 class="text-left">Email</h6>
                                <fieldset class="form-group position-relative has-icon-left text-left">
                                    <input type="text" class="form-control" value="<?= $user['email'] ?>" placeholder="Silahkan masukkan email" name="email">
                                    <div id="email_error" class="invalid-feedback">
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="d-block mb-2">
                                <h6 class="text-left">Password</h6>
                                <fieldset class="form-group position-relative has-icon-left text-left">
                                    <input type="password" class="form-control" value="" placeholder="Silahkan isi password baru" name="password">
                                    <div id="password_error" class="invalid-feedback">
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-12 col-12 text-left mt-2">
                            <button type="button" class="btn btn-primary waves-effect waves-float waves-light" onclick="saveData()"><i class="bx bx-edit-alt mr-50"></i>Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="table"></div> -->

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
    saveData = () => {

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang sudah diubah tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#3085d6',
            // green
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: window.location.origin + "/member/profile/update_user",
                    type: "POST",
                    data: {
                        name: $("input[name=name]").val(),
                        email: $("input[name=email]").val(),
                        password: $("input[name=password]").val(),
                    },
                    success: (res) => {
                        var myToastEl = document.getElementById('toastSuccess')
                        var bsToast = new bootstrap.Toast(myToastEl)
                        $('#toastSuccess .toast-body').html(res.message)
                        bsToast.show()


                        setTimeout(() => {
                            window.location.reload()
                        }, 1000)
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
        })
    }
</script>

<?= $this->endSection() ?>


<?= $this->include('templates/main') ?>