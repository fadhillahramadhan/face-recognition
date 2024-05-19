<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>

        </ul>
        <ol class="breadcrumb d-none-1440">
            <?php foreach ($breadcumbs as $key => $value) : ?>
                <?php if ($value['active']) : ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= $key ?></li>
                <?php else : ?>
                    <li class="breadcrumb-item"><a href="<?= $value['href'] ?>"><?= $key ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>


        </ol>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">


            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item">
                    <div class="user-nav d-flex flex-column align-items-end justify-content-center">
                        <span class=" user-status text-black"><b style="color: #089c33!important"><?= session('admin')['email'] ?></b></span>
                        <span class="user-status text-muted"><?= session('admin')['name'] ?></span>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url() ?>/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="<?= base_url('/admin/profile') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>

                            <a href="<?= base_url('/authadmin/logout') ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>


<!-- under 1440px display none -->
<style>
    @media (max-width: 1439px) {
        .d-none-1440 {
            display: none;
        }
    }
</style>