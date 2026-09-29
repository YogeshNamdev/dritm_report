<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>

<style>
    :root {
        --mx-p-bg:  #e7f6ea; --mx-p-fg:  #1e7b34; --mx-p-bd:  #bfe6c8;
        --mx-hd-bg: #fff4de; --mx-hd-fg: #a5680a; --mx-hd-bd: #f5d99b;
        --mx-aa-bg: #fdeaea; --mx-aa-fg: #c0392b; --mx-aa-bd: #f5c2be;
        --mx-wo-bg: #eef0f3; --mx-wo-fg: #5a6472; --mx-wo-bd: #d7dbe0;
        --mx-cl-bg: #e7f3ff; --mx-cl-fg: #0b5ea8; --mx-cl-bd: #b6dcff;
        --mx-lv-bg: #f1e9fb; --mx-lv-fg: #6c3fa8; --mx-lv-bd: #dcc7f2;
        --mx-left-bg: #f3e3e3; --mx-left-fg: #8a3b3b; --mx-left-bd: #e2c4c4;
        --mx-in-bg: #e9ebee; --mx-in-fg: #495057; --mx-in-bd: #ced4da;
        --mx-weekend: #fff9ec;
    }

    .mx-toolbar { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 10px 14px; }
    .mx-toolbar .form-group { margin-bottom: 0; }
    .mx-toolbar label { font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #8a94a3; margin-bottom: 3px; font-weight: 600; display: block; }

    .mx-legend { display: flex; flex-wrap: wrap; gap: 8px; margin: 2px 0 14px; }
    .mx-legend .chip { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 3px 10px; border-radius: 20px; border: 1px solid transparent; }
    .mx-legend .chip.p  { background: var(--mx-p-bg);  color: var(--mx-p-fg);  border-color: var(--mx-p-bd); }
    .mx-legend .chip.hd { background: var(--mx-hd-bg); color: var(--mx-hd-fg); border-color: var(--mx-hd-bd); }
    .mx-legend .chip.aa { background: var(--mx-aa-bg); color: var(--mx-aa-fg); border-color: var(--mx-aa-bd); }
    .mx-legend .chip.wo { background: var(--mx-wo-bg); color: var(--mx-wo-fg); border-color: var(--mx-wo-bd); }
    .mx-legend .chip.cl { background: var(--mx-cl-bg); color: var(--mx-cl-fg); border-color: var(--mx-cl-bd); }
    .mx-legend .chip.left { background: var(--mx-left-bg); color: var(--mx-left-fg); border-color: var(--mx-left-bd); }
    .mx-legend .chip.lv { background: var(--mx-lv-bg); color: var(--mx-lv-fg); border-color: var(--mx-lv-bd); }

    #matrix_wrap { overflow: auto; max-height: 68vh; border: 1px solid #e3e6ea; border-radius: 8px; }
    #tbl_matrix { border-collapse: separate; border-spacing: 0; font-size: 12px; white-space: nowrap; width: 100%; }
    #tbl_matrix th, #tbl_matrix td { border-bottom: 1px solid #eef0f2; border-right: 1px solid #eef0f2; padding: 5px 7px; text-align: center; }
    #tbl_matrix td:first-child, #tbl_matrix th:first-child { text-align: left; }

    #tbl_matrix thead th { position: sticky; top: 0; background: #2b3542; color: #fff; z-index: 3; font-weight: 600; border-color: #3a4553; }
    #tbl_matrix thead tr.mx-date-row th { font-size: 12.5px; padding: 6px 7px; }
    #tbl_matrix thead tr.mx-day-row th { font-size: 10px; font-weight: 500; color: #b7c0cc; text-transform: uppercase; padding: 0 7px 5px; top: 27px; }
    #tbl_matrix thead th.mx-weekend { background: #3a3126; }
    #tbl_matrix thead th.mx-summary { background: #1f2733; }

    #tbl_matrix td.sticky-col, #tbl_matrix th.sticky-col { position: sticky; left: 0; background: #fff; z-index: 2; box-shadow: 2px 0 4px rgba(0,0,0,.04); text-align: left; }
    #tbl_matrix th.sticky-col { background: #2b3542; z-index: 4; }
    #tbl_matrix tbody tr:nth-child(even) td.sticky-col { background: #fafbfc; }
    #tbl_matrix tbody tr:hover td { background: #f4f8fd; }
    #tbl_matrix tbody tr:hover td.sticky-col { background: #eef4fb; }
    #tbl_matrix td.mx-weekend { background: var(--mx-weekend); }
    #tbl_matrix tbody tr:hover td.mx-weekend { background: #fdf3dc; }
    #tbl_matrix td.mx-summary { background: #f7f8fa; font-weight: 600; }

    .mx-badge { display: inline-block; min-width: 30px; padding: 2px 7px; border-radius: 5px; font-size: 11px; font-weight: 600; border: 1px solid transparent; }
    .mx-badge.P  { background: var(--mx-p-bg);  color: var(--mx-p-fg);  border-color: var(--mx-p-bd); }
    .mx-badge.HD { background: var(--mx-hd-bg); color: var(--mx-hd-fg); border-color: var(--mx-hd-bd); }
    .mx-badge.AA { background: var(--mx-aa-bg); color: var(--mx-aa-fg); border-color: var(--mx-aa-bd); }
    .mx-badge.WO { background: var(--mx-wo-bg); color: var(--mx-wo-fg); border-color: var(--mx-wo-bd); }
    .mx-badge.CL { background: var(--mx-cl-bg); color: var(--mx-cl-fg); border-color: var(--mx-cl-bd); }
    .mx-badge.Leave { background: var(--mx-lv-bg); color: var(--mx-lv-fg); border-color: var(--mx-lv-bd); }
    .mx-badge.Left { background: var(--mx-left-bg); color: var(--mx-left-fg); border-color: var(--mx-left-bd); }
    .mx-badge.Inactive { background: var(--mx-in-bg); color: var(--mx-in-fg); border-color: var(--mx-in-bd); }
    .mx-badge.blank { background: transparent; color: #ced4da; border: none; }

    .mx-empty-state { text-align:center; padding: 48px 16px; color: #8a94a3; }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Matrix</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <div class="card-header">
                    <div class="mx-toolbar">
                        <h5 class="m-0 mr-2"><b>Attendance Matrix</b></h5>

                        <div class="form-group">
                            <label>Month</label>
                            <input type="month" id="filter_month" class="form-control form-control-sm" value="<?= date('Y-m'); ?>" onchange="get_matrix();">
                        </div>

                        <?php if(in_array((int)$_SESSION["userdata"]["role_id"], array(1))): ?>
                        <div class="form-group">
                            <label>LOB</label>
                            <select class="form-control form-control-sm select2" id="filter_lob" style="width:170px;" onchange="get_matrix();">
                                <option value="">All</option>
                                <?php if($lob_list): foreach($lob_list as $l): ?>
                                    <option value="<?= $l->lob; ?>"><?= $l->lob; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>TL</label>
                            <select class="form-control form-control-sm select2" id="filter_tl" style="width:170px;" onchange="get_matrix();">
                                <option value="">All</option>
                                <?php if($tl_list): foreach($tl_list as $t): ?>
                                    <option value="<?= $t->emp_id; ?>"><?= $t->name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">

                    <div class="mx-legend">
                        <span class="chip p">Present</span>
                        <span class="chip hd">Half Day</span>
                        <span class="chip aa">Absent</span>
                        <span class="chip wo">Week Off</span>
                        <span class="chip cl">CL</span>
                        <span class="chip lv">Leave</span>
                        <span class="chip left">Left</span>
                    </div>

                    <div id="matrix_wrap"><div class="mx-empty-state"><i class="fa fa-spinner fa-spin"></i> Loading...</div></div>

                </div>

            </div>
        </div>
    </section>
</div>

<script>
var DAY_ABBR = { 1:'Mon', 2:'Tue', 3:'Wed', 4:'Thu', 5:'Fri', 6:'Sat', 0:'Sun' };

function isWeekend(dateStr)
{
    return new Date(dateStr+"T00:00:00").getDay() === 0;
}

function statusBadge(status)
{
    if(!status || status == '-'){
        return "<span class='mx-badge blank'>&middot;</span>";
    }
    return "<span class='mx-badge "+status+"'>"+status+"</span>";
}

function get_matrix()
{
    var month = $("#filter_month").val();
    var lob = $("#filter_lob").length ? $("#filter_lob").val() : "";
    var tl_id = $("#filter_tl").length ? $("#filter_tl").val() : "";

    if(!month){ return; }

    $("#matrix_wrap").html("<div class='mx-empty-state'><i class='fa fa-spinner fa-spin'></i> Loading...</div>");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_attendance_matrix",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            month: month, lob: lob, tl_id: tl_id
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#matrix_wrap").html("<div class='mx-empty-state'>"+data.message+"</div>");
                return;
            }
            if(data.total_record == 0){
                $("#matrix_wrap").html("<div class='mx-empty-state'>Is month ka koi record nahi mila.</div>");
                return;
            }

            var txt = "<table id='tbl_matrix'><thead>";

            txt += "<tr class='mx-date-row'><th class='sticky-col' rowspan='2'>Employee</th><th rowspan='2'>ID</th>";
            <?php if(in_array((int)$_SESSION["userdata"]["role_id"], array(1))): ?>
            txt += "<th rowspan='2'>LOB</th><th rowspan='2'>TL</th>";
            <?php endif; ?>
            data.date_columns.forEach(function(d){
                txt += "<th class='"+(isWeekend(d)?'mx-weekend':'')+"'>"+d.substring(8,10)+"</th>";
            });
            txt += "<th class='mx-summary' rowspan='2'>P</th><th class='mx-summary' rowspan='2'>AA</th><th class='mx-summary' rowspan='2'>HD</th><th class='mx-summary' rowspan='2'>WO</th>";
            txt += "</tr><tr class='mx-day-row'>";
            data.date_columns.forEach(function(d){
                var dow = new Date(d+"T00:00:00").getDay();
                txt += "<th class='"+(isWeekend(d)?'mx-weekend':'')+"'>"+DAY_ABBR[dow]+"</th>";
            });
            txt += "</tr></thead><tbody>";

            for(var i = 0; i < data.total_record; i++){
                var r = data.all_record[i];
                txt += "<tr>";
                txt += "<td class='sticky-col'>"+r.name+"</td>";
                txt += "<td>"+r.msd_id+"</td>";
                <?php if(in_array((int)$_SESSION["userdata"]["role_id"], array(1))): ?>
                txt += "<td>"+(r.lob || '-')+"</td>";
                txt += "<td>"+(r.tl_name || '-')+"</td>";
                <?php endif; ?>
                data.date_columns.forEach(function(d){
                    txt += "<td class='"+(isWeekend(d)?'mx-weekend':'')+"'>"+statusBadge(r.days[d])+"</td>";
                });
                txt += "<td class='mx-summary'>"+r.present_count+"</td>";
                txt += "<td class='mx-summary'>"+r.absent_count+"</td>";
                txt += "<td class='mx-summary'>"+r.hd_count+"</td>";
                txt += "<td class='mx-summary'>"+r.wo_count+"</td>";
                txt += "</tr>";
            }
            txt += "</tbody></table>";
            $("#matrix_wrap").html(txt);
        },
        error: function(){
            $("#matrix_wrap").html("<div class='mx-empty-state'>Server error</div>");
        }
    });
}

$(function(){
    if($.fn.select2){
        $(".select2").select2({ theme: "bootstrap4", placeholder: "All" });
    }
    get_matrix();
});
</script>