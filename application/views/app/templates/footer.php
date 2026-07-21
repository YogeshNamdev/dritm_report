<!-- ===================== FOOTER ===================== -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark"></aside>

<!-- Main Footer -->
<footer class="main-footer ft-bar">
    <div class="ft-left">
        <span class="ft-copy">&copy; <?php echo date("Y"); ?>&ndash;<?php echo date("Y", strtotime("+1 year")); ?></span>
        <a href="<?php echo WEB_URL; ?>" target="_blank" class="ft-brand"><?php echo APP_TITLE; ?></a>
        <span class="ft-sep">|</span>
        <span class="ft-rights">All rights reserved.</span>
    </div>
    <div class="ft-right">
        <span class="ft-version-label">Version</span>
        <span class="ft-version-badge"><?php echo VERSION_NO; ?></span>
    </div>
</footer>

</div><!-- /.wrapper -->

<style>
/* ---- Footer ---- */
.main-footer.ft-bar {
    background: #fff !important;
    border-top: 1px solid #e8eaf0 !important;
    box-shadow: none !important;
    padding: 0 1.5rem !important;
    min-height: 46px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap;
    gap: 6px;
}

.ft-left {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.ft-copy   { font-size: 12px; color: #aaa; }
.ft-brand  { font-size: 12px; font-weight: 700; color: #1a5fa5; text-decoration: none; }
.ft-brand:hover { text-decoration: underline; }
.ft-sep    { font-size: 12px; color: #ddd; }
.ft-rights { font-size: 12px; color: #aaa; }

.ft-right {
    display: flex;
    align-items: center;
    gap: 6px;
}
.ft-version-label { font-size: 11px; color: #bbb; }
.ft-version-badge {
    font-size: 11px;
    font-weight: 700;
    color: #1a5fa5;
    background: #e6f0fb;
    padding: 2px 9px;
    border-radius: 20px;
}

@media(max-width: 480px) {
    .main-footer.ft-bar { justify-content: center; }
    .ft-right { display: none; }
}
</style>

<!-- ======================== REQUIRED SCRIPTS ======================== -->
<!-- Overlay Scrollbars -->
<script src="<?php echo base_url(); ?>includes/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE -->
<script src="<?php echo base_url(); ?>includes/dist/js/adminlte.js"></script>
<script src="<?php echo base_url(); ?>includes/dist/js/demo.js"></script>

</body>
</html>
