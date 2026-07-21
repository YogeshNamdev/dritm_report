<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/datatables-responsive/css/responsive.bootstrap4.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>includes/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>

<style>
.remark-format-page .template-preview {
    max-width: 520px;
    word-break: break-word;
}
.remark-format-page .template-preview:not(.rich-template-preview) {
    white-space: pre-wrap;
}
.remark-format-page .rich-template-preview {
    max-height: 180px;
    overflow: auto;
}
.remark-format-page .rich-template-preview p {
    margin-bottom: 6px;
}
.remark-format-page .rich-template-preview ul,
.remark-format-page .rich-template-preview ol {
    margin-bottom: 6px;
    padding-left: 22px;
}
.remark-format-page .copy-status {
    display: inline-block;
    min-width: 52px;
    color: #168047;
    font-weight: 700;
    font-size: 12px;
}
.remark-format-page textarea {
    min-height: 130px;
    resize: vertical;
}
.remark-format-page .text-color-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.remark-format-page .text-color-control {
    border: 1px solid #d8dde6;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #fff;
}
.remark-format-page .text-color-control span {
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: #4b5563;
}
.remark-format-page .text-color-control input[type="color"] {
    width: 44px;
    height: 34px;
    border: 0;
    padding: 0;
    background: transparent;
    cursor: pointer;
}
.remark-format-page .remark-title-text {
    font-weight: 800;
    display: inline-block;
}
.remark-format-page .remark-subtitle-text {
    font-weight: 700;
    display: inline-block;
}
.remark-format-page tr.remark-row td {
    vertical-align: middle;
}
@media (max-width: 767px) {
    .remark-format-page .card-header h5 {
        display: block !important;
    }
    .remark-format-page .card-header .btn {
        margin-top: 10px;
        width: 100%;
    }
    .remark-format-page .text-color-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="content-wrapper remark-format-page">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url(); ?>app/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Remark Formats</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h5 class="m-0 d-flex justify-content-between align-items-center">
                        <b>My Remark Formats</b>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_format_model">Add New Format</button>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="remark_format_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add_format_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Remark Format</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="add_remark_format_form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" maxlength="150" required>
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="remark_content" id="remark_content" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Text Colors</label>
                        <div class="text-color-grid">
                            <label class="text-color-control">
                                <span>Title Color</span>
                                <input type="color" name="title_color" id="title_color" value="#212529">
                            </label>
                            <label class="text-color-control">
                                <span>Subtitle Color</span>
                                <input type="color" name="subtitle_color" id="subtitle_color" value="#6c757d">
                            </label>
                        </div>
                    </div>
                    <div id="add_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_btn" onclick="add_remark_format();">Save Format</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_format_model">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Remark Format</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="edit_remark_format_form">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" maxlength="150" required>
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="subtitle" id="edit_subtitle" class="form-control" maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="remark_content" id="edit_remark_content" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Text Colors</label>
                        <div class="text-color-grid">
                            <label class="text-color-control">
                                <span>Title Color</span>
                                <input type="color" name="title_color" id="edit_title_color" value="#212529">
                            </label>
                            <label class="text-color-control">
                                <span>Subtitle Color</span>
                                <input type="color" name="subtitle_color" id="edit_subtitle_color" value="#6c757d">
                            </label>
                        </div>
                    </div>
                    <div id="edit_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_btn" onclick="update_remark_format();">Update Format</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_format_model">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Format</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="delete_id">
                <p class="mb-0">Are you sure you want to delete this saved remark format?</p>
                <div id="delete_err_msg" class="mt-2"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="delete_btn" onclick="delete_remark_format();">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
var remarkFormatRows = {};
var remarkFormatTable = null;
var remarkEditorConfig = {
    height: 220,
    allowedContent: true,
    extraAllowedContent: 'span(*)[style];p(*)[style];div(*)[style];strong;em;u;ol;ul;li;br;blockquote;h1 h2 h3 h4 h5 h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'styles', items: ['FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'clipboard', items: ['Undo', 'Redo'] }
    ],
    removePlugins: 'elementspath',
    resize_enabled: true
};

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function initRemarkEditors() {
    if(typeof CKEDITOR === "undefined") return;
    if(!CKEDITOR.instances.remark_content) {
        CKEDITOR.replace("remark_content", remarkEditorConfig);
    }
    if(!CKEDITOR.instances.edit_remark_content) {
        CKEDITOR.replace("edit_remark_content", remarkEditorConfig);
    }
}

function syncRemarkEditor(editorId) {
    if(typeof CKEDITOR !== "undefined" && CKEDITOR.instances[editorId]) {
        CKEDITOR.instances[editorId].updateElement();
        return CKEDITOR.instances[editorId].getData();
    }
    return $("#" + editorId).val();
}

function setRemarkEditorData(editorId, value) {
    value = value == null ? "" : value;
    $("#" + editorId).val(value);
    if(typeof CKEDITOR !== "undefined" && CKEDITOR.instances[editorId]) {
        CKEDITOR.instances[editorId].setData(value);
    }
}

function getReadableRichText(value) {
    var text = $("<div>").html(value == null ? "" : value).text();
    text = text.replace(/\u00a0/g, " ").replace(/\s+/g, " ");
    return $.trim(text);
}

function hasHtmlMarkup(value) {
    return /<\/?[a-z][\s\S]*>/i.test(value || "");
}

function renderRemarkContent(value) {
    value = value == null ? "" : value;
    if(hasHtmlMarkup(value)) {
        return "<div class='template-preview rich-template-preview'>"+value+"</div>";
    }
    return "<div class='template-preview'>"+escapeHtml(value)+"</div>";
}

function htmlToPlainText(value) {
    if(hasHtmlMarkup(value)) {
        return $("<div>").html(value).text();
    }
    return value == null ? "" : value;
}

function safeTextColor(value, fallback) {
    value = (value || fallback).toString();
    return /^#[0-9a-fA-F]{6}$/.test(value) ? value : fallback;
}

function makeDataTable_Basic(tableID) {
    var tableSelector = "#" + tableID;
    if (!$(tableSelector).length || !$.fn.DataTable) return null;
    if ($.fn.DataTable.isDataTable(tableSelector)) $(tableSelector).DataTable().destroy();
    return $(tableSelector).DataTable({
        destroy: true,
        ordering: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: 'Bfrtip',
        buttons: ['colvis', { extend: 'print', exportOptions: { columns: ':visible' } }, { extend: 'excelHtml5' }]
    });
}

function showMessage(selector, message, success) {
    $(selector).html("<span style='color:"+(success ? "green" : "red")+";font-weight:bold'>"+escapeHtml(message)+"</span>");
}

function validateFormat(titleSelector, subtitleSelector, contentSelector, messageSelector) {
    var title = $(titleSelector).val();
    var subtitle = $(subtitleSelector).val();
    var content = $(contentSelector).val();
    if(title.replace(/ /g, "") == "") {
        showMessage(messageSelector, "Enter title.", false);
        return false;
    }
    if(title.length > 150) {
        showMessage(messageSelector, "Title cannot exceed 150 characters.", false);
        return false;
    }
    if(subtitle.replace(/ /g, "") == "") {
        showMessage(messageSelector, "Enter subtitle.", false);
        return false;
    }
    if(subtitle.length > 200) {
        showMessage(messageSelector, "Subtitle cannot exceed 200 characters.", false);
        return false;
    }
    if(getReadableRichText(content).replace(/ /g, "") == "") {
        showMessage(messageSelector, "Enter description.", false);
        return false;
    }
    return true;
}

function get_all_details() {
    $("#remark_format_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/get_all_remark_formats",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data) {
            if(data.response == true) {
    remarkFormatRows = {};
    var txt = "<table class='table table-bordered table-striped table-sm' style='font-size:13px;' id='tbl_remark_formats'>";
    txt += "<thead><tr><th>Sr.No.</th><th>Title</th><th>Subtitle</th><th>Description</th><th style='width:140px;'>Action</th></tr></thead><tbody>";

    for(var i = 0; i < data.total_record; i++) {
        var row = data.all_record[i];
        row.title_color = safeTextColor(row.title_color, "#212529");
        row.subtitle_color = safeTextColor(row.subtitle_color, "#6c757d");
        remarkFormatRows[row.id] = row;

        txt += "<tr class='remark-row'>";
        txt += "<td>"+(i+1)+"</td>";
        txt += "<td><span class='remark-title-text' style='color:"+row.title_color+"'>"+escapeHtml(row.title)+"</span></td>";
        txt += "<td><span class='remark-subtitle-text' style='color:"+row.subtitle_color+"'>"+escapeHtml(row.subtitle)+"</span></td>";
        txt += "<td>"+renderRemarkContent(row.remark_content)+"</td>";

        txt += "<td class='text-nowrap text-center' style='width:120px;padding:4px;'>";

        // Copy Icon
        txt += "<i class='fa fa-copy text-success mr-3' " +
               "style='cursor:pointer;font-size:18px;' " +
               "title='Copy' " +
               "onclick='copy_format("+row.id+", this)'></i>";

        // Edit Icon
        txt += "<i class='fa fa-edit text-primary mr-3' " +
               "style='cursor:pointer;font-size:18px;' " +
               "title='Edit' " +
               "onclick='open_edit_model("+row.id+")'></i>";

        // Delete Icon
        txt += "<i class='fa fa-trash text-danger' " +
               "style='cursor:pointer;font-size:18px;' " +
               "title='Delete' " +
               "onclick='open_delete_model("+row.id+")'></i>";

        txt += "<span class='copy-status ml-2'></span>";
        txt += "</td></tr>";
    }

    txt += "</tbody></table>";

    $("#remark_format_list_div").html(txt);
    remarkFormatTable = makeDataTable_Basic("tbl_remark_formats");

} else {
    remarkFormatRows = {};
    $("#remark_format_list_div").html("<center><font color='red'><b>"+escapeHtml(data.message)+"</b></font></center>");
}
        }
    });
}

function add_remark_format() {
    syncRemarkEditor("remark_content");
    if(!validateFormat("#title", "#subtitle", "#remark_content", "#add_err_msg")) return;
    $("#add_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/add_remark_format",
        data: new FormData(document.getElementById("add_remark_format_form")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            showMessage("#add_err_msg", data.message, data.response);
            if(data.response) {
                $("#add_remark_format_form")[0].reset();
                setRemarkEditorData("remark_content", "");
                $("#add_format_model").modal("hide");
                get_all_details();
            }
            $("#add_btn").prop("disabled", false);
        }
    });
}

function open_edit_model(id) {
    var row = remarkFormatRows[id];
    if(!row) return;
    $("#hidden_id").val(row.id);
    $("#edit_title").val(row.title);
    $("#edit_subtitle").val(row.subtitle);
    setRemarkEditorData("edit_remark_content", row.remark_content);
    $("#edit_title_color").val(safeTextColor(row.title_color, "#212529"));
    $("#edit_subtitle_color").val(safeTextColor(row.subtitle_color, "#6c757d"));
    $("#edit_err_msg").html("");
    $("#edit_format_model").modal("show");
}

function update_remark_format() {
    syncRemarkEditor("edit_remark_content");
    if(!validateFormat("#edit_title", "#edit_subtitle", "#edit_remark_content", "#edit_err_msg")) return;
    $("#update_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        url: base_url + "app/reports/update_remark_format",
        data: new FormData(document.getElementById("edit_remark_format_form")),
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
            showMessage("#edit_err_msg", data.message, data.response);
            if(data.response) {
                $("#edit_format_model").modal("hide");
                get_all_details();
            }
            $("#update_btn").prop("disabled", false);
        }
    });
}

function open_delete_model(id) {
    $("#delete_id").val(id);
    $("#delete_err_msg").html("");
    $("#delete_format_model").modal("show");
}

function delete_remark_format() {
    var id = $("#delete_id").val();
    $("#delete_btn").prop("disabled", true);
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url + "app/reports/delete_remark_format",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', id:id },
        cache: false,
        success: function(data) {
            showMessage("#delete_err_msg", data.message, data.response);
            if(data.response) {
                $("#delete_format_model").modal("hide");
                get_all_details();
            }
            $("#delete_btn").prop("disabled", false);
        }
    });
}

function copy_format(id, button) {
    var row = remarkFormatRows[id];
    if(!row) return;
    var status = $(button).siblings(".copy-status");
    var htmlContent = row.remark_content || "";
    var plainContent = htmlToPlainText(htmlContent);
    function markCopied() {
        status.text("Copied");
        setTimeout(function(){ status.text(""); }, 1400);
    }
    if(hasHtmlMarkup(htmlContent) && navigator.clipboard && navigator.clipboard.write && window.ClipboardItem) {
        navigator.clipboard.write([
            new ClipboardItem({
                "text/html": new Blob([htmlContent], { type: "text/html" }),
                "text/plain": new Blob([plainContent], { type: "text/plain" })
            })
        ]).then(markCopied);
    } else if(navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(plainContent).then(markCopied);
    } else {
        var temp = $("<textarea>");
        $("body").append(temp);
        temp.val(plainContent).select();
        document.execCommand("copy");
        temp.remove();
        markCopied();
    }
}

$("#add_format_model, #edit_format_model").on("shown.bs.modal", function() {
    if(typeof CKEDITOR === "undefined") return;
    for(var id in CKEDITOR.instances) {
        if(CKEDITOR.instances.hasOwnProperty(id)) {
            CKEDITOR.instances[id].resize("100%", 220);
        }
    }
});

$("#add_format_model").on("hidden.bs.modal", function() {
    $("#add_remark_format_form")[0].reset();
    setRemarkEditorData("remark_content", "");
    $("#title_color").val("#212529");
    $("#subtitle_color").val("#6c757d");
    $("#add_err_msg").html("");
    $("#add_btn").prop("disabled", false);
});

$("#edit_format_model").on("hidden.bs.modal", function() {
    $("#edit_remark_format_form")[0].reset();
    setRemarkEditorData("edit_remark_content", "");
    $("#edit_err_msg").html("");
    $("#update_btn").prop("disabled", false);
});

initRemarkEditors();
get_all_details();
</script>
