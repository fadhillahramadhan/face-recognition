<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Absensi</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url() ?>/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/styles.min.css" />
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon/favicon.png'); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon/favicon.png'); ?>">
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?= $this->include('templates_admin/sidebar') ?>

        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <?= $this->include('templates_admin/header') ?>
            <!--  Header End -->
            <div class="container-fluid">
                <!--  Page Content -->
                <?= $this->renderSection('content') ?>
                <!--  End Page Content -->
            </div>

        </div>
    </div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastSuccess" class="toast bg-success hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body" style="color: white;">

            </div>
        </div>
    </div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastDanger" class="toast bg-danger hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body" style="color: white;">

            </div>
        </div>
    </div>

    <script src="<?= base_url() ?>/assets/libs/jquery/dist/jquery.min.js"></script>

    <script src="<?= base_url() ?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/sidebarmenu.js"></script>
    <script src="<?= base_url() ?>/assets/js/app.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="<?= base_url() ?>/assets/js/dashboard.js"></script>
    <script src="<?= base_url() ?>/assets/js/datatable-extended.js"></script>
    <?= $this->renderSection('script') ?>

</body>

</html>