<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">Scholarship & Discount Module</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-5">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title m-0">Scholarship Master</h3>
                        </div>
                        <div class="card-body">
                            <form id="scholarship_master_form" method="post">
                                <div class="form-group">
                                    <label>Scholarship Name</label>
                                    <input type="text" class="form-control" name="name" id="sch_name" placeholder="Enter scholarship name">
                                </div>
                                <div class="form-group">
                                    <label>Scholarship Code</label>
                                    <input type="text" class="form-control" name="code" id="sch_code" placeholder="Optional code">
                                </div>
                                <div class="form-group">
                                    <label>Discount Percent</label>
                                    <input type="number" min="0" step="0.01" class="form-control" name="discount_percent" id="sch_percent" placeholder="Enter discount percent">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" id="sch_description" rows="3" placeholder="Optional description"></textarea>
                                </div>
                                <div id="sch_err_msg" class="mb-2"></div>
                                <button type="button" class="btn btn-primary" id="sch_save_btn" onclick="saveScholarshipMaster();">Save Scholarship</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title m-0">Scholarship Master List</h3>
                        </div>
                        <div class="card-body">
                            <div id="scholarship_list_div"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title m-0">Assign Student Discount</h3>
                        </div>
                        <div class="card-body">
                            <form id="student_discount_form" method="post">
                                <div class="form-group">
                                    <label>Student Admission</label>
                                    <select class="form-control" name="admission_id" id="admission_id" onchange="loadAdmissionDiscounts();">
                                        <option value="0">-- Select Admission --</option>
                                        <?php if($admission_list != FALSE){ foreach($admission_list as $admission){ ?>
                                            <option value="<?= $admission->id; ?>">
                                                <?= $admission->name; ?> - <?= $admission->course_name; ?> - <?= $admission->admission_no; ?>
                                            </option>
                                        <?php }} ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Discount Category</label>
                                    <select class="form-control" name="discount_category" id="discount_category" onchange="toggleScholarshipFields();">
                                        <option value="SCHOLARSHIP">Scholarship</option>
                                        <option value="REFERENCE">Reference</option>
                                        <option value="SPECIAL">Special</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Academic Year</label>
                                    <input type="number" min="1" class="form-control" name="academic_year_no" id="academic_year_no" value="1">
                                </div>
                                <div class="form-group">
                                    <label>Applicable On</label>
                                    <select class="form-control" name="applicable_on" id="applicable_on">
                                        <option value="TUITION">Tuition</option>
                                        <option value="MISC">Misc</option>
                                        <option value="ALL">All</option>
                                    </select>
                                </div>

                                <div class="form-group" id="scholarship_select_wrap">
                                    <label>Scholarship</label>
                                    <select class="form-control" name="scholarship_id" id="discount_scholarship_id" onchange="applyScholarshipDefaults();">
                                        <option value="0">-- Select Scholarship --</option>
                                        <?php if($scholarship_list){ foreach($scholarship_list as $sch){ ?>
                                            <option value="<?= $sch->id; ?>" data-percent="<?= $sch->discount_percent; ?>" data-name="<?= htmlspecialchars($sch->name, ENT_QUOTES); ?>">
                                                <?= $sch->name; ?> (<?= $sch->discount_percent; ?>%)
                                            </option>
                                        <?php }} ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Discount Label</label>
                                    <input type="text" class="form-control" name="discount_label" id="discount_label" placeholder="Scholarship Discount / Reference Discount">
                                </div>

                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea class="form-control" name="reason" id="discount_reason" rows="3" placeholder="Why this discount is given"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Discount Type</label>
                                    <select class="form-control" name="discount_type" id="discount_type">
                                        <option value="PERCENT">Percent</option>
                                        <option value="AMOUNT">Amount</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Discount Value</label>
                                    <input type="number" min="0" step="0.01" class="form-control" name="discount_value" id="discount_value" placeholder="Enter percent or amount">
                                </div>

                                <div id="discount_err_msg" class="mb-2"></div>
                                <button type="button" class="btn btn-success" id="discount_save_btn" onclick="assignStudentDiscount();">Assign Discount</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title m-0">Assigned Discount Details</h3>
                        </div>
                        <div class="card-body">
                            <div id="ledger_summary" class="mb-3"></div>
                            <div id="student_discount_list_div"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function saveScholarshipMaster()
{
    var name = $("#sch_name").val().trim();
    var percent = $("#sch_percent").val().trim();

    if(name === ""){
        $("#sch_err_msg").html("<span style='color:red;font-weight:bold'>Please enter scholarship name.</span>");
        $("#sch_name").focus();
        return;
    }

    if(percent === "" || parseFloat(percent) <= 0){
        $("#sch_err_msg").html("<span style='color:red;font-weight:bold'>Please enter valid discount percent.</span>");
        $("#sch_percent").focus();
        return;
    }

    var base_url = '<?= base_url(); ?>';
    var formData = new FormData(document.getElementById('scholarship_master_form'));

    $("#sch_save_btn").prop("disabled", true);
    $("#sch_err_msg").html("<span style='color:blue'><i class='fa fa-spinner fa-spin'></i> Saving...</span>");

    $.ajax({
        type: "POST",
        url: base_url + "app/scholarships/save_master",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data){
            if(data.response){
                $("#sch_err_msg").html("<span style='color:green;font-weight:bold'>" + data.message + "</span>");
                $("#scholarship_master_form")[0].reset();
                getAllScholarships();
            }else{
                $("#sch_err_msg").html("<span style='color:red;font-weight:bold'>" + data.message + "</span>");
            }
            $("#sch_save_btn").prop("disabled", false);
        },
        error: function(){
            $("#sch_err_msg").html("<span style='color:red;font-weight:bold'>Server error.</span>");
            $("#sch_save_btn").prop("disabled", false);
        }
    });
}

function getAllScholarships()
{
    var base_url = '<?= base_url(); ?>';
    $("#scholarship_list_div").html("<center><b><i class='fa fa-spinner fa-spin'></i> Loading...</b></center>");

    $.ajax({
        type: "GET",
        url: base_url + "app/scholarships/get_all",
        dataType: "json",
        success: function(data){
            if(data.response && data.all_record.length > 0){
                var txt = "<table class='table table-bordered table-sm' id='scholarship_master_table'>";
                txt += "<thead><tr><th>#</th><th>Name</th><th>Code</th><th>Percent</th><th>Description</th></tr></thead><tbody>";

                $.each(data.all_record, function(index, row){
                    txt += "<tr>";
                    txt += "<td>" + (index + 1) + "</td>";
                    txt += "<td>" + safeText(row.name) + "</td>";
                    txt += "<td>" + safeText(row.code ? row.code : '-') + "</td>";
                    txt += "<td>" + safeText(row.discount_percent) + "%</td>";
                    txt += "<td>" + safeText(row.description ? row.description : '-') + "</td>";
                    txt += "</tr>";
                });

                txt += "</tbody></table>";
                $("#scholarship_list_div").html(txt);

                if($.fn.DataTable.isDataTable('#scholarship_master_table')){
                    $('#scholarship_master_table').DataTable().destroy();
                }
                $('#scholarship_master_table').DataTable();

                rebuildScholarshipDropdown(data.all_record);
            }else{
                $("#scholarship_list_div").html("<center style='color:red'><b>No scholarship record found.</b></center>");
                rebuildScholarshipDropdown([]);
            }
        },
        error: function(){
            $("#scholarship_list_div").html("<center style='color:red'><b>Unable to load scholarship list.</b></center>");
        }
    });
}

function rebuildScholarshipDropdown(rows)
{
    var html = "<option value='0'>-- Select Scholarship --</option>";

    $.each(rows, function(index, row){
        html += "<option value='" + row.id + "' data-percent='" + row.discount_percent + "' data-name=\"" + escapeAttribute(row.name) + "\">";
        html += safeText(row.name) + " (" + safeText(row.discount_percent) + "%)";
        html += "</option>";
    });

    $("#discount_scholarship_id").html(html);
}

function toggleScholarshipFields()
{
    var category = $("#discount_category").val();

    if(category === "SCHOLARSHIP"){
        $("#scholarship_select_wrap").show();
        $("#discount_type").val("PERCENT");
        $("#discount_label").val("Scholarship Discount");
        applyScholarshipDefaults();
    }else{
        $("#scholarship_select_wrap").hide();
        $("#discount_scholarship_id").val("0");
        if($("#discount_label").val().trim() === "" || $("#discount_label").val().trim() === "Scholarship Discount"){
            $("#discount_label").val(category.charAt(0) + category.slice(1).toLowerCase() + " Discount");
        }
    }
}

function applyScholarshipDefaults()
{
    if($("#discount_category").val() !== "SCHOLARSHIP"){
        return;
    }

    var opt = $("#discount_scholarship_id option:selected");
    var percent = opt.data("percent");

    $("#discount_type").val("PERCENT");
    if(percent !== undefined){
        $("#discount_value").val(percent);
    }

    if($("#discount_label").val().trim() === ""){
        $("#discount_label").val("Scholarship Discount");
    }
}

function assignStudentDiscount()
{
    var admissionId = $("#admission_id").val();
    var category = $("#discount_category").val();
    var label = $("#discount_label").val().trim();
    var type = $("#discount_type").val();
    var value = $("#discount_value").val().trim();

    if(admissionId === "0"){
        $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>Please select admission.</span>");
        $("#admission_id").focus();
        return;
    }

    if(category === "SCHOLARSHIP" && $("#discount_scholarship_id").val() === "0"){
        $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>Please select scholarship.</span>");
        $("#discount_scholarship_id").focus();
        return;
    }

    if(label === ""){
        $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>Please enter discount label.</span>");
        $("#discount_label").focus();
        return;
    }

    if(value === "" || parseFloat(value) <= 0){
        $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>Please enter valid discount value.</span>");
        $("#discount_value").focus();
        return;
    }

    var base_url = '<?= base_url(); ?>';
    var formData = new FormData(document.getElementById('student_discount_form'));

    $("#discount_save_btn").prop("disabled", true);
    $("#discount_err_msg").html("<span style='color:blue'><i class='fa fa-spinner fa-spin'></i> Saving...</span>");

    $.ajax({
        type: "POST",
        url: base_url + "app/scholarships/assign_discount",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data){
            if(data.response){
                $("#discount_err_msg").html("<span style='color:green;font-weight:bold'>" + data.message + "</span>");
                var currentAdmission = $("#admission_id").val();
                $("#student_discount_form")[0].reset();
                $("#admission_id").val(currentAdmission);
                toggleScholarshipFields();
                loadAdmissionDiscounts();
            }else{
                $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>" + data.message + "</span>");
            }
            $("#discount_save_btn").prop("disabled", false);
        },
        error: function(){
            $("#discount_err_msg").html("<span style='color:red;font-weight:bold'>Server error.</span>");
            $("#discount_save_btn").prop("disabled", false);
        }
    });
}

function loadAdmissionDiscounts()
{
    var admissionId = $("#admission_id").val();
    var base_url = '<?= base_url(); ?>';

    if(admissionId === "0"){
        $("#ledger_summary").html("");
        $("#student_discount_list_div").html("<center><b>Select admission to view assigned discounts.</b></center>");
        return;
    }

    $("#student_discount_list_div").html("<center><b><i class='fa fa-spinner fa-spin'></i> Loading...</b></center>");

    $.ajax({
        type: "POST",
        url: base_url + "app/scholarships/get_admission_discounts",
        data: { admission_id: admissionId, academic_year_no: $("#academic_year_no").val() || 1 },
        dataType: "json",
        success: function(data){
            if(data.response){
                renderLedgerSummary(data.ledger);
                renderDiscountTable(data.all_record);
            }else{
                $("#ledger_summary").html("");
                $("#student_discount_list_div").html("<center style='color:red'><b>" + data.message + "</b></center>");
            }
        },
        error: function(){
            $("#ledger_summary").html("");
            $("#student_discount_list_div").html("<center style='color:red'><b>Unable to load discount details.</b></center>");
        }
    });
}

function renderLedgerSummary(ledger)
{
    if(!ledger){
        $("#ledger_summary").html("");
        return;
    }

    var html = "<div class='row'>";
    html += "<div class='col-md-3'><div class='small-box bg-info'><div class='inner'><p>Gross Fee</p><h5>Rs. " + safeText(formatAmount(ledger.gross_fee || ledger.total_fee)) + "</h5></div></div></div>";
    html += "<div class='col-md-3'><div class='small-box bg-warning'><div class='inner'><p>Total Discount</p><h5>Rs. " + safeText(formatAmount(ledger.total_discount || 0)) + "</h5></div></div></div>";
    html += "<div class='col-md-3'><div class='small-box bg-success'><div class='inner'><p>Net Fee</p><h5>Rs. " + safeText(formatAmount(ledger.net_fee || ledger.total_fee)) + "</h5></div></div></div>";
    html += "<div class='col-md-3'><div class='small-box bg-danger'><div class='inner'><p>Balance</p><h5>Rs. " + safeText(formatAmount(ledger.balance || 0)) + "</h5></div></div></div>";
    html += "</div>";
    $("#ledger_summary").html(html);
}

function renderDiscountTable(rows)
{
    if(rows.length === 0){
        $("#student_discount_list_div").html("<center><b>No discounts assigned yet.</b></center>");
        return;
    }

    var txt = "<table class='table table-bordered table-sm'><thead><tr><th>#</th><th>Category</th><th>Label</th><th>Reason</th><th>Type</th><th>Value</th><th>Amount</th></tr></thead><tbody>";

    $.each(rows, function(index, row){
        txt += "<tr>";
        txt += "<td>" + (index + 1) + "</td>";
        txt += "<td>" + safeText(row.discount_category) + "</td>";
        txt += "<td>" + safeText(row.discount_label) + "</td>";
        txt += "<td>" + safeText(row.reason ? row.reason : '-') + "</td>";
        txt += "<td>" + safeText(row.discount_type) + "</td>";
        txt += "<td>" + safeText(row.discount_value) + (row.discount_type === 'PERCENT' ? '%' : '') + "</td>";
        txt += "<td>Rs. " + safeText(formatAmount(row.discount_amount)) + "</td>";
        txt += "</tr>";
    });

    txt += "</tbody></table>";
    $("#student_discount_list_div").html(txt);
}

function formatAmount(value)
{
    var number = parseFloat(value || 0);
    return number.toFixed(2);
}

function safeText(value)
{
    return $('<div>').text(value).html();
}

function escapeAttribute(value)
{
    return String(value).replace(/"/g, '&quot;');
}

$(document).ready(function(){
    getAllScholarships();
    toggleScholarshipFields();
    $("#student_discount_list_div").html("<center><b>Select admission to view assigned discounts.</b></center>");
});
</script>
