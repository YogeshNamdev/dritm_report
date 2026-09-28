<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">My Roster</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><b>My Roster</b></h5>
                        <input type="month" id="filter_month" class="form-control" style="width:180px;" value="<?= date('Y-m'); ?>" onchange="get_my_roster();">
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="roster_div"></div>
                    <a href="<?= base_url(); ?>app/attendance/request_roster_change" class="btn btn-sm btn-primary mt-3">Request a Change</a>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
function badgeForType(type)
{
    var map = { 'SHIFT':'info', 'WO':'secondary', 'CL':'primary', 'HD':'warning', 'LP':'danger' };
    return map[type] || 'light';
}

function get_my_roster()
{
    var month = $("#filter_month").val();
    $("#roster_div").html("<center><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_my_roster",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', month: month },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#roster_div").html("<center><font color='red'>"+data.message+"</font></center>");
                return;
            }
            if(data.total_record == 0){
                $("#roster_div").html("<center class='text-muted p-3'>Is month ka roster abhi banaya nahi gaya.</center>");
                return;
            }
            var txt = "<table class='table table-bordered table-sm'><thead><tr><th>Date</th><th>Day</th><th>Roster</th></tr></thead><tbody>";
            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                var d = new Date(r.roster_date+"T00:00:00");
                var dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
                var label = r.roster_value;
                if(r.roster_type == "SHIFT"){
                    label = r.shift_start.substring(0,5)+" - "+r.shift_end.substring(0,5);
                } else if(r.roster_type == "WO"){
                    label = (r.wo_type == "TL" ? "TL Choice WO" : "Agent Choice WO");
                }
                txt += "<tr><td>"+r.roster_date+"</td><td>"+dayName+"</td><td><span class='badge badge-"+badgeForType(r.roster_type)+"'>"+label+"</span></td></tr>";
            }
            txt += "</tbody></table>";
            $("#roster_div").html(txt);
        },
        error: function(){ $("#roster_div").html("<center><font color='red'>Server error</font></center>"); }
    });
}

get_my_roster();
</script>