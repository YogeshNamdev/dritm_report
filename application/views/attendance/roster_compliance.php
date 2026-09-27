<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Roster Compliance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex align-items-end">
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Date</label>
                            <input type="date" id="check_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="check_compliance();">Check</button>
                        <span id="day_type_badge" class="ml-3"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header"><h5 class="m-0"><b>Overall</b></h5></div>
                        <div class="card-body p-0"><div id="overall_div"></div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header"><h5 class="m-0"><b>Shift Group</b></h5></div>
                        <div class="card-body p-0"><div id="shift_group_div"></div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header"><h5 class="m-0"><b>LOB / Campaign</b></h5></div>
                        <div class="card-body p-0"><div id="lob_div"></div></div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function renderTable(rows)
{
    var txt = "<table class='table table-bordered table-sm mb-0'>";
    txt += "<thead><tr><th>Rule</th><th>Target</th><th>Actual</th><th></th></tr></thead><tbody>";
    rows.forEach(function(r){
        var badge = r.ok ? "<span class='badge badge-success'>OK</span>" : "<span class='badge badge-danger'>MISMATCH</span>";
        txt += "<tr" + (r.ok ? "" : " style='background:#fdecea;'") + ">";
        txt += "<td>"+r.label+"</td><td>"+r.target+"</td><td>"+r.actual+"</td><td>"+badge+"</td>";
        txt += "</tr>";
    });
    txt += "</tbody></table>";
    return txt;
}

function check_compliance()
{
    var date = $("#check_date").val();
    if(!date){ alert("Date select karo."); return; }

    $("#overall_div, #shift_group_div, #lob_div").html("<center class='p-3'><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_roster_compliance",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            date: date
        },
        cache: false,
        success: function(data){
            if(!data.response){
                alert(data.message);
                return;
            }
            var r = data.report;
            $("#day_type_badge").html("<span class='badge badge-dark'>Day Type: "+r.day_type+"</span>");
            $("#overall_div").html(renderTable(r.overall));
            $("#shift_group_div").html(renderTable(r.shift_group));
            $("#lob_div").html(renderTable(r.lob));
        },
        error: function(){ alert("Server error"); }
    });
}

check_compliance();
</script>