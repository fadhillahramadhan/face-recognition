<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-center">
            <a class="text-nowrap logo-img">
                <img src="<?= base_url() ?>/assets/images/logos/logo.png" height="100" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" aria-expanded="false" href="<?= base_url('/admin/dashboard') ?>" <span>
                        <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Dosen</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('/admin/user') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Dosen</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('/admin/absence') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-paperclip"></i>
                        </span>
                        <span class="hide-menu">Presensi Dosen</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Mata Kuliah</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('/admin/courses') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-globe"></i>
                        </span>
                        <span class="hide-menu">Mata Kuliah</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('/admin/addcourses') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-plus"></i>
                        </span>
                        <span class="hide-menu">Tambah Mata Kuliah</span>
                    </a>
                </li>
            </ul>
            <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
                <div class="d-flex">
                    <div class="unlimited-access-title me-3">
                        <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Ingin Keluar ? &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</h6>
                        <a href="<?= base_url('/authadmin/logout') ?>" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Logout</a>
                    </div>
                    <div class="unlimited-access-img">
                        <img src="<?= base_url() ?>/assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>