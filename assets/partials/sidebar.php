<nav class="navbar navbar-dark navbar-expand-lg col-12 navbar-blackground-sm">
    <div class="logo-lg">
        <a class="navbar-brand" href="<?php echo $http ?>/invoice">
            <img src="<?php echo $http ?>/assets/img/logo-300.png" alt="logo" />
        </a>
    </div>
    <button class="navbar-toggler d-lg-none collapsed justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation" style="background-color: rgb(255, 0, 0, 0.9)">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="container-fluid navbar-blackground-lg">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (checkPermission('การแจ้งเตือน', $this->CURUSER->user[0]->dep_id, $this->role) || $this->CURUSER->user[0]->user_type == 'Cus') { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo $http ?>/invoice">การแจ้งเตือน</a>
                    </li>
                <?php }; ?>
                <?php if (checkPermission('รายงาน', $this->CURUSER->user[0]->dep_id, $this->role) || $this->CURUSER->user[0]->user_type == 'Cus') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $http ?>/report">รายงาน</a>
                    </li>
                <?php }; ?>
                <?php if ($this->CURUSER->user[0]->user_type == 'Emp' && checkPermission('ตั้งค่า', $this->CURUSER->user[0]->dep_id, $this->role)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                            ตั้งค่า <i class="bi bi-chevron-down mt-4"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $http . '/customer' ?>">ข้อมูลลูกค้า</a></li>
                            <li><a class="dropdown-item" href="<?php echo $http . '/setting?tab=invoice' ?>">แก้ไขคอลัมน์และซ่อมข้อมูล</a></li>
                        </ul>
                    </li>
                <?php }; ?>
            </ul>
            <div class="d-block">
                <a href="<?php echo $http ?>/access/logout" class="d-inline-flex align-items-center text-white smo">
                    Back to smo
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($this->CURUSER->user[0]->username)) { ?>
        <div class="displayname">
            <div class="name-role">
                <span><?php echo str_replace(' ', '&nbsp;', $this->CURUSER->user[0]->username); ?></span>
            </div>
        </div>
    <?php } ?>
</nav>

<nav id="sidebarMenu" class="sidebar bg-gray-800 text-white collapse d-lg-none" data-simplebar>
    <div class="sidebar-inner px-3 pt-3">
        <div class="user-card d-flex align-items-center justify-content-between pb-4">
            <div class="d-flex align-items-center justify-content-start">
                <div class="d-block">
                    <?php if (!empty($this->CURUSER->user[0]->username)) { ?>
                        <span class="mt-1 sidebar-text"><?php echo $this->CURUSER->user[0]->username; ?></span>
                    <?php } ?>
                    <!-- <h2 class="h5 mb-3">fff</h2>
                    <a href="<?php //echo $http 
                                ?>/logout" class="btn btn-danger btn-sm d-inline-flex align-items-center">
                        <span class="icon icon-xs me-1"><i class="bi bi-box-arrow-right"></i></span> Sign Out
                    </a> -->
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
        <ul class="nav flex-column pt-3 pt-md-0 bar">
            <li class="nav-item py-3">
                <span class="sidebar-icon">
                    <img src="<?php echo $http
                                ?>/assets/img/logo-300.png" height="20" alt="Logo">
                </span>
                <span class="mt-1 ms-1 sidebar-text">Notification system</span>
            </li>
            <?php if (checkPermission('การแจ้งเตือน', $this->CURUSER->user[0]->dep_id, $this->role) || $this->CURUSER->user[0]->user_type == 'Cus') { ?>
                <li class="nav-item">
                    <a href="<?php echo $http ?>/invoice" class="nav-link">
                        <span class="sidebar-icon"></span>
                        <span class="sidebar-text">การแจ้งเตือน</span>
                    </a>
                </li>
            <?php } ?>
            <?php if (checkPermission('รายงาน', $this->CURUSER->user[0]->dep_id, $this->role) || $this->CURUSER->user[0]->user_type == 'Cus') { ?>
                <li class="nav-item">
                    <a href="<?php echo $http ?>/report" class="nav-link">
                        <span class="sidebar-icon"></span>
                        <span class="sidebar-text">รายงาน</span>
                    </a>
                </li>
            <?php } ?>
            <?php if ($this->CURUSER->user[0]->user_type == 'Emp' && checkPermission('ตั้งค่า', $this->CURUSER->user[0]->dep_id, $this->role)) { ?>
                <li class="nav-item">
                    <a href="<?php echo $http ?>/customer" class="nav-link">
                        <span class="sidebar-icon"></span>
                        <span class="sidebar-text">ข้อมูลลูกค้า</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $http . '/setting?tab=invoice' ?>" class="nav-link">
                        <span class="sidebar-icon"></span>
                        <span class="sidebar-text">แก้ไขคอลัมน์และซ่อมข้อมูล</span>
                    </a>
                </li>
            <?php }; ?>
            <li class="nav-item">
                <a href="<?php echo $http ?>/access/logout" class="nav-link smo">
                    <span class="sidebar-icon"></span>
                    <span class="sidebar-text">Back to smo</span>
                </a>
            </li>
        </ul>
    </div>
</nav>