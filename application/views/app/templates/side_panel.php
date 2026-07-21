<!-- ===================== SIDEBAR ===================== -->
<aside class="main-sidebar elevation-0" style="background:#0f1829;border-right:1px solid rgba(255,255,255,.06)">

    <!-- Brand -->
    <a href="<?php echo base_url(); ?>app/index" class="brand-link sb-brand">
        <div class="sb-brand-icon">
            <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <img src="<?php echo base_url(); ?>includes/dist/img/logo1.png"
             alt="<?php echo APP_TITLE; ?>"
             class="sb-brand-img"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <img src="<?php echo base_url(); ?>includes/dist/img/drit-logo.gif"
        alt="<?php echo APP_TITLE; ?>"
        class="sb-brand-img"
        onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
        <span class="sb-brand-text" style="display:none"><?php echo APP_TITLE; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar sb-scroll">

        <!-- User Info Strip -->
        <div class="sb-user-strip">
            <div class="sb-user-avatar">
                <?php echo strtoupper(substr($_SESSION["userdata"]["user_name"], 0, 1)); ?>
            </div>
            <div class="sb-user-info">
                <div class="sb-user-name"><?php echo $_SESSION["userdata"]["user_name"]; ?></div>
                <div class="sb-user-role"><?php echo $_SESSION["userdata"]["role_name"]; ?></div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent sb-nav"
                data-widget="treeview" role="menu" data-accordion="false">

                <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2, 3 ))): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/home" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-chart-line"></i></span>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($_SESSION["userdata"]["role_id"] == 1): ?>

                <!-- Section Label -->
                <div class="sb-section-label" style="margin-top:1rem">Admin</div>

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/users/roles" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-user-shield"></i></span>
                        <p>Roles</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/users" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-users"></i></span>
                        <p>Users</p>
                    </a>
                </li>

                <?php endif; ?>

                <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2, 3, 4))): ?>

                <div class="sb-section-label" style="margin-top:1rem">Reports</div>

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/NoOrSameResolutionDetails" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                        </span>
                        <p>No or Same Resolution Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/CopyPasteResolutionReport" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </span>
                        <p>Copy/Paste Resolution Report</p>
                    </a>
                </li>

                 <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/DirectionReport" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </span>
                        <p>Direction Report</p>
                    </a>
                </li>
                 

                <?php endif; ?>

                <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2))): ?>

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/CallbackReport" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-phone-alt"></i></span>
                        <p>Callback Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/NameChangeReport" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-user-edit"></i></span>
                        <p>Name Change Report</p>
                    </a>
                </li>

                <?php endif; ?>
                <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2, 3, 4))): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/RemarkFormats" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-clipboard-list"></i></span>
                        <p>Remark Formats</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION["userdata"]["role_id"], array(1, 2, 3, 4, 5))): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/TATManagement" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-clock"></i></span>
                        <p>TAT Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/PSMAuditDashboard" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-random"></i></span>
                        <p>PSM Audit Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/SendToLowerLevelDashboard" class="nav-link sb-link">
                        <span class="sb-link-icon"><i class="fas fa-level-down-alt"></i></span>
                        <p>Send to Lower Level</p>
                    </a>
                </li>
                <?php endif; ?>

                 <?php if(in_array($_SESSION["userdata"]["role_id"], array(1,3, 4))): ?>

                    <li class="nav-item">
                        <a href="<?php echo base_url(); ?>app/Reports/owaform" class="nav-link sb-link">
                            <span class="sb-link-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                                </svg>
                            </span>
                            <p>OWA Form</p>
                        </a>
                    </li>
                        <li class="nav-item">
                        <a href="<?php echo base_url(); ?>app/Reports/owalist" class="nav-link sb-link">
                            <span class="sb-link-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                                </svg>
                            </span>
                            <p>OWA Report</p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($_SESSION["userdata"]["role_id"] != 5): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/CreatationReport" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </span>
                        <p>Creation Report</p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($_SESSION["userdata"]["role_id"] == 5): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/DisasterReport" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </span>
                        <p>Disaster Report</p>
                    </a>
                </li>
                <li class="nav-item">
                <a href="<?php echo base_url(); ?>app/Reports/PotralIssueReport" class="nav-link sb-link">
                    <span class="sb-link-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                        </svg>
                    </span>
                    <p>Portal Issues Report</p>
                </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>app/Reports/oldDisasterReport" class="nav-link sb-link">
                        <span class="sb-link-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </span>
                        <p>Old Disaster Report</p>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </nav>

    </div>
    <!-- /.sidebar -->
</aside>

<style>
/* ---- Sidebar wrapper ---- */
.main-sidebar {
    width: 250px !important;
}
.main-sidebar .sidebar {
    padding: 0 0 1rem;
    overflow-y: auto;
    overflow-x: hidden;
}

/* ---- Brand ---- */
.sb-brand {
    display: flex !important;
    align-items: center;
    gap: 10px;
    padding: 14px 16px !important;
    background: rgba(255,255,255,.04);
    border-bottom: 1px solid rgba(255,255,255,.06);
    min-height: 58px;
    text-decoration: none !important;
}
.sb-brand:hover { background: rgba(255,255,255,.07) !important; }

.sb-brand-icon {
    width: 36px; height: 36px;
    background: #1a5fa5;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.sb-brand-img {
    max-height: 32px;
    max-width: 120px;
    object-fit: contain;
}

.sb-brand-text {
    font-size: 14px;
    font-weight: 700;
    color: #fff;
}

/* ---- User strip ---- */
.sb-user-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    background: rgba(255,255,255,.03);
    border-bottom: 1px solid rgba(255,255,255,.05);
    margin-bottom: 6px;
}

.sb-user-avatar {
    width: 36px; height: 36px;
    background: #1a5fa5;
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700;
    flex-shrink: 0;
}

.sb-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #e8eaf0;
    line-height: 1.2;
}
.sb-user-role {
    font-size: 11px;
    color: rgba(255,255,255,.4);
    margin-top: 1px;
}

/* ---- Section label ---- */
.sb-section-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,.3);
    padding: 10px 18px 4px;
}

/* ---- Nav list ---- */
.sb-nav {
    padding: 0 8px;
}

/* ---- Nav link ---- */
.nav-sidebar .nav-link.sb-link {
    display: flex !important;
    align-items: center;
    gap: 10px;
    padding: 9px 10px !important;
    border-radius: 9px;
    color: rgba(255,255,255,.65) !important;
    font-size: 13px;
    font-weight: 500;
    transition: all .18s;
    margin-bottom: 2px;
    white-space: nowrap;
}

.nav-sidebar .nav-link.sb-link:hover,
.nav-sidebar .nav-link.sb-link.active {
    background: rgba(255,255,255,.08) !important;
    color: #fff !important;
}

.nav-sidebar .nav-link.sb-link.active {
    background: #1a5fa5 !important;
    color: #fff !important;
}

/* ---- Link Icon ---- */
.sb-link-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    background: rgba(255,255,255,.06);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background .18s;
}
.sb-link:hover .sb-link-icon,
.sb-link.active .sb-link-icon {
    background: rgba(255,255,255,.15);
}
.sb-link.active .sb-link-icon {
    background: rgba(255,255,255,.2);
}

/* ---- Arrow ---- */
.sb-arrow {
    margin-left: auto;
    font-size: 11px !important;
    color: rgba(255,255,255,.35) !important;
    transition: transform .2s;
}
.nav-item.menu-open > .nav-link .sb-arrow {
    transform: rotate(-90deg);
}

/* ---- Subtree ---- */
.sb-subtree {
    padding: 2px 0 4px 38px !important;
    margin: 0 !important;
}

.sb-sub-link {
    padding: 7px 10px !important;
    font-size: 13px;
}

.sb-sub-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    flex-shrink: 0;
    transition: background .18s;
}
.sb-sub-link:hover .sb-sub-dot,
.sb-sub-link.active .sb-sub-dot {
    background: #4da6ff;
}

/* ---- Scrollbar ---- */
.sb-scroll::-webkit-scrollbar { width: 4px; }
.sb-scroll::-webkit-scrollbar-track { background: transparent; }
.sb-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

/* ---- Active state auto-detection ---- */
.nav-sidebar .nav-link.sb-link p {
    margin: 0;
    flex: 1;
    line-height: 1;
}
</style>

<script>
/* Auto-highlight active sidebar link based on current URL */
$(document).ready(function () {

    var currentUrl = window.location.href.split('?')[0];

    $('.nav-sidebar .sb-link').removeClass('active');

    $('.nav-sidebar .sb-link').each(function () {

        var href = $(this).attr('href');

        if (!href || href == '#')
            return;

        href = href.split('?')[0];

        // Exact Match
        if (currentUrl === href)
        {
            $(this).addClass('active');

            $(this).closest('.nav-treeview')
                   .prev('.sb-link')
                   .addClass('active');

            $(this).parents('.nav-item')
                   .addClass('menu-open');
        }
    });

});
</script>
