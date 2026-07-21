<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- DataTable Assets -->
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<div class="content-wrapper">

    <!-- HEADER -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right" style="background:transparent;padding:0">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">Admission Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <div class="am-page">

                <!-- PAGE HEADER -->
                <div class="am-page-header">
                    <div>
                        <div class="am-page-title">Admission Master</div>
                        <div class="am-page-sub">Manage all student admissions</div>
                    </div>
                    <button class="am-btn-primary" data-toggle="modal" data-target="#add_admission_modal">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add Admission
                    </button>
                </div>

                <!-- TABLE CARD -->
                <div class="am-card">
                    <div id="admission_list_div">
                        <div class="am-loading">
                            <svg width="20" height="20" fill="none" stroke="#1a5fa5" stroke-width="2" viewBox="0 0 24 24" class="am-spin">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            Loading admissions...
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</div>


<!-- ======================== MODALS ======================== -->

<!-- Add Admission Modal -->
<div class="modal fade" id="add_admission_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Add New Admission</h5>
                    <p>Fill in the details to enrol a student</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="am-modal-body">
                <form id="admission_details" method="post" enctype="multipart/form-data">

                    <div class="am-field">
                        <label>Student Name</label>
                        <select class="am-input" id="student_id" name="student_id">
                            <option value="0">-- Select Student --</option>
                            <?php if($student_list != FALSE): foreach($student_list as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->name ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="am-field">
                        <label>Course</label>
                        <select class="am-input" id="course_id" name="course_id">
                            <option value="0">-- Select Course --</option>
                            <?php if($course_list != FALSE): foreach($course_list as $c): ?>
                            <option value="<?= $c->id ?>"><?= $c->course_name ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="am-field">
                        <label>Batch</label>
                        <select class="am-input" id="batch_id" name="batch_id">
                            <option value="0">-- Select Batch --</option>
                            <?php if($batch_list != FALSE): foreach($batch_list as $b): ?>
                            <option value="<?= $b->id ?>"><?= $b->batch_name ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="am-field">
                        <label>Admission Date</label>
                        <input type="text" name="admission_date" id="admission_date" class="am-input" placeholder="Select date">
                    </div>

                    <div id="err_msg"></div>

                </form>
            </div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Cancel</button>
                <button class="am-btn-primary" id="au_btn" onclick="add_admission()">
                    Save Admission
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Admission Detail Modal -->
<div class="modal fade" id="admissionModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Admission Details</h5>
                    <p>Full fee & installment breakdown</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <input type="hidden" id="student_admission_id">
            <div class="am-modal-body" id="admissionModalBody">
                <div class="am-loading">Loading details...</div>
            </div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Pay Installment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Pay Installment</h5>
                    <p>Enter payment details below</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="am-modal-body">
                <input type="hidden" id="pay_installment_id">
                <input type="hidden" id="pay_ledger_id">
                <div class="am-field">
                    <label>Amount (&#8377;)</label>
                    <input type="number" id="pay_amount" class="am-input">
                </div>
                <div class="am-field">
                    <label>Payment Mode</label>
                    <select id="pay_mode" class="am-input">
                        <option value="CASH">Cash</option>
                        <option value="ONLINE">Online</option>
                        <option value="UPI">UPI</option>
                    </select>
                </div>
                <div class="am-field">
                    <label>Payment Date</label>
                    <input type="date" id="pay_date" class="am-input">
                </div>
            </div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Cancel</button>
                <button class="am-btn-success" onclick="savePayment()">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Payment History</h5>
                    <p>All transactions for this installment</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="am-modal-body" id="historyBody"></div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Process Refund</h5>
                    <p>Enter refund details carefully</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="am-modal-body">
                <input type="hidden" id="refund_admission_id">
                <div class="am-field">
                    <label>Refund Amount (&#8377;)</label>
                    <input type="number" id="refund_amount" class="am-input">
                </div>
                <div class="am-field">
                    <label>Reason</label>
                    <textarea id="refund_reason" class="am-input" rows="3" style="resize:vertical"></textarea>
                </div>
                <div class="am-field">
                    <label>Refund Date</label>
                    <input type="date" id="refund_date" class="am-input">
                </div>
            </div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Cancel</button>
                <button class="am-btn-danger" onclick="saveRefund()">Process Refund</button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content am-modal">
            <div class="am-modal-head">
                <div>
                    <h5>Fee Receipt</h5>
                    <p>Official payment receipt</p>
                </div>
                <button class="am-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="am-modal-body" id="receiptBody"></div>
            <div class="am-modal-footer">
                <button class="am-btn-outline" data-dismiss="modal">Close</button>
                <button class="am-btn-success" onclick="printReceipt()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ======================== STYLES ======================== -->
<style>

/* ---- Base ---- */
.am-page { padding: 0 0 2rem; }

.am-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 1.25rem;
}
.am-page-title { font-size: 20px; font-weight: 700; color: #1a1a2e; }
.am-page-sub   { font-size: 13px; color: #999; margin-top: 2px; }

/* ---- Card ---- */
.am-card {
    background: #fff;
    border: 1px solid #e8eaf0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.25rem;
    min-height: 120px;
}

/* ---- Loading ---- */
.am-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 3rem;
    font-size: 13px;
    color: #999;
}
.am-spin {
    animation: am-spin 1s linear infinite;
}
@keyframes am-spin { to { transform: rotate(360deg); } }

/* ---- DataTable overrides ---- */
#admission_list_div .dataTables_wrapper {
    padding: 1rem 1.25rem;
}
#admission_list_div .dataTables_filter input {
    border: 1px solid #e0e2ea;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 13px;
    outline: none;
}
#admission_list_div .dataTables_filter input:focus {
    border-color: #1a5fa5;
}
#admission_list_div .dataTables_length select {
    border: 1px solid #e0e2ea;
    border-radius: 8px;
    padding: 4px 8px;
    font-size: 13px;
}
#admission_list_div .dt-buttons {
    margin-bottom: .5rem;
}
#admission_list_div .dt-button {
    background: #f7f8fb !important;
    border: 1px solid #e0e2ea !important;
    border-radius: 7px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #555 !important;
    padding: 5px 12px !important;
    margin-right: 4px !important;
    box-shadow: none !important;
    transition: all .18s !important;
}
#admission_list_div .dt-button:hover {
    background: #eef0f8 !important;
    border-color: #bbb !important;
    color: #333 !important;
}

/* ---- Admission Table ---- */
.am-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.am-table thead th {
    background: #f7f8fb;
    color: #666;
    font-weight: 600;
    padding: 11px 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
}
.am-table tbody td {
    padding: 11px 14px;
    color: #333;
    border-bottom: 1px solid #f3f4f8;
    vertical-align: middle;
}
.am-table tbody tr:last-child td { border-bottom: none; }
.am-table tbody tr:hover { background: #fafbfd; }

.am-link {
    color: #1a5fa5;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}
.am-link:hover { color: #0f3d72; text-decoration: underline; }

/* ---- Detail Modal Internals ---- */
.am-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 1.25rem;
}
@media(max-width:520px) { .am-info-grid { grid-template-columns: 1fr; } }

.am-info-item span {
    display: block;
    font-size: 11px;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 2px;
}
.am-info-item strong { font-size: 14px; color: #1a1a2e; }

.am-fee-tiles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 10px;
    margin-bottom: 1.25rem;
}
.am-fee-tile {
    background: #f7f8fb;
    border-radius: 10px;
    padding: .8rem 1rem;
    text-align: center;
}
.am-fee-tile-label { font-size: 11px; color: #999; margin-bottom: 5px; }
.am-fee-tile-val   { font-size: 17px; font-weight: 700; color: #1a1a2e; }
.am-fee-tile-val.green { color: #1a7a4a; }
.am-fee-tile-val.red   { color: #a33030; }
.am-fee-tile-val.blue  { color: #1a5fa5; }
.am-fee-tile-val.amber { color: #a06000; }

.am-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 1.25rem 0 .75rem;
    padding-bottom: 6px;
    border-bottom: 1px solid #f0f1f5;
}

.am-inst-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.am-inst-table thead th {
    background: #f7f8fb;
    color: #666;
    font-weight: 600;
    padding: 9px 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
}
.am-inst-table tbody td {
    padding: 9px 12px;
    color: #333;
    border-bottom: 1px solid #f3f4f8;
    vertical-align: middle;
}
.am-inst-table tbody tr:last-child td { border-bottom: none; }
.am-inst-table tbody tr:hover { background: #fafbfd; }

.am-refund-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: #fff5f5;
    border: 1px solid #fcd4d4;
    border-radius: 8px;
    font-size: 13px;
    color: #a33030;
    margin-top: 8px;
}
.am-refund-item svg { flex-shrink: 0; }

.am-history-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f8;
    font-size: 13px;
}
.am-history-item:last-child { border-bottom: none; }

/* ---- Receipt ---- */
.am-receipt-header {
    text-align: center;
    padding-bottom: 1rem;
    border-bottom: 1px dashed #ddd;
    margin-bottom: 1rem;
}
.am-receipt-header h4 { font-size: 18px; font-weight: 700; color: #1a1a2e; margin: 0; }
.am-receipt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    font-size: 13px;
    margin-bottom: 1rem;
}
@media(max-width:420px) { .am-receipt-grid { grid-template-columns: 1fr; } }
.am-receipt-item span   { display: block; font-size: 11px; color: #999; margin-bottom: 2px; }
.am-receipt-item strong { font-size: 14px; color: #1a1a2e; }
.am-receipt-amount {
    text-align: center;
    padding: 1rem 0 .5rem;
    border-top: 1px dashed #ddd;
}
.am-receipt-amount span { font-size: 12px; color: #999; display: block; }
.am-receipt-amount strong { font-size: 28px; font-weight: 700; color: #1a7a4a; }

/* ---- Badges ---- */
.am-badge {
    font-size: 11px; font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    display: inline-block; white-space: nowrap;
}
.am-badge.success { background: #e6f9f0; color: #1a7a4a; }
.am-badge.warning { background: #fff4e0; color: #a06000; }
.am-badge.danger  { background: #fdeaea; color: #a33030; }
.am-badge.info    { background: #e6f0fb; color: #1a5fa5; }

/* ---- Buttons ---- */
.am-btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    background: #1a5fa5; color: #fff;
    border: none; border-radius: 9px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: background .2s; white-space: nowrap;
}
.am-btn-primary:hover { background: #144d88; }

.am-btn-success {
    display: inline-flex; align-items: center; gap: 6px;
    background: #1a7a4a; color: #fff;
    border: none; border-radius: 9px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: background .2s;
}
.am-btn-success:hover { background: #155e38; }

.am-btn-danger {
    background: #a33030; color: #fff;
    border: none; border-radius: 9px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: background .2s;
}
.am-btn-danger:hover { background: #7c2424; }

.am-btn-outline {
    background: transparent; color: #555;
    border: 1px solid #ddd; border-radius: 9px;
    padding: 9px 18px; font-size: 13px; font-weight: 500;
    cursor: pointer; transition: all .2s;
}
.am-btn-outline:hover { border-color: #aaa; color: #333; }

.am-btn-sm-success {
    background: #e6f9f0; color: #1a7a4a;
    border: none; border-radius: 7px;
    padding: 4px 11px; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: background .18s;
}
.am-btn-sm-success:hover { background: #c5f0da; }

.am-btn-sm-info {
    background: #e6f0fb; color: #1a5fa5;
    border: none; border-radius: 7px;
    padding: 4px 11px; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: background .18s;
}
.am-btn-sm-info:hover { background: #cce0f6; }

.am-btn-sm-danger {
    background: #fdeaea; color: #a33030;
    border: none; border-radius: 7px;
    padding: 4px 11px; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: background .18s;
}
.am-btn-sm-danger:hover { background: #f9c8c8; }

/* ---- Modals ---- */
.am-modal {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 12px 48px rgba(0,0,0,.15);
}
.am-modal-head {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f1f5;
}
.am-modal-head h5 { font-size: 15px; font-weight: 700; color: #1a1a2e; margin: 0 0 2px; }
.am-modal-head p  { font-size: 12px; color: #aaa; margin: 0; }
.am-modal-close {
    background: none; border: none; font-size: 22px;
    color: #aaa; cursor: pointer; line-height: 1; flex-shrink: 0;
}
.am-modal-close:hover { color: #555; }
.am-modal-body   { padding: 1.25rem; max-height: 70vh; overflow-y: auto; }
.am-modal-footer {
    padding: .9rem 1.25rem;
    border-top: 1px solid #f0f1f5;
    display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap;
}

/* ---- Form Fields ---- */
.am-field { margin-bottom: 14px; }
.am-field label {
    display: block; font-size: 12px; font-weight: 600;
    color: #666; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .4px;
}
.am-input {
    width: 100%; padding: 9px 12px;
    border: 1px solid #dde0ea; border-radius: 8px;
    font-size: 14px; color: #333; background: #fff;
    transition: border-color .2s; outline: none;
}
.am-input:focus { border-color: #1a5fa5; }

/* ---- Error / Success messages ---- */
.am-msg { font-size: 13px; font-weight: 600; padding: 6px 0; }
.am-msg.success { color: #1a7a4a; }
.am-msg.error   { color: #a33030; }
.am-msg.loading { color: #1a5fa5; }

/* ---- Divider ---- */
.am-divider { border: none; border-top: 1px solid #f0f1f5; margin: .5rem 0 1rem; }

/* ---- Responsive ---- */
@media(max-width: 576px) {
    .am-page-header { flex-direction: column; align-items: flex-start; }
    .am-modal-body  { max-height: 60vh; }
}
</style>


<!-- ======================== SCRIPTS ======================== -->
<script>

/* ---- DataTable Helper ---- */
function makeDataTable_Basic(tableID) {
    $('#' + tableID).DataTable({
        dom: 'Bfrtip',
        buttons: [
            'colvis',
            { extend: 'print',    exportOptions: { columns: ':visible' } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL', download: 'open' },
            { extend: 'excelHtml5', customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="C"]', sheet).attr('s', '2');
            }}
        ],
        language: {
            search: '',
            searchPlaceholder: 'Search admissions...',
            lengthMenu: 'Show _MENU_ entries'
        }
    });
}

/* ===================== ADD ADMISSION ===================== */
function add_admission() {
    var student_id     = $("#student_id").val();
    var course_id      = $("#course_id").val();
    var batch_id       = $("#batch_id").val();
    var admission_date = $("#admission_date").val();

    if (student_id == "0") {
        $("#student_id").focus();
        showMsg("err_msg", "error", "Please select a student.");
        return;
    }
    if (course_id == "0") {
        $("#course_id").focus();
        showMsg("err_msg", "error", "Please select a course.");
        return;
    }
    if (batch_id == "0") {
        $("#batch_id").focus();
        showMsg("err_msg", "error", "Please select a batch.");
        return;
    }

    $("#au_btn").prop("disabled", true);
    showMsg("err_msg", "loading", '<i class="fa fa-spinner fa-spin"></i> Saving...');

    var base_url  = '<?php echo base_url(); ?>';
    var formData  = new FormData(document.getElementById('admission_details'));
    formData.set("admission_date", admission_date);

    $.ajax({
        type: "POST",
        url: base_url + "app/admissions/save",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            if (data.response == true) {
                showMsg("err_msg", "success", "Admission saved successfully!");
                $("#admission_details")[0].reset();
                get_all_admissions();
            } else {
                showMsg("err_msg", "error", data.message);
            }
            $("#au_btn").prop("disabled", false);
        },
        error: function() {
            showMsg("err_msg", "error", "Server error. Please try again.");
            $("#au_btn").prop("disabled", false);
        }
    });
}

function showMsg(id, type, text) {
    $("#" + id).html("<div class='am-msg " + type + "'>" + text + "</div>");
}

/* ===================== LOAD ALL ADMISSIONS ===================== */
function get_all_admissions() {
    $("#admission_list_div").html(
        '<div class="am-loading">' +
        '<svg width="20" height="20" fill="none" stroke="#1a5fa5" stroke-width="2" viewBox="0 0 24 24" class="am-spin">' +
        '<path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>' +
        '</svg>Loading admissions...</div>'
    );

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/admissions/get_all_admissions",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if (data.response == true) {
                var txt = "<div style='overflow-x:auto'>";
                txt += "<table class='am-table' id='tbl_students'>";
                txt += "<thead><tr>";
                txt += "<th>Sr.</th>";
                txt += "<th>Admission ID</th>";
                txt += "<th>Student Name</th>";
                txt += "<th>Course</th>";
                txt += "<th>Batch</th>";
                txt += "<th>Admission Date</th>";
                txt += "</tr></thead><tbody>";

                for (var i = 0; i < data.total_record; i++) {
                    var r = data.all_record[i];
                    txt += "<tr>";
                    txt += "<td>" + (i + 1) + "</td>";
                    txt += "<td><a class='am-link' onclick='openAddModal(" + r.id + ");get_admission_details_by_id();'>" + r.admission_no + "</a></td>";
                    txt += "<td>" + r.name + "</td>";
                    txt += "<td>" + r.course_name + "</td>";
                    txt += "<td>" + r.batch_name + "</td>";
                    txt += "<td>" + r.admission_date + "</td>";
                    txt += "</tr>";
                }

                txt += "</tbody></table></div>";
                $("#admission_list_div").html(txt);
                makeDataTable_Basic("tbl_students");

                $("input[data-bootstrap-switch]").each(function() {
                    $(this).bootstrapSwitch();
                });
            } else {
                $("#admission_list_div").html(
                    "<div class='am-loading' style='color:#a33030'>" + data.message + "</div>"
                );
            }
        }
    });
}

/* ===================== ADMISSION DETAIL ===================== */
function openAddModal(id) {
    $("#student_admission_id").val(id);
    $("#admissionModalBody").html('<div class="am-loading">Loading details...</div>');
    $("#admissionModal").modal("show");
}

function get_admission_details_by_id() {
    var admission_id = $("#student_admission_id").val();
    if (!admission_id || admission_id == 0) { alert("Invalid Request"); return; }

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/admissions/get_admission_details_by_id",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
            'admission_id': admission_id
        },
        cache: false,
        success: function(data) {
            if (data.response == true) {
                var a    = data.all_record.admission;
                var l    = data.all_record.ledger;
                var inst = data.all_record.installments;

                var html = "";

                /* ----- Student Info Grid ----- */
                html += "<div class='am-info-grid'>";
                html += "<div class='am-info-item'><span>Admission No.</span><strong>" + a.admission_no + "</strong></div>";
                html += "<div class='am-info-item'><span>Status</span><strong><span class='am-badge success'>" + a.status + "</span></strong></div>";
                html += "<div class='am-info-item'><span>Student Name</span><strong>" + a.name + "</strong></div>";
                html += "<div class='am-info-item'><span>Mobile</span><strong>" + a.mobile + "</strong></div>";
                html += "<div class='am-info-item'><span>Admission Date</span><strong>" + a.admission_date + "</strong></div>";
                html += "</div>";

                /* ----- Fee Summary Tiles ----- */
                html += "<div class='am-fee-tiles'>";
                html += "<div class='am-fee-tile'><div class='am-fee-tile-label'>Total Fee</div><div class='am-fee-tile-val blue'>&#8377; " + l.total_fee + "</div></div>";
                html += "<div class='am-fee-tile'><div class='am-fee-tile-label'>Paid</div><div class='am-fee-tile-val green'>&#8377; " + l.paid + "</div></div>";
                html += "<div class='am-fee-tile'><div class='am-fee-tile-label'>Balance</div><div class='am-fee-tile-val red'>&#8377; " + l.balance + "</div></div>";
                html += "</div>";

                /* ----- Installment Table ----- */
                html += "<div class='am-section-title'>Installments</div>";
                html += "<div style='overflow-x:auto'>";
                html += "<table class='am-inst-table'>";
                html += "<thead><tr><th>#</th><th>Due Date</th><th>Amount</th><th>Status</th><th>Pay</th><th>History</th></tr></thead>";
                html += "<tbody>";

                for (var i = 0; i < inst.length; i++) {
                    var badgeCls = inst[i].status == "PAID" ? "success" : "warning";
                    var amtHtml;

                    if (inst[i].paid_amount == "0.00") {
                        amtHtml = "&#8377; " + (Number(inst[i].amount) - Number(inst[i].paid_amount));
                    } else {
                        amtHtml = "&#8377; " + inst[i].remaining;
                        if (inst[i].fine > 0) {
                            amtHtml += " <small style='color:#a33030;font-weight:600'>+ Fine &#8377;" + inst[i].fine + "</small>";
                        } else {
                            amtHtml += " <small style='color:#aaa'>(Total &#8377; " + inst[i].amount + ")</small>";
                        }
                    }

                    html += "<tr>";
                    html += "<td>" + (i + 1) + "</td>";
                    html += "<td>" + inst[i].due_date + "</td>";
                    html += "<td>" + amtHtml + "</td>";
                    html += "<td><span class='am-badge " + badgeCls + "'>" + inst[i].status + "</span></td>";
                    html += "<td><button class='am-btn-sm-success' onclick='openPaymentModal(" + inst[i].id + "," + inst[i].ledger_id + "," + inst[i].remaining + ")'>Pay</button></td>";
                    html += "<td><button class='am-btn-sm-info' onclick='viewPaymentHistory(" + inst[i].id + ")'>History</button></td>";
                    html += "</tr>";
                }

                html += "</tbody></table></div>";

                /* ----- Refund Section ----- */
                html += "<div class='am-section-title'>Refunds</div>";
                html += "<button class='am-btn-sm-danger' onclick='openRefundModal(" + a.id + ")'>Process Refund</button>";

                if (data.all_record.refunds && data.all_record.refunds.length > 0) {
                    data.all_record.refunds.forEach(function(r) {
                        html += "<div class='am-refund-item'>";
                        html += "<svg width='14' height='14' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M3 12a9 9 0 109 9'/><polyline points='3 3 3 12 12 12'/></svg>";
                        html += "Refund of <strong>&#8377;" + r.amount + "</strong> &nbsp;|&nbsp; " + r.refund_date;
                        html += "</div>";
                    });
                } else {
                    html += "<p style='color:#aaa;font-size:13px;margin-top:8px'>No refunds processed.</p>";
                }

                $("#admissionModalBody").html(html);
            }
        }
    });
}

/* ===================== SAVE PAYMENT ===================== */
function savePayment() {
    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/payments/save_payment",
        data: {
            installment_id: $("#pay_installment_id").val(),
            ledger_id:      $("#pay_ledger_id").val(),
            amount:         $("#pay_amount").val(),
            payment_mode:   $("#pay_mode").val(),
            payment_date:   $("#pay_date").val()
        },
        success: function(data) {
            alert(data.message);
            $("#paymentModal").modal("hide");
            get_admission_details_by_id();
        }
    });
}

function openPaymentModal(inst_id, ledger_id, amount) {
    $("#pay_installment_id").val(inst_id);
    $("#pay_ledger_id").val(ledger_id);
    $("#pay_amount").val(amount);
    $("#pay_date").val(new Date().toISOString().split('T')[0]);
    $("#paymentModal").modal("show");
}

/* ===================== PAYMENT HISTORY ===================== */
function viewPaymentHistory(id) {
    var base_url = '<?php echo base_url(); ?>';

    $.post(base_url + "app/payments/history", { installment_id: id }, function(res) {
        var html = "";
        if (res.length === 0) {
            html = "<p style='color:#aaa;font-size:13px;text-align:center;padding:1rem'>No payment records found.</p>";
        } else {
            res.forEach(function(p) {
                html += "<div class='am-history-item'>";
                html += "<div>";
                html += "<div style='font-weight:600;color:#1a1a2e;font-size:13px'>&#8377; " + p.amount + " &nbsp;<span style='font-weight:400;color:#888'>via " + p.payment_mode + "</span></div>";
                html += "<div style='font-size:12px;color:#aaa'>" + p.payment_date + " &nbsp;&bull;&nbsp; " + p.receipt_no + "</div>";
                html += "</div>";
                html += "<button class='am-btn-sm-info' onclick='openReceipt(" + p.id + ")'>Receipt</button>";
                html += "</div>";
            });
        }
        $("#historyBody").html(html);
        $("#historyModal").modal("show");
    }, "json");
}

/* ===================== RECEIPT ===================== */
function openReceipt(payment_id) {
    var base_url = '<?php echo base_url(); ?>';

    $.post(base_url + "app/payments/get_receipt", { payment_id: payment_id }, function(res) {
        if (!res.response) { alert("Receipt not found"); return; }
        var d = res.data;

        var html = "<div id='printArea'>";
        html += "<div class='am-receipt-header'><h4>FEE RECEIPT</h4></div>";
        html += "<div class='am-receipt-grid'>";
        html += "<div class='am-receipt-item'><span>Student</span><strong>" + d.name + "</strong></div>";
        html += "<div class='am-receipt-item'><span>Mobile</span><strong>" + d.mobile + "</strong></div>";
        html += "<div class='am-receipt-item'><span>Admission No.</span><strong>" + d.admission_no + "</strong></div>";
        html += "<div class='am-receipt-item'><span>Receipt No.</span><strong>" + d.receipt_no + "</strong></div>";
        html += "<div class='am-receipt-item'><span>Date</span><strong>" + d.payment_date + "</strong></div>";
        html += "<div class='am-receipt-item'><span>Mode</span><strong>" + d.payment_mode + "</strong></div>";
        html += "</div>";
        html += "<div class='am-receipt-amount'><span>Amount Paid</span><strong>&#8377; " + d.amount + "</strong></div>";
        html += "</div>";

        $("#receiptBody").html(html);
        $("#receiptModal").modal("show");
    }, 'json');
}

function printReceipt() {
    var content = document.getElementById("printArea").innerHTML;
    var w = window.open('');
    w.document.write(
        "<html><head><title>Fee Receipt</title>" +
        "<style>body{font-family:Arial,sans-serif;padding:32px;color:#222}" +
        ".am-receipt-header{text-align:center;border-bottom:1px dashed #ccc;padding-bottom:12px;margin-bottom:14px}" +
        ".am-receipt-header h4{font-size:20px;font-weight:700;margin:0}" +
        ".am-receipt-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:12px 0}" +
        ".am-receipt-item span{font-size:11px;color:#888;display:block;margin-bottom:2px}" +
        ".am-receipt-item strong{font-size:14px;color:#111}" +
        ".am-receipt-amount{text-align:center;border-top:1px dashed #ccc;padding-top:14px;margin-top:6px}" +
        ".am-receipt-amount span{font-size:12px;color:#888;display:block}" +
        ".am-receipt-amount strong{font-size:26px;font-weight:700;color:#1a7a4a}" +
        "</style></head><body>" + content + "</body></html>"
    );
    w.print();
    w.close();
}

/* ===================== REFUND ===================== */
function openRefundModal(id) {
    $("#refund_admission_id").val(id);
    $("#refundModal").modal("show");
}

function saveRefund() {
    var base_url = '<?php echo base_url(); ?>';

    $.post(base_url + "app/refunds/save", {
        admission_id: $("#refund_admission_id").val(),
        amount:       $("#refund_amount").val(),
        reason:       $("#refund_reason").val(),
        refund_date:  $("#refund_date").val()
    }, function(res) {
        alert(res.message);
        $("#refundModal").modal("hide");
        get_admission_details_by_id();
    }, "json");
}

/* ===================== UPLOAD DOCS ===================== */
function uploadStudentDocs() {
    var id    = $("#student_id_for_docs").val();
    var files = $("#docs_files")[0].files;
    var formData = new FormData();
    formData.append("student_id", id);
    for (var i = 0; i < files.length; i++) { formData.append("docs[]", files[i]); }

    $.ajax({
        url: "<?php echo base_url(); ?>app/students/upload_docs",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            if (res.status == true) {
                alert("Uploaded Successfully");
                $("#admissionModal").modal("hide");
                get_all_admissions();
            } else {
                alert(res.msg);
            }
        }
    });
}

/* ---- Init ---- */
get_all_admissions();

</script>
