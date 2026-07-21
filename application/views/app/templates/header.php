<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title . " | " . APP_TITLE; ?></title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/fontawesome-free/css/all.min.css">
    <!-- Overlay Scrollbars -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>includes/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>includes/dist/css/adminlte.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* ============================================================
       GLOBAL DESIGN TOKENS  — consistent with Dashboard & Admissions
       ============================================================ */
    :root {
        --color-primary:   #1a5fa5;
        --color-success:   #1a7a4a;
        --color-danger:    #a33030;
        --color-warning:   #a06000;
        --color-sidebar-bg: #0f1829;
        --color-border:    #e8eaf0;
        --color-surface:   #f7f8fb;
        --color-text:      #1a1a2e;
        --color-muted:     #888;
        --radius-card:     12px;
        --radius-btn:      9px;
        --radius-input:    8px;
        --shadow-card:     0 1px 6px rgba(0,0,0,.06);
        --shadow-modal:    0 12px 48px rgba(0,0,0,.15);
    }

    /* ---- Font override ---- */
    body,
    .content-wrapper,
    .main-header,
    .main-sidebar,
    .main-footer,
    .modal,
    button,
    input,
    select,
    textarea {
        font-family: 'DM Sans', sans-serif !important;
    }

    /* ---- Body / wrapper ---- */
    body {
        background: #f3f4f8 !important;
        color: var(--color-text) !important;
    }
    .wrapper { background: #f3f4f8; }
    .content-wrapper { background: #f3f4f8 !important; }

    /* ---- Breadcrumb ---- */
    .breadcrumb {
        background: transparent !important;
        padding: 0 !important;
        font-size: 13px;
    }
    .breadcrumb-item a { color: var(--color-primary); text-decoration: none; }
    .breadcrumb-item.active { color: var(--color-muted); }
    .breadcrumb-item + .breadcrumb-item::before { color: #ccc; }

    /* ---- AdminLTE card overrides ---- */
    .card {
        border: 1px solid var(--color-border) !important;
        border-radius: var(--radius-card) !important;
        box-shadow: var(--shadow-card) !important;
    }
    .card-header {
        background: #fff !important;
        border-bottom: 1px solid var(--color-border) !important;
        border-radius: var(--radius-card) var(--radius-card) 0 0 !important;
        padding: .85rem 1.25rem !important;
    }
    .card-body { padding: 1.25rem !important; }

    /* ---- Bootstrap form overrides ---- */
    .form-control {
        border: 1px solid #dde0ea !important;
        border-radius: var(--radius-input) !important;
        font-size: 14px !important;
        padding: 9px 12px !important;
        color: var(--color-text) !important;
        box-shadow: none !important;
        transition: border-color .2s !important;
    }
    .form-control:focus {
        border-color: var(--color-primary) !important;
        box-shadow: none !important;
    }

    /* ---- Bootstrap button overrides ---- */
    .btn {
        border-radius: var(--radius-btn) !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        padding: 8px 18px !important;
        transition: all .18s !important;
        box-shadow: none !important;
    }
    .btn-primary   { background: var(--color-primary) !important; border-color: var(--color-primary) !important; }
    .btn-primary:hover { background: #144d88 !important; border-color: #144d88 !important; }
    .btn-success   { background: var(--color-success) !important; border-color: var(--color-success) !important; }
    .btn-success:hover { background: #155e38 !important; border-color: #155e38 !important; }
    .btn-danger    { background: var(--color-danger) !important; border-color: var(--color-danger) !important; }
    .btn-danger:hover  { background: #7c2424 !important; border-color: #7c2424 !important; }
    .btn-secondary { background: #f7f8fb !important; border-color: var(--color-border) !important; color: #555 !important; }
    .btn-secondary:hover { background: #eef0f8 !important; color: #333 !important; }
    .btn-sm { padding: 5px 12px !important; font-size: 12px !important; }

    /* ---- Bootstrap badge overrides ---- */
    .badge {
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 4px 9px !important;
        border-radius: 20px !important;
    }
    .badge-success { background: #e6f9f0 !important; color: #1a7a4a !important; }
    .badge-warning { background: #fff4e0 !important; color: #a06000 !important; }
    .badge-danger  { background: #fdeaea !important; color: #a33030 !important; }
    .badge-info    { background: #e6f0fb !important; color: #1a5fa5 !important; }
    .badge-primary { background: #e6f0fb !important; color: #1a5fa5 !important; }

    /* ---- Bootstrap table overrides ---- */
    .table thead th {
        background: var(--color-surface) !important;
        color: #666 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        border-bottom: 1px solid var(--color-border) !important;
        padding: 10px 14px !important;
        white-space: nowrap;
    }
    .table tbody td {
        font-size: 13px !important;
        color: var(--color-text) !important;
        padding: 10px 14px !important;
        vertical-align: middle !important;
        border-color: #f3f4f8 !important;
    }
    .table-bordered { border: 1px solid var(--color-border) !important; }
    .table-hover tbody tr:hover { background: #fafbfd !important; }

    /* ---- Bootstrap modal overrides ---- */
    .modal-content {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: var(--shadow-modal) !important;
        overflow: hidden;
    }
    .modal-header {
        background: #fff !important;
        border-bottom: 1px solid var(--color-border) !important;
        padding: 1rem 1.25rem !important;
    }
    .modal-title { font-size: 15px !important; font-weight: 700 !important; color: var(--color-text) !important; }
    .modal-body  { padding: 1.25rem !important; }
    .modal-footer {
        background: #fff !important;
        border-top: 1px solid var(--color-border) !important;
        padding: .9rem 1.25rem !important;
    }
    .close { font-size: 22px !important; color: #aaa !important; opacity: 1 !important; }
    .close:hover { color: #555 !important; }

    /* ---- Dropdown overrides ---- */
    .dropdown-menu {
        border: 1px solid var(--color-border) !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 32px rgba(0,0,0,.12) !important;
        font-size: 13px !important;
    }
    .dropdown-item {
        font-size: 13px !important;
        padding: 9px 16px !important;
        color: #333 !important;
        transition: background .15s !important;
    }
    .dropdown-item:hover { background: var(--color-surface) !important; }
    .dropdown-divider { border-color: #f0f1f5 !important; }

    /* ---- Content header ---- */
    .content-header {
        padding: 1rem 1.25rem .5rem !important;
    }

    /* ---- Scrollbar ---- */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #d0d2dc; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #b0b2bc; }

    /* ---- Sidebar width ---- */
    .main-sidebar, .main-sidebar::before { width: 250px !important; }
    .content-wrapper,
    .main-footer,
    .main-header { margin-left: 250px !important; }
    @media(max-width: 991px) {
        .content-wrapper,
        .main-footer,
        .main-header { margin-left: 0 !important; }
    }

    /* ---- DataTable styling ---- */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--color-border);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        outline: none;
        font-family: 'DM Sans', sans-serif;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--color-primary);
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--color-border);
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
    }
    .dataTables_wrapper .dt-buttons .dt-button {
        background: var(--color-surface) !important;
        border: 1px solid var(--color-border) !important;
        border-radius: 7px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #555 !important;
        padding: 5px 12px !important;
        margin-right: 4px !important;
        box-shadow: none !important;
        font-family: 'DM Sans', sans-serif !important;
    }
    .dataTables_wrapper .dt-buttons .dt-button:hover {
        background: #eef0f8 !important;
        border-color: #bbb !important;
        color: #333 !important;
    }
    .dataTables_info { font-size: 12px; color: var(--color-muted); }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
    .dataTables_paginate .paginate_button.current {
        background: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
        color: #fff !important;
    }
    .dataTables_paginate .paginate_button:hover {
        background: var(--color-surface) !important;
        border-color: var(--color-border) !important;
        color: var(--color-text) !important;
    }
    </style>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>includes/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>includes/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">



<!-- Page content is injected here by each view -->
<!-- ===================== NAVBAR ===================== -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-bottom:1px solid #e8eaf0;box-shadow:0 1px 8px rgba(0,0,0,.06);min-height:58px">

    <!-- Left: Toggle -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link nav-toggle-btn" data-widget="pushmenu" href="#" role="button" title="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right: User + Fullscreen -->
    <ul class="navbar-nav ml-auto align-items-center" style="gap:4px">

        <!-- User dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link nav-user-btn" data-toggle="dropdown" href="#" role="button">
                <div class="nav-avatar">
                    <?php echo strtoupper(substr($_SESSION["userdata"]["user_name"], 0, 1)); ?>
                </div>
                <div class="nav-user-info d-none d-sm-flex">
                    <span class="nav-user-name"><?php echo $_SESSION["userdata"]["user_name"]; ?></span>
                    <span class="nav-user-role"><?php echo $_SESSION["userdata"]["role_name"]; ?></span>
                </div>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right nav-dropdown-menu">
                <div class="nav-dropdown-header">
                    <div class="nav-dd-avatar">
                        <?php echo strtoupper(substr($_SESSION["userdata"]["user_name"], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="nav-dd-name"><?php echo $_SESSION["userdata"]["user_name"]; ?></div>
                        <div class="nav-dd-role"><?php echo $_SESSION["userdata"]["role_name"]; ?></div>
                    </div>
                </div>
                <div class="nav-dropdown-divider"></div>

                <a href="<?php echo base_url(); ?>app/logout" class="nav-dropdown-item danger">
                    <span class="nav-dd-icon red">
                        <i class="fas fa-power-off" style="font-size:12px"></i>
                    </span>
                    Logout
                </a>
            </div>
        </li>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link nav-icon-btn" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>

<style>
/* ---- Navbar base ---- */
.main-header.navbar {
    padding: 0 1.25rem;
}

/* ---- Toggle button ---- */
.nav-toggle-btn {
    width: 38px; height: 38px;
    display: flex !important; align-items: center; justify-content: center;
    border-radius: 9px;
    color: #555 !important;
    transition: background .18s;
}
.nav-toggle-btn:hover { background: #f0f1f5 !important; }

/* ---- User button ---- */
.nav-user-btn {
    display: flex !important;
    align-items: center;
    gap: 9px;
    padding: 6px 10px !important;
    border-radius: 9px;
    transition: background .18s;
    color: #333 !important;
}
.nav-user-btn:hover { background: #f0f1f5 !important; }

.nav-avatar {
    width: 34px; height: 34px;
    background: #1a5fa5;
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    flex-shrink: 0;
}

.nav-user-info {
    flex-direction: column;
    line-height: 1.2;
}
.nav-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
}
.nav-user-role {
    font-size: 11px;
    color: #999;
}

.nav-chevron {
    font-size: 10px !important;
    color: #aaa;
}

/* ---- Icon button ---- */
.nav-icon-btn {
    width: 38px; height: 38px;
    display: flex !important; align-items: center; justify-content: center;
    border-radius: 9px;
    color: #555 !important;
    transition: background .18s;
}
.nav-icon-btn:hover { background: #f0f1f5 !important; }

/* ---- Dropdown ---- */
.nav-dropdown-menu {
    border: 1px solid #e8eaf0 !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.12) !important;
    padding: 0 !important;
    overflow: hidden;
    min-width: 220px;
    margin-top: 6px !important;
}

.nav-dropdown-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    background: #f7f8fb;
}

.nav-dd-avatar {
    width: 38px; height: 38px;
    background: #1a5fa5;
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700;
    flex-shrink: 0;
}

.nav-dd-name {
    font-size: 13px;
    font-weight: 700;
    color: #1a1a2e;
}
.nav-dd-role {
    font-size: 11px;
    color: #999;
    margin-top: 1px;
}

.nav-dropdown-divider {
    border: none;
    border-top: 1px solid #f0f1f5;
    margin: 0;
}

.nav-dropdown-item {
    display: flex !important;
    align-items: center;
    gap: 10px;
    padding: 11px 16px !important;
    font-size: 13px !important;
    font-weight: 500;
    color: #333 !important;
    text-decoration: none;
    transition: background .15s;
}
.nav-dropdown-item:hover {
    background: #f7f8fb !important;
    color: #1a1a2e !important;
}
.nav-dropdown-item.danger:hover {
    background: #fff5f5 !important;
    color: #a33030 !important;
}

.nav-dd-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.nav-dd-icon.blue { background: #e6f0fb; color: #1a5fa5; }
.nav-dd-icon.red  { background: #fdeaea; color: #a33030; }
</style>
