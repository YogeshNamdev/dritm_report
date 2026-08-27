<?php
$this->config->load('config');
$feedback_allowed_ids = $this->config->item('feedback_done_allowed_emp_ids') ?: array();
$current_emp = (string) ($_SESSION['userdata']['emp_id'] ?? '');
$is_admin = (($_SESSION['userdata']['role_id'] ?? null) == 1);

$can_mark_feedback_done = in_array($current_emp, $feedback_allowed_ids);

// PURANA: $show_feedback_column = $is_admin || $can_mark_feedback_done;
// NAYA: admin ko kabhi nahi dikhega, sirf whitelist wale agent ko
$show_feedback_column = (!$is_admin) && $can_mark_feedback_done;
?>

<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<!-- ============================ -->
<!-- Rich Text Editor (Quill) + Sanitizer -->
<!-- ============================ -->
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.5/dist/purify.min.js"></script>

<script>
function makeDataTable_Basic(tableID, savedPageLength)
 {
  var tableSelector = '#'+tableID;

  if (!$(tableSelector).length || !$.fn.DataTable) {
      return null;
  }

  if ($.fn.DataTable.isDataTable(tableSelector)) {
      $(tableSelector).DataTable().destroy();
  }

  return $(tableSelector).DataTable({
                        destroy: true,
                        ordering: true,
                        lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                        pageLength: savedPageLength || 10,
                        dom: 'Blfrtip',
                        buttons: [
                            'colvis',
                            { extend: 'print', exportOptions: { columns: ':not(.no-export)' }  },
                            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL',  download: 'open', exportOptions: { columns: ':not(.no-export)' } },
                            { extend: 'excelHtml5', exportOptions: { columns: ':not(.no-export)' }, customize: function( xlsx ) { var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c[r^="C"]', sheet).attr( 's', '2' ); }}
                          ]
                 });
 }
</script>
<style>
    .cd-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px 24px;
}
@media (max-width: 767px) {
    .cd-info-grid { grid-template-columns: 1fr; }
}
.cd-info-item { border-bottom: 1px dashed #e0e0e0; padding-bottom: 6px; }
.cd-info-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: .3px; }
.cd-info-value { font-size: 14px; font-weight: 500; word-break: break-word; }
.cd-remarks-box {
    background: #f8f9fa; border-left: 4px solid #007bff;
    padding: 12px 15px; border-radius: 4px; margin-top: 10px; white-space: pre-line;
}
.cd-timeline { position: relative; margin-top: 8px; padding-left: 28px; }
.cd-timeline::before {
    content: ''; position: absolute; left: 8px; top: 4px; bottom: 4px; width: 2px; background: #dee2e6;
}
.cd-timeline-item { position: relative; margin-bottom: 18px; }
.cd-timeline-item::before {
    content: ''; position: absolute; left: -24px; top: 3px; width: 12px; height: 12px;
    border-radius: 50%; background: #007bff; border: 2px solid #fff; box-shadow: 0 0 0 2px #007bff;
}
.cd-timeline-item:nth-child(odd)::before { background: #007bff; box-shadow: 0 0 0 2px #007bff; }
.cd-timeline-item:nth-child(even)::before { background: #17a2b8; box-shadow: 0 0 0 2px #17a2b8; }
.cd-timeline-item:nth-child(odd) .cd-timeline-date { color: #007bff; }
.cd-timeline-item:nth-child(even) .cd-timeline-date { color: #17a2b8; }
.cd-timeline-date { font-size: 12px; font-weight: 700; }
.cd-timeline-remark { font-size: 13.5px; margin-top: 3px; white-space: pre-line; }

/* ---- Status / Priority Badges ---- */
.cd-status-badge { font-size: 13px; padding: 6px 14px; border-radius: 20px; font-weight: 600; letter-spacing: .3px; }
.cd-area-box {
    background: #fff8e1; border-left: 4px solid #ffa000;
    padding: 14px 18px; border-radius: 6px; margin-top: 10px;
    font-size: 13.5px; line-height: 1.9;
}
.cd-area-box b { color: #6d4c00; }

.field-select select.form-control-sm {
    width: 100%;
    height: 34px !important;
    line-height: 1.5;
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    background-color: #fff;
    -webkit-appearance: menulist;
    -moz-appearance: menulist;
    appearance: menulist;
}

.field-select select.form-control-sm:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 .15rem rgba(0,123,255,.25);
    outline: none;
}

/* Table row ko thodi extra breathing room do taaki select clip na ho */
#assignDataTable tbody td { padding: 10px 8px; }


/* ---- Remark rich-text view box ---- */
.remark-view-html {
    background: #f8f9fa; border-left: 4px solid #28a745;
    padding: 14px 18px; border-radius: 6px; min-height: 100px;
}
.remark-view-html p { margin-bottom: 6px; }

/* ---- Quill editor Bootstrap modal ke andar theek se fit ho ---- */
#remark_editor { background: #fff; min-height: 160px; }
.ql-toolbar.ql-snow { border-radius: 6px 6px 0 0; background: #f8f9fa; }
.ql-container.ql-snow { border-radius: 0 0 6px 6px; font-size: 14px; }
.modal .ql-picker-options { z-index: 3000; }

/* ---- Complaint modal header color transition ---- */
#complaintDetailModal .modal-header,
#remarkModal .modal-header { transition: background-color .2s ease; }

/* ---- Field edit pencil ---- */
.field-wrapper .field-value-text { font-weight: 500; }
.field-wrapper .btn-edit-field { font-size: 12px; }

/* ---- Table row polish ---- */
#assignDataTable thead th { background: #343a40; color: #fff; font-size: 13px; vertical-align: middle; }
#assignDataTable tbody td { font-size: 13.5px; vertical-align: middle; }
    </style>
  <!-- Bootstrap Switch -->


<div class="content-wrapper">

    <!-- HEADER -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url(); ?>app/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Correct / Incorrect Data Assign
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- MAIN CARD -->
            <div class="card card-default color-palette-box">

                <!-- CARD HEADER -->
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <!-- Heading -->
                        <h5 class="m-0">
                            <b>Assign Data</b>
                        </h5>

                        <!-- Right Controls -->


                    </div>

                </div>
                <!-- /.card-header -->

                <!-- CARD BODY -->
                <div class="card-body">

                    <div class="row align-items-end">

    <!-- Filter Date -->
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="filter_date">Filter Date</label>
            <input type="date"
                   class="form-control"
                   id="filter_date"
                   name="filter_date"
                   value="<?= date('Y-m-d'); ?>">
        </div>
    </div>

    <?php if (($_SESSION['userdata']['role_id'] ?? null) == 1): ?>
        <!-- Select TL -->
        <div class="col-md-3">
            <div class="form-group mb-0">
                <label for="tl_select">Select TL</label>
                <select id="tl_select" class="form-control">
                    <option value="">-- Select TL --</option>
                    <option value="Sanjesh">Sanjesh</option>
                    <option value="Naina">Naina</option>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <!-- Submit Button -->
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label>&nbsp;</label>
            <button type="button"
                    class="btn btn-primary"
                    onclick="today_assign_data()">
                Submit
            </button>
        </div>
    </div>

</div>
                    <hr>

                    <!-- Table yahan AJAX se load hogi -->
                    <div id="add_detail_model" class="table-responsive">
                        <table id="assignDataTable" class="table table-bordered table-striped table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Comp Date</th>
                                    <th>Comp ID</th>
                                    <th>Agent ID</th>
                                    <th>Phone</th>
                                    <th>TL Name</th>
                                    <th>Correct / Incorrect</th>
                                    <th>Description Error</th>
                                    <th>Remark</th>
                                    <?php if ($is_admin): ?>
                                    <th>TL Feedback</th>
                                    <th class="no-export">TL Action</th>
                                    <?php else: ?>
                                    <th class="no-export">Action</th>
                                    <?php endif; ?>

                                    <?php if ($show_feedback_column): ?>
<th>Agent Feedback</th>
<?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="assignDataBody"></tbody>
                        </table>
                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->

        </div>
        <!-- /.container-fluid -->
    </section>

</div>
<!-- /.content-wrapper -->

<!-- ============================ -->
<!-- COMPLAINT DETAIL MODAL -->
<!-- ============================ -->
<div class="modal fade" id="complaintDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-file-alt mr-2"></i>Complaint Details <span id="cd_compid_badge" class="badge badge-light ml-2"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="complaintDetailBody" style="max-height:75vh; overflow-y:auto;">
                <div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================ -->
<!-- REMARK MODAL (Rich Text) -->
<!-- ============================ -->
<div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="remarkModalTitle">
                    <i class="fas fa-comment-alt mr-2"></i>Add / Edit Remark
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="remark_editor_wrap">
                    <div id="remark_editor"></div>
                </div>
                <div id="remark_view_box" class="remark-view-html" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="remark_close_btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="remark_edit_btn" style="display:none;">
                    <i class="fas fa-pen mr-1"></i>Edit
                </button>
                <button type="button" class="btn btn-primary" id="save_remark_btn"><i class="fas fa-save mr-1"></i>Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feedbackDoneModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-check-circle mr-2"></i>Mark Feedback Done</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>From Time</label>
                    <input type="time" id="feedback_from_time" class="form-control">
                </div>
                <div class="form-group">
                    <label>To Time</label>
                    <input type="time" id="feedback_to_time" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="save_feedback_done_btn">
                    <i class="fas fa-check mr-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var CURRENT_ROLE_ID = <?= (int) ($_SESSION['userdata']['role_id'] ?? 0); ?>;
var $activeRemarkBtn = null;
var assignDataTable = null;
var quillEditor = null;
var remarksMap = {}; // rowId -> { html: '...', saved: '1' / '0' }

var CAN_MARK_FEEDBACK_DONE = <?= $can_mark_feedback_done ? 'true' : 'false'; ?>;
var SHOW_FEEDBACK_COLUMN = <?= $show_feedback_column ? 'true' : 'false'; ?>;



var correctIncorrectOptions = ['Correct', 'Incorrect', 'Recheck'];

var descriptionErrorOptions = [
    'Wrong Attribute', 'Wrong Department', 'Insufficient Description', 'Unclear Description',
    'Demand/Suggestion Category', 'Incomplete Address', 'Typing Error', 'Recheck', 'Correct', 'Other'
];

function escapeHtml(str) {
    return $('<div>').text(str || '').html();
}

function getPlainTextFromHtml(html) {
    return $('<div>').html(html || '').text().trim();
}

function buildSelect(options, selected, cssClass) {
    var html = '<select class="form-control form-control-sm ' + cssClass + '">';
    html += '<option value="">-- Select --</option>';
    $.each(options, function (i, opt) {
        var sel = (opt === selected) ? 'selected' : '';
        html += '<option value="' + opt + '" ' + sel + '>' + opt + '</option>';
    });
    html += '</select>';
    return html;
}

// ---------------------------------------------
// Remark button build (row.id ke against remarksMap me store hota hai)
// saved = "1" -> DB me confirm ho chuka hai -> "View Remark" (readonly, formatting ke saath)
// saved = "0" -> sirf local draft hai -> "Edit Remark" (editable)
// ---------------------------------------------
function buildRemarkButton(rowId, remarkHtml, savedFlag) {
    remarksMap[rowId] = { html: remarkHtml || '', saved: savedFlag };

    var hasRemark = getPlainTextFromHtml(remarkHtml) !== '';

    if (savedFlag === '1' && hasRemark) {
        return '<button type="button" class="btn btn-sm btn-success btn-remark" data-row-id="' + rowId + '">'
             + '<i class="fas fa-eye mr-1"></i>View Remark</button>';
    }
    if (hasRemark) {
        return '<button type="button" class="btn btn-sm btn-warning btn-remark" data-row-id="' + rowId + '">'
             + '<i class="fas fa-pen mr-1"></i>Edit Remark</button>';
    }
    return '<button type="button" class="btn btn-sm btn-outline-secondary btn-remark" data-row-id="' + rowId + '">'
         + '<i class="fas fa-plus mr-1"></i>Add Remark</button>';
}

function initRemarkEditor() {
    if (quillEditor) return;

    quillEditor = new Quill('#remark_editor', {
        theme: 'snow',
        placeholder: 'Remark likhein...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });
}

// function today_assign_data() {
//     var filter_date = $('#filter_date').val();

//     $('#assignDataBody').html('<tr><td colspan="9" class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');

//     $.ajax({
//         url: "<?= base_url(); ?>app/reports/today_assign_data",
//         type: "POST",
//         data: { filter_date: filter_date },
//         dataType: "json",
//         success: function (response) {
//             renderAssignTable(response);
//         },
//         error: function () {
//             $('#assignDataBody').html('<tr><td colspan="9" class="text-center text-danger">Data load karte waqt error aaya</td></tr>');
//         }
//     });
// }

function today_assign_data() {
    var filter_date = $('#filter_date').val();

    $('#assignDataBody').html('<tr><td colspan="9" class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');

    if (CURRENT_ROLE_ID == 1) {
        var tl_name = $('#tl_select').val();

        $.ajax({
            url: "<?= base_url(); ?>app/reports/admin_assign_data",
            type: "POST",
            data: { filter_date: filter_date, tl_name: tl_name },
            dataType: "json",
            success: function (response) { renderAssignTable(response); },
            error: function () {
                $('#assignDataBody').html('<tr><td colspan="9" class="text-center text-danger">Data load karte waqt error aaya</td></tr>');
            }
        });
    } else {
        $.ajax({
            url: "<?= base_url(); ?>app/reports/today_assign_data",
            type: "POST",
            data: { filter_date: filter_date },
            dataType: "json",
            success: function (response) { renderAssignTable(response); },
            error: function () {
                $('#assignDataBody').html('<tr><td colspan="9" class="text-center text-danger">Data load karte waqt error aaya</td></tr>');
            }
        });
    }
}

function loadTlList() {
    if (CURRENT_ROLE_ID != 1) return;

    $.ajax({
        url: "<?= base_url(); ?>app/reports/get_tl_list",
        type: "POST",
        data: { filter_date: $('#filter_date').val() },
        dataType: "json",
        success: function (list) {
            var $sel = $('#tl_select');
            var currentVal = $sel.val();
            $sel.empty().append('<option value="">-- Sabhi TL --</option>');
            $.each(list, function (i, item) {
                $sel.append('<option value="' + escapeHtml(item.tl_name) + '">' + escapeHtml(item.tl_name) + '</option>');
            });
            $sel.val(currentVal); // pehle se selected tha to wahi rakho
        }
    });
}


$(document).on('change', '#filter_date', function () {
    loadTlList();
});

$(document).on('change', '#tl_select', function () {
    today_assign_data();
});

// value already set hai -> text + edit icon dikhao (dropdown hidden rahega peeche)
// value empty hai -> seedha dropdown dikhao
function buildFieldCell(options, selected, cssClass, isEditable) {

    var selectHtml = buildSelect(options, selected, cssClass);
    var hasValue = selected && selected.trim() !== '';

    var displayStyle = hasValue ? 'display:none;' : '';
    var textStyle = hasValue ? '' : 'display:none;';

    var html = '<div class="field-wrapper">';

    html += '<div class="field-text" style="' + textStyle + '">';
    html += '<span class="field-value-text">' + escapeHtml(selected) + '</span> ';
    if (isEditable) {
        html += '<i class="fas fa-pen text-primary btn-edit-field" style="cursor:pointer;" title="Edit"></i>';
    }
    html += '</div>';

    html += '<div class="field-select" style="' + displayStyle + '">' + selectHtml + '</div>';

    html += '</div>';

    return html;
}

// Pencil icon click -> text hide, dropdown show
$(document).on('click', '.btn-edit-field', function () {
    var $wrapper = $(this).closest('.field-wrapper');
    $wrapper.find('.field-text').hide();
    $wrapper.find('.field-select').show();
});

function getTodayDate() {
    var d = new Date();
    var yyyy = d.getFullYear();
    var mm = String(d.getMonth() + 1).padStart(2, '0');
    var dd = String(d.getDate()).padStart(2, '0');
    return yyyy + '-' + mm + '-' + dd;
}

function getDateOnly(datetimeStr) {
    if (!datetimeStr) return '';
    return datetimeStr.split(' ')[0];
}

function buildUpdateButton(createdAt) {
    var today = getTodayDate();
    var assignedDate = getDateOnly(createdAt);

    if (assignedDate === today) {
        return '<button type="button" class="btn btn-sm btn-primary btn-update-row"><i class="fas fa-check mr-1"></i>Update</button>';
    }

    return '<button type="button" class="btn btn-sm btn-secondary" disabled title="Sirf assign wale din hi update ho sakti hai"><i class="fas fa-lock mr-1"></i>Locked</button>';
}


function renderAssignTable(rows) {

    var savedPageIndex = 0;
    var savedPageLength = 10;

    if (assignDataTable) {
        try {
            savedPageIndex = assignDataTable.page.info().page;
            savedPageLength = assignDataTable.page.len();
        } catch (e) {
            savedPageIndex = 0;
            savedPageLength = 10;
        }
        assignDataTable.destroy();
        assignDataTable = null;
    }

    var $tbody = $('#assignDataBody');
    $tbody.empty();

    if (!rows || rows.length === 0) {
        var baseColspan = CURRENT_ROLE_ID == 1 ? 10 : 9;
        if (SHOW_FEEDBACK_COLUMN) baseColspan += 1;

        $tbody.html('<tr><td colspan="' + baseColspan + '" class="text-center">No records found</td></tr>');
        return;
    }

    var isAdmin = (CURRENT_ROLE_ID == 1);

    $.each(rows, function (i, row) {

        var $tr = $('<tr>').attr('data-id', row.id);

        $tr.append('<td>' + escapeHtml(row.compdate) + '</td>');
        $tr.append('<td class="text-primary" style="cursor:pointer; text-decoration:underline; font-weight:600;" onclick="viewComplaintDetails(this)">' + escapeHtml(row.compid) + '</td>');
        $tr.append('<td>' + escapeHtml(row.created_by_agent_id) + '</td>');
        $tr.append('<td>' + escapeHtml(row.phone) + '</td>');
        $tr.append('<td>' + escapeHtml(row.tl_name) + '</td>');

        if (isAdmin) {
            // ---- ADMIN: Agent Feedback column kabhi nahi dikhega ----
            $tr.append('<td>' + (escapeHtml(row.correct_incorrect) || '<span class="text-muted">—</span>') + '</td>');
            $tr.append('<td>' + (escapeHtml(row.description_error) || '<span class="text-muted">—</span>') + '</td>');
            $tr.append('<td>' + buildAdminRemarkCell(row.id, row.remark) + '</td>');
            $tr.append(buildTlFeedbackCells(row));

            // Admin ke liye SHOW_FEEDBACK_COLUMN hamesha false rahega (PHP se hi tay ho chuka),
            // isliye yeh block admin ke liye kabhi nahi chalega — lekin defensive check rakha hai
            if (SHOW_FEEDBACK_COLUMN) {
                $tr.append('<td>' + buildFeedbackDoneCell(row) + '</td>');
            }

        } else {
            var isTodayRow = (getDateOnly(row.created_at) === getTodayDate());

            $tr.append('<td>' + buildFieldCell(correctIncorrectOptions, row.correct_incorrect, 'correct_incorrect_select', isTodayRow) + '</td>');
            $tr.append('<td>' + buildFieldCell(descriptionErrorOptions, row.description_error, 'description_error_select', isTodayRow) + '</td>');
            $tr.append('<td>' + buildRemarkButton(row.id, row.remark, '1') + '</td>');
            $tr.append('<td class="no-export">' + buildUpdateButton(row.created_at) + '</td>');

            // Agent ke liye sirf whitelist wale ko yeh column dikhega
            if (SHOW_FEEDBACK_COLUMN) {
                $tr.append('<td>' + buildFeedbackDoneCell(row) + '</td>');
            }
        }

        $tbody.append($tr);
    });

    assignDataTable = makeDataTable_Basic('assignDataTable', savedPageLength);

    if (assignDataTable) {
        var totalPages = assignDataTable.page.info().pages;
        var targetPage = Math.min(savedPageIndex, Math.max(totalPages - 1, 0));

        assignDataTable.page(targetPage).draw(false);
    }
}

function buildFeedbackDoneCell(row) {
    var isDone = (row.feedback_done === 'Yes');

    if (isDone) {
        return '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Done</span>'
             + '<div class="small text-muted mt-1">' + escapeHtml(row.feedback_time_duration || '') + '</div>';
    }

    if (!CAN_MARK_FEEDBACK_DONE) {
        return '<span class="text-muted">—</span>'; // admin/non-eligible agent ko button nahi, khali dash
    }

    return '<button type="button" class="btn btn-sm btn-outline-success btn-feedback-done" data-row-id="' + row.id + '">'
         + '<i class="fas fa-check mr-1"></i>Feedback Done</button>';
}

function buildTlFeedbackCells(row) {
    var hasTlStatus = row.tl_status && row.tl_status.trim() !== '';

    if (hasTlStatus) {
        var badgeClass = (row.tl_status === 'Correct') ? 'badge-success' : 'badge-danger';

        var statusHtml = '<span class="badge ' + badgeClass + '">' + escapeHtml(row.tl_status) + '</span>'
            + '<div class="small text-muted mt-1">'
            + 'by ' + escapeHtml(row.given_tl_status || '') + '<br>'
            + escapeHtml(row.tl_update_at || '')
            + '</div>';

        return '<td>' + statusHtml + '</td><td class="no-export text-center">—</td>';
    }

    var selectHtml = '<select class="form-control form-control-sm tl_status_select">'
        + '<option value="">-- Select --</option>'
        + '<option value="Correct">Correct</option>'
        + '<option value="Incorrect">Incorrect</option>'
        + '</select>';

    var btnHtml = '<button type="button" class="btn btn-sm btn-primary btn-update-tl-status">'
        + '<i class="fas fa-check mr-1"></i>Update</button>';

    return '<td>' + selectHtml + '</td><td class="no-export">' + btnHtml + '</td>';
}

// ==========================================================
// COMPLAINT DETAIL MODAL
// ==========================================================
function viewComplaintDetails(cell) {
    var compId = $(cell).text().trim();

    if (!compId) return;

    $('#cd_compid_badge').text('');
    $('#complaintDetailBody').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
    $('#complaintDetailModal').modal('show');

    $.ajax({
        url: "<?= base_url(); ?>app/reports/complaint_details",
        type: "POST",
        data: { compId: compId },
        dataType: "json",
success: function (response) {
    if (response.status === 'success') {
        renderComplaintDetailModal(response.details, response.summary, response.area_details);
    } else {
        $('#complaintDetailBody').html(
            '<div class="text-center text-danger p-4">' + escapeHtml(response.message) + '</div>'
        );
    }
},        error: function () {
            $('#complaintDetailBody').html(
                '<div class="text-center text-danger p-4">Server error, dobara try karein</div>'
            );
        }
    });
}

function cdField(label, value) {
    value = (value === null || value === undefined || value === '') ? '—' : value;
    return '<div class="cd-info-item">'
         + '<div class="cd-info-label">' + escapeHtml(label) + '</div>'
         + '<div class="cd-info-value">' + escapeHtml(value) + '</div>'
         + '</div>';
}

function getStatusBadge(status) {
    if (!status) return '<span class="badge badge-secondary cd-status-badge">—</span>';

    var lower = String(status).toLowerCase();
    var cls = 'badge-secondary';

    if (lower.indexOf('close') !== -1 || lower.indexOf('resolved') !== -1) {
        cls = 'badge-success';
    } else if (lower.indexOf('open') !== -1 || lower.indexOf('pending') !== -1) {
        cls = 'badge-danger';
    } else if (lower.indexOf('process') !== -1 || lower.indexOf('progress') !== -1) {
        cls = 'badge-info';
    } else if (lower.indexOf('transfer') !== -1 || lower.indexOf('forward') !== -1) {
        cls = 'badge-warning';
    }

    return '<span class="badge ' + cls + ' cd-status-badge">' + escapeHtml(status) + '</span>';
}

function getPriorityBadge(priority) {
    if (!priority && priority !== 0) return '—';

    var lower = String(priority).toLowerCase();
    var cls = 'badge-secondary';

    if (lower.indexOf('high') !== -1 || priority == '1') cls = 'badge-danger';
    else if (lower.indexOf('medium') !== -1 || priority == '2') cls = 'badge-warning';
    else if (lower.indexOf('low') !== -1 || priority == '3') cls = 'badge-success';

    return '<span class="badge ' + cls + '">' + escapeHtml(priority) + '</span>';
}

function setComplaintModalHeaderColor(status) {
    var $header = $('#complaintDetailModal .modal-header');
    $header.removeClass('bg-primary bg-success bg-danger bg-info bg-warning');

    var lower = String(status || '').toLowerCase();

    if (lower.indexOf('close') !== -1 || lower.indexOf('resolved') !== -1) {
        $header.addClass('bg-success');
    } else if (lower.indexOf('open') !== -1 || lower.indexOf('pending') !== -1) {
        $header.addClass('bg-danger');
    } else if (lower.indexOf('process') !== -1) {
        $header.addClass('bg-info');
    } else {
        $header.addClass('bg-primary');
    }
}

function formatDMY(dateStr) {
    if (!dateStr) return '—';
    // "2025-04-01 21:44:26" -> "01 Apr 2025, 09:44 PM"
    var d = new Date(dateStr.replace(' ', 'T'));
    if (isNaN(d.getTime())) return dateStr;

    var options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    return d.toLocaleString('en-IN', options);
}

function renderComplaintDetailModal(d, summary, areaDetails) {

    $('#cd_compid_badge').text('#' + d.compid);
    setComplaintModalHeaderColor(d.compStatus1);

    var html = '';

    // ---------- TOP: Complaint Info Grid ----------
    html += '<div class="cd-info-grid">';
    html += cdField('Caller Name', (d.CallerName || '') + ' ' + (d.CallerSurname || ''));
    html += cdField('Phone', d.Phoneno);
    html += cdField('Complaint Date', formatDMY(d.compdate));
    html += cdField('District', d.District);
    html += cdField('Department', d.Departname);
    html += cdField('Category', d.CompAttt);

    html += '<div class="cd-info-item"><div class="cd-info-label">Current Status</div>'
         + '<div class="cd-info-value">' + getStatusBadge(d.compStatus1) + '</div></div>';

    //html += cdField('Officer Level', d.OfficerLevel);

    html += '<div class="cd-info-item"><div class="cd-info-label">Coplaint Status</div>'
         + '<div class="cd-info-value">' + getPriorityBadge(d.StatusRemark) + '</div></div>';

    html += cdField('Medium', d.medium);
    html += cdField('Address', d.streetAddress);
    //html += cdField('CM Helpline ID', d.CID);
    html += '</div>';

    // ---------- Complaint Remarks ----------
    if (d.compremarks) {
        html += '<div class="mt-3"><div class="cd-info-label mb-1">Complaint Description</div>';
        html += '<div class="cd-remarks-box">' + escapeHtml(d.compremarks) + '</div></div>';
    }

    if (d.OfficerRemarks) {
        html += '<div class="mt-3"><div class="cd-info-label mb-1">Latest Officer Remark</div>';
        html += '<div class="cd-remarks-box" style="border-color:#28a745;">' + escapeHtml(d.OfficerRemarks) + '</div></div>';
    }

    // ---------- Area Details (naya section) ----------
    if (areaDetails && areaDetails.length > 0) {
        html += '<div class="mt-3"><div class="cd-info-label mb-1"><i class="fas fa-map-marker-alt mr-1"></i>Area Details</div>';
        $.each(areaDetails, function (i, area) {
            // Address field procedure se already HTML (<b> tags) ke saath aata hai,
            // isliye escapeHtml() nahi lagana — seedha render karna hai
            html += '<div class="cd-area-box">' + (area.Address || '—') + '</div>';
        });
        html += '</div>';
    }

    // ---------- BOTTOM: Timeline ----------
    html += '<hr><h6 class="mb-3"><b><i class="fas fa-history mr-1"></i>Remark / Status Timeline</b></h6>';

    if (!summary || summary.length === 0) {
        html += '<div class="text-muted">Koi timeline record nahi mila</div>';
    } else {
        html += '<div class="cd-timeline">';
        $.each(summary, function (i, item) {
            html += '<div class="cd-timeline-item">';
            html += '<div class="cd-timeline-date">' + escapeHtml(item.Date) + '</div>';
            html += '<div class="cd-timeline-remark">' + escapeHtml(item.status) + '</div>';
            html += '</div>';
        });
        html += '</div>';
    }

    $('#complaintDetailBody').html(html);
}

// ==========================================================
// REMARK MODAL (Rich Text) — click / save / update
// ==========================================================

// Remark button click -> modal open (saved ya draft state ke hisaab se)
function showRemarkReadonly(html, isAdminView) {
    $('#remark_editor_wrap').hide();
    var safeHtml = (typeof DOMPurify !== 'undefined') ? DOMPurify.sanitize(html) : html;
    $('#remark_view_box').html(safeHtml || '<span class="text-muted">Koi remark nahi</span>').show();

    $('#save_remark_btn').hide();
    // Sirf agent ko Edit button dikhega, admin ko kabhi nahi
    $('#remark_edit_btn').toggle(!isAdminView);
    $('#remark_close_btn').text('Close');
    $('#remarkModalTitle').html('<i class="fas fa-eye mr-2"></i>View Remark');
    $('#remarkModal .modal-header').removeClass('bg-primary').addClass('bg-success');
}

function showRemarkEditable(html) {
    $('#remark_view_box').hide();
    $('#remark_editor_wrap').show();
    quillEditor.root.innerHTML = html || '';

    $('#save_remark_btn').show();
    $('#remark_edit_btn').hide();
    $('#remark_close_btn').text('Cancel');
    $('#remarkModalTitle').html('<i class="fas fa-edit mr-2"></i>Add / Edit Remark');
    $('#remarkModal .modal-header').removeClass('bg-success').addClass('bg-primary');
}

// Remark button click -> modal open
$(document).on('click', '.btn-remark', function () {
    $activeRemarkBtn = $(this);

    var rowId = $(this).data('row-id');
    var entry = remarksMap[rowId] || { html: '', saved: '0' };

    initRemarkEditor();

    var isAdminView = (CURRENT_ROLE_ID == 1);
    var hasRemarkText = getPlainTextFromHtml(entry.html) !== '';

    if (isAdminView || (entry.saved === '1' && hasRemarkText)) {
        showRemarkReadonly(entry.html, isAdminView);
    } else {
        showRemarkEditable(entry.html);
    }

    $('#remarkModal').modal('show');
});

// Naya: Edit button click -> readonly se editable mode mein switch (sirf agent ke liye)
$(document).on('click', '#remark_edit_btn', function () {
    if (!$activeRemarkBtn) return;

    var rowId = $activeRemarkBtn.data('row-id');
    var entry = remarksMap[rowId] || { html: '' };

    showRemarkEditable(entry.html);
});
function buildAdminRemarkCell(rowId, remarkHtml) {
    var hasRemark = getPlainTextFromHtml(remarkHtml) !== '';

    if (hasRemark) {
        remarksMap[rowId] = { html: remarkHtml || '', saved: '1' };
        return '<button type="button" class="btn btn-sm btn-success btn-remark" data-row-id="' + rowId + '">'
             + '<i class="fas fa-eye mr-1"></i>View Remark</button>';
    }

    return '<span class="text-muted"><i class="fas fa-ban mr-1"></i>No Remark</span>';
}

$(document).on('click', '.btn-update-tl-status', function () {

    var $btn = $(this);
    var $tr = $btn.closest('tr');
    var id = $tr.data('id');

    var tl_status = $tr.find('.tl_status_select').val();
    var selected_tl = $('#tl_select').val(); // upar wale dropdown se currently selected TL

    if (!tl_status) {
        alert('Pehle Correct / Incorrect select karein');
        return;
    }

    $btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: "<?= base_url(); ?>app/reports/update_tl_status",
        type: "POST",
        data: { id: id, tl_status: tl_status, given_tl_name: selected_tl },
        dataType: "json",
        success: function (res) {
            if (res.status === 'success') {
                today_assign_data();
            } else {
                $btn.prop('disabled', false).text('Update');
                alert(res.message);
            }
        },
        error: function () {
            $btn.prop('disabled', false).text('Update');
            alert('Server error, dobara try karein');
        }
    });
});

// Modal Save button -> sirf local draft update (row Update click hone tak server pe nahi jaega)
$('#save_remark_btn').on('click', function () {
    if (!$activeRemarkBtn) return;

    var rowId = $activeRemarkBtn.data('row-id');
    var plainText = quillEditor.getText().trim();
    var rawHtml = (plainText === '') ? '' : quillEditor.root.innerHTML;
    var cleanHtml = (typeof DOMPurify !== 'undefined') ? DOMPurify.sanitize(rawHtml) : rawHtml;

    remarksMap[rowId] = { html: cleanHtml, saved: '0' };

    var newBtnHtml = buildRemarkButton(rowId, cleanHtml, '0');
    $activeRemarkBtn.replaceWith(newBtnHtml);

    $('#remarkModal').modal('hide');
    $activeRemarkBtn = null;
});

// Row Update button -> AJAX save, phir poori table refetch
$(document).on('click', '.btn-update-row', function () {

    var $btn = $(this);
    var $tr = $btn.closest('tr');
    var id = $tr.data('id');

    var correct_incorrect = $tr.find('.correct_incorrect_select').val();
    var description_error = $tr.find('.description_error_select').val();
    var remarkEntry = remarksMap[id] || { html: '' };
    var remark = remarkEntry.html;

    $btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: "<?= base_url(); ?>app/reports/update_assign_data",
        type: "POST",
        data: { id: id, correct_incorrect: correct_incorrect, description_error: description_error, remark: remark },
        dataType: "json",
        success: function (res) {
            if (res.status === 'success') {
                // poori table dobara fetch karo taaki latest dropdown + remark state dikhe
                today_assign_data();
            } else {
                $btn.prop('disabled', false).text('Update');
                alert(res.message);
            }
        },
        error: function () {
            $btn.prop('disabled', false).text('Update');
            alert('Server error, dobara try karein');
        }
    });
});

if (CURRENT_ROLE_ID == 1) {
    loadTlList();
}
today_assign_data();

var $activeFeedbackBtn = null;

$(document).on('click', '.btn-feedback-done', function () {
    $activeFeedbackBtn = $(this);
    $('#feedback_from_time').val('');
    $('#feedback_to_time').val('');
    $('#feedbackDoneModal').modal('show');
});

$('#save_feedback_done_btn').on('click', function () {
    if (!$activeFeedbackBtn) return;

    var rowId = $activeFeedbackBtn.data('row-id');
    var fromTime = $('#feedback_from_time').val();
    var toTime = $('#feedback_to_time').val();

    if (!fromTime || !toTime) {
        alert('From aur To dono time select karein');
        return;
    }

    var $btn = $(this);
    $btn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: "<?= base_url(); ?>app/reports/update_feedback_done",
        type: "POST",
        data: { id: rowId, from_time: fromTime, to_time: toTime },
        dataType: "json",
        success: function (res) {
            $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Save');

            if (res.status === 'success') {
                $('#feedbackDoneModal').modal('hide');
                today_assign_data();
            } else {
                alert(res.message);
            }
        },
        error: function () {
            $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Save');
            alert('Server error, dobara try karein');
        }
    });

    $activeFeedbackBtn = null;
});

</script>