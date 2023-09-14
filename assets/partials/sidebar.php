<nav class="navbar navbar-dark navbar-expand-lg px-4 col-12" style="background-color: rgb(255,0,0,0.9);">
    <a class="navbar-brand me-lg-5 p-2" href="<?php echo site_url(); ?>" style="background-color: #fff">
        <img src="/assets/img/logo-300.png" alt="logo" />
    </a>
    <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container-fluid">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/invoice">Invoice</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/report">Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/customer">Customer</a>
                </li>
            </ul>
            <!-- <div class="d-block">
                <a href="/logout" class="btn btn-danger btn-sm d-inline-flex align-items-center">
                    <span class="icon icon-xs me-1"><i class="bi bi-box-arrow-right"></i></span> Sign Out
                </a>
            </div> -->
        </div>
    </div>
</nav>

<nav id="sidebarMenu" class="sidebar bg-gray-800 text-white collapse d-lg-none" data-simplebar>
    <div class="sidebar-inner px-3 pt-3">
        <div class="user-card d-flex align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="/assets/img/user.png" class="card-img-top rounded-circle border-white" alt="Bonnie Green">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">fff</h2>
                    <a href="/logout" class="btn btn-danger btn-sm d-inline-flex align-items-center">
                        <span class="icon icon-xs me-1"><i class="bi bi-box-arrow-right"></i></span> Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item pb-3">
                <span class="sidebar-icon">
                    <img src="/assets/img/logo-300.png" height="20" alt="Logo">
                </span>
                <span class="mt-1 ms-1 sidebar-text">Notification system</span>
            </li>
            <li class="nav-item" id="_menu-dashboard">
                <a href="/invoice" class="nav-link">
                    <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="sidebar-text">Invoice</span>
                </a>
            </li>
            <li class="nav-item" id="_menu-dashboard">
                <a href="/report" class="nav-link">
                    <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="sidebar-text">Report</span>
                </a>
            </li>
            <li class="nav-item" id="_menu-dashboard">
                <a href="/customer" class="nav-link">
                    <span class="sidebar-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="sidebar-text">Customer</span>
                </a>
            </li>
            <li role="separator" class="dropdown-divider border-gray-700"></li>
        </ul>
    </div>
</nav>