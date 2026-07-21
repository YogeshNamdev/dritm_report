<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">Batch Fee Plans</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title m-0">Batch Fee Plan Master</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <form id="batch_fee_plan_form" method="post">
                                <div class="form-group">
                                    <label>Batch</label>
                                    <select class="form-control" id="batch_id" name="batch_id" onchange="syncCourseByBatch();">
                                        <option value="0">-- Select Batch --</option>
                                        <?php if($batches){ foreach($batches as $batch){ ?>
                                            <option value="<?= $batch->id; ?>" data-course-id="<?= $batch->course_id; ?>" data-course-name="<?= htmlspecialchars($batch->course_name, ENT_QUOTES); ?>" data-duration="<?= $batch->duration; ?>">
                                                <?= $batch->batch_name; ?> - <?= $batch->course_name; ?>
                                            </option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <input type="hidden" id="course_id" name="course_id">
                                <div class="form-group">
                                    <label>Course</label>
                                    <input type="text" id="course_name" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Yearly Tuition Fee</label>
                                    <input type="number" min="0" step="0.01" class="form-control" id="tuition_fee_yearly" name="tuition_fee_yearly">
                                </div>
                                <div class="form-group">
                                    <label>Tuition Fee Type</label>
                                    <select class="form-control" id="tuition_fee_type_id" name="tuition_fee_type_id">
                                        <option value="0">-- Select --</option>
                                        <?php foreach($fee_types as $type){ ?>
                                            <option value="<?= $type->id; ?>"><?= $type->fee_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>One-Time Admission Fee</label>
                                    <input type="number" min="0" step="0.01" class="form-control" id="admission_fee" name="admission_fee">
                                </div>
                                <div class="form-group">
                                    <label>Admission Fee Type</label>
                                    <select class="form-control" id="admission_fee_type_id" name="admission_fee_type_id">
                                        <option value="0">-- Select --</option>
                                        <?php foreach($fee_types as $type){ ?>
                                            <option value="<?= $type->id; ?>"><?= $type->fee_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Misc Charge Mode</label>
                                    <select class="form-control" id="misc_charge_mode" name="misc_charge_mode">
                                        <option value="YEARLY">Yearly</option>
                                        <option value="SEMESTER">Semester Wise</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Effective From</label>
                                    <input type="date" class="form-control" id="effective_from" name="effective_from">
                                </div>

                                <hr>
                                <h5>Misc Items</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Year</label>
                                        <input type="number" min="1" class="form-control" id="misc_year_no">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Semester</label>
                                        <select class="form-control" id="misc_semester_no">
                                            <option value="">All/NA</option>
                                            <option value="1">Sem 1</option>
                                            <option value="2">Sem 2</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Amount</label>
                                        <input type="number" min="0" step="0.01" class="form-control" id="misc_amount">
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Misc Fee Type</label>
                                    <select class="form-control" id="misc_fee_type_id">
                                        <option value="0">-- Select --</option>
                                        <?php foreach($fee_types as $type){ ?>
                                            <option value="<?= $type->id; ?>"><?= $type->fee_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <input type="text" class="form-control" id="misc_remarks">
                                </div>
                                <button type="button" class="btn btn-info btn-sm" onclick="addMiscRow();">Add Misc Item</button>
                                <div id="misc_rows_wrap" class="mt-3"></div>

                                <div id="plan_err_msg" class="mt-3"></div>
                                <button type="button" id="plan_save_btn" class="btn btn-primary mt-2" onclick="saveBatchFeePlan();">Save Batch Fee Plan</button>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <div id="batch_fee_plan_list_div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var miscRows = [];

function syncCourseByBatch()
{
    var opt = $("#batch_id option:selected");
    $("#course_id").val(opt.data("course-id") || "");
    $("#course_name").val(opt.data("course-name") || "");
}

function addMiscRow()
{
    var feeTypeId = $("#misc_fee_type_id").val();
    var feeTypeName = $("#misc_fee_type_id option:selected").text();
    var yearNo = $("#misc_year_no").val();
    var semesterNo = $("#misc_semester_no").val();
    var amount = $("#misc_amount").val();
    var chargeMode = $("#misc_charge_mode").val();
    var remarks = $("#misc_remarks").val().trim();

    if(feeTypeId === "0" || yearNo === "" || parseInt(yearNo) <= 0 || amount === "" || parseFloat(amount) <= 0){
        $("#plan_err_msg").html("<span style='color:red;font-weight:bold'>Please enter valid misc item details.</span>");
        return;
    }

    if(chargeMode === "YEARLY"){
        semesterNo = "";
    } else if(semesterNo === ""){
        $("#plan_err_msg").html("<span style='color:red;font-weight:bold'>Please select semester for semester-wise misc.</span>");
        return;
    }

    miscRows.push({
        fee_type_id: feeTypeId,
        fee_type_name: feeTypeName,
        academic_year_no: yearNo,
        semester_no: semesterNo,
        amount: amount,
        charge_mode: chargeMode,
        remarks: remarks
    });

    $("#misc_fee_type_id").val("0");
    $("#misc_year_no").val("");
    $("#misc_semester_no").val("");
    $("#misc_amount").val("");
    $("#misc_remarks").val("");
    $("#plan_err_msg").html("");
    renderMiscRows();
}

function renderMiscRows()
{
    if(miscRows.length === 0){
        $("#misc_rows_wrap").html("<div class='text-muted'>No misc items added.</div>");
        return;
    }

    var txt = "<table class='table table-bordered table-sm'><thead><tr><th>#</th><th>Fee Type</th><th>Year</th><th>Semester</th><th>Mode</th><th>Amount</th><th>Remarks</th><th>Action</th></tr></thead><tbody>";
    $.each(miscRows, function(index, row){
        txt += "<tr><td>" + (index + 1) + "</td><td>" + safeText(row.fee_type_name) + "</td><td>" + safeText(row.academic_year_no) + "</td><td>" + safeText(row.semester_no ? row.semester_no : '-') + "</td><td>" + safeText(row.charge_mode) + "</td><td>Rs. " + safeText(parseFloat(row.amount).toFixed(2)) + "</td><td>" + safeText(row.remarks ? row.remarks : '-') + "</td><td><button type='button' class='btn btn-danger btn-sm' onclick='removeMiscRow(" + index + ");'>Remove</button></td></tr>";
    });
    txt += "</tbody></table>";
    $("#misc_rows_wrap").html(txt);
}

function removeMiscRow(index)
{
    miscRows.splice(index, 1);
    renderMiscRows();
}

function saveBatchFeePlan()
{
    var batchId = $("#batch_id").val();
    var courseId = $("#course_id").val();
    var tuitionYearly = $("#tuition_fee_yearly").val();

    if(batchId === "0" || courseId === "" || tuitionYearly === "" || parseFloat(tuitionYearly) <= 0){
        $("#plan_err_msg").html("<span style='color:red;font-weight:bold'>Please select batch and enter valid yearly tuition fee.</span>");
        return;
    }

    var formData = new FormData(document.getElementById('batch_fee_plan_form'));
    formData.append('misc_rows_json', JSON.stringify(miscRows));
    $("#plan_save_btn").prop("disabled", true);
    $("#plan_err_msg").html("<span style='color:blue'><i class='fa fa-spinner fa-spin'></i> Saving...</span>");

    $.ajax({
        type: "POST",
        url: "<?= base_url(); ?>app/batch_fee_plans/save",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data){
            if(data.response){
                $("#plan_err_msg").html("<span style='color:green;font-weight:bold'>" + data.message + "</span>");
                $("#batch_fee_plan_form")[0].reset();
                miscRows = [];
                renderMiscRows();
                getAllBatchFeePlans();
            }else{
                $("#plan_err_msg").html("<span style='color:red;font-weight:bold'>" + data.message + "</span>");
            }
            $("#plan_save_btn").prop("disabled", false);
        },
        error: function(){
            $("#plan_err_msg").html("<span style='color:red;font-weight:bold'>Server error.</span>");
            $("#plan_save_btn").prop("disabled", false);
        }
    });
}

function getAllBatchFeePlans()
{
    $("#batch_fee_plan_list_div").html("<center><b><i class='fa fa-spinner fa-spin'></i> Loading...</b></center>");
    $.getJSON("<?= base_url(); ?>app/batch_fee_plans/get_all", function(data){
        if(data.response){
            var txt = "<table class='table table-bordered table-sm'><thead><tr><th>#</th><th>Batch</th><th>Course</th><th>Tuition/Year</th><th>Admission</th><th>Misc Mode</th><th>Misc Items</th></tr></thead><tbody>";
            $.each(data.all_record, function(index, row){
                var miscHtml = "";
                if(row.misc_items && row.misc_items.length){
                    $.each(row.misc_items, function(i, item){
                        miscHtml += "<div><b>" + safeText(item.fee_name) + "</b> | Year " + safeText(item.academic_year_no) + (item.semester_no ? " | Sem " + safeText(item.semester_no) : "") + " | Rs. " + safeText(parseFloat(item.amount).toFixed(2)) + "</div>";
                    });
                }else{
                    miscHtml = "-";
                }
                txt += "<tr><td>" + (index + 1) + "</td><td>" + safeText(row.batch_name) + "</td><td>" + safeText(row.course_name) + "</td><td>Rs. " + safeText(parseFloat(row.tuition_fee_yearly).toFixed(2)) + "</td><td>Rs. " + safeText(parseFloat(row.admission_fee).toFixed(2)) + "</td><td>" + safeText(row.misc_charge_mode) + "</td><td>" + miscHtml + "</td></tr>";
            });
            txt += "</tbody></table>";
            $("#batch_fee_plan_list_div").html(txt);
        }else{
            $("#batch_fee_plan_list_div").html("<center style='color:red'><b>No batch fee plan found.</b></center>");
        }
    }).fail(function(){
        $("#batch_fee_plan_list_div").html("<center style='color:red'><b>Unable to load batch fee plans.</b></center>");
    });
}

function safeText(value)
{
    return $('<div>').text(value).html();
}

$(document).ready(function(){
    renderMiscRows();
    getAllBatchFeePlans();
});
</script>
