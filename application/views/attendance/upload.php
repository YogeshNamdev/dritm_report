<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Upload Center</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">

                <!-- RAW REPORT UPLOAD -->
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header"><h5 class="m-0"><b>Raw Report Upload</b></h5></div>
                        <div class="card-body">
                            <form id="raw_report_form" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Raw Report File (.xlsx / .csv)</label>
                                    <input type="file" name="raw_report_file" id="raw_report_file" class="form-control" required>
                                </div>
                                <div id="raw_report_msg"></div>
                            </form>
                            <button type="button" class="btn btn-primary" id="raw_report_btn" onclick="upload_raw_report();">Upload</button>
                        </div>
                    </div>
                </div>

                <!-- ROSTER UPLOAD -->
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header"><h5 class="m-0"><b>Roster / Employee Master Upload</b></h5></div>
                        <div class="card-body">
                            <form id="roster_form" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Roster File (.xlsx / .csv)</label>
                                    <input type="file" name="roster_file" id="roster_file" class="form-control" required>
                                </div>
                                <div id="roster_msg"></div>
                            </form>
                            <button type="button" class="btn btn-primary" id="roster_btn" onclick="upload_roster();">Upload</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RECENT UPLOADS -->
            <div class="card card-default mt-3">
                <div class="card-header"><h5 class="m-0"><b>Recent Uploads</b></h5></div>
                <div class="card-body">
                    <div id="uploads_list_div"></div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function makeDataTable_Basic(tableID)
{
    var tableSelector = '#'+tableID;
    if (!$(tableSelector).length || !$.fn.DataTable) return null;
    if ($.fn.DataTable.isDataTable(tableSelector)) $(tableSelector).DataTable().destroy();
    return $(tableSelector).DataTable({ destroy: true, ordering: true });
}

function upload_raw_report()
{
    var file = $("#raw_report_file")[0].files[0];
    if(!file){ $("#raw_report_msg").html("<font color='red'>File select karo.</font>"); return; }

    $("#raw_report_btn").prop("disabled", true);
    $("#raw_report_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Processing...</font>");

    var formData = new FormData($("#raw_report_form")[0]);
    formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>app/attendance/upload_raw_report",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#raw_report_msg").html("<span style='color:"+color+";font-weight:bold'>"+data.message+"</span>");
            if(data.errors && data.errors.length){
                var list = "<ul style='font-size:12px;color:#a00;'>";
                data.errors.forEach(function(e){ list += "<li>"+e+"</li>"; });
                list += "</ul>";
                $("#raw_report_msg").append(list);
            }
            get_recent_uploads();
            $("#raw_report_btn").prop("disabled", false);
        },
        error: function(){
            $("#raw_report_msg").html("<span style='color:red'>Server error</span>");
            $("#raw_report_btn").prop("disabled", false);
        }
    });
}

function upload_roster()
{
    var file = $("#roster_file")[0].files[0];
    if(!file){ $("#roster_msg").html("<font color='red'>File select karo.</font>"); return; }

    $("#roster_btn").prop("disabled", true);
    $("#roster_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Processing...</font>");

    var formData = new FormData($("#roster_form")[0]);
    formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>app/attendance/upload_roster",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#roster_msg").html("<span style='color:"+color+";font-weight:bold'>"+data.message+"</span>");
            if(data.errors && data.errors.length){
                var list = "<ul style='font-size:12px;color:#a00;'>";
                data.errors.forEach(function(e){ list += "<li>"+e+"</li>"; });
                list += "</ul>";
                $("#roster_msg").append(list);
            }
            get_recent_uploads();
            $("#roster_btn").prop("disabled", false);
        },
        error: function(){
            $("#roster_msg").html("<span style='color:red'>Server error</span>");
            $("#roster_btn").prop("disabled", false);
        }
    });
}

function get_recent_uploads()
{
    $("#uploads_list_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_recent_uploads",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            if(data.response == true){
                var txt = "<table class='table table-bordered table-sm' id='tbl_uploads'>";
                txt += "<thead><tr><th>File</th><th>Type</th><th>Uploaded By</th><th>Status</th><th>Total</th><th>Success</th><th>Skipped</th><th>When</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++){
                    var r = data.all_record[i];
                    txt += "<tr>";
                    txt += "<td>"+r.file_name+"</td>";
                    txt += "<td>"+r.file_type+"</td>";
                    txt += "<td>"+(r.uploaded_by_name || '-')+"</td>";
                    txt += "<td>"+r.status+"</td>";
                    txt += "<td>"+r.total_rows+"</td>";
                    txt += "<td>"+r.success_count+"</td>";
                    txt += "<td>"+r.skipped_count+"</td>";
                    txt += "<td>"+r.created_at+"</td>";
                    txt += "</tr>";
                }
                txt += "</tbody></table>";
                $("#uploads_list_div").html(txt);
                makeDataTable_Basic("tbl_uploads");
            } else {
                $("#uploads_list_div").html("<center><font color='red'>"+data.message+"</font></center>");
            }
        }
    });
}

get_recent_uploads();
</script>
