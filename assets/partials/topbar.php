<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
            <div class="d-flex align-items-center mb-3">
                <?php if (!empty($page_header)) { ?>
                    <h4 class="text-muted">
                        <?php echo $page_header; ?></h5>
                    <?php } ?>
                    </h4>
            </div>

            <!-- <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <img class="avatar rounded-circle" alt="Image placeholder" src="/assets/img/user.png">
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small fw-bold text-gray-900">test</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                        <a class="dropdown-item d-flex align-items-center" href="/profile">
                            <span class="dropdown-icon text-gray-500 mr-2"><i class="bi bi-person-circle"></i></span>My Profile
                        </a>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center" href="/logout">
                            <span class="dropdown-icon text-danger mr-2"><i class="bi bi-box-arrow-right"></i></span>Logout
                        </a>
                    </div>
                </li>
            </ul> -->
        </div>
    </div>
</nav>