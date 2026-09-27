<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>

<style>
    #roster_grid_wrap { overflow-x: auto; max-height: 70vh; overflow-y: auto; border: 1px solid #dee2e6; }
    #roster_grid_table { border-collapse: collapse; font-size: 12px; white-space: nowrap; }
    #roster_grid_table th, #roster_grid_table td { border: 1px solid #dee2e6; padding: 2px 4px; }
    #roster_grid_table thead th { position: sticky; top: 0; background: #343a40; color: #fff; z-index: 3; }
    #roster_grid_table td.sticky-col, #roster_grid_table th.sticky-col { position: sticky; left: 0; background: #fff; z-index: 2; }
    #roster_grid_table th.sticky-col { background: #343a40; z-index: 4; }
    .cell-select { width: 92px; font-size: 11px; padding: 1px; border: 1px solid #ced4da; border-radius: 3px; }
    .cell-select.type-SHIFT { background: #d1ecf1; }
    .cell-select.type-WO { background: #e2e3e5; }
    .cell-select.type-CL { background: #d6e9c6; }
    .cell-select.type-HD { background: #fff3cd; }
    .cell-select.type-LP { background: #f8d7da; }
    .preset-chip { display:inline-block; background:#e9ecef; border-radius:12px; padding:3px 10px; margin:2px; font-size:12px; }
    .preset-chip .del { color:#dc3545; cursor:pointer; margin-left:6px; font-weight:bold; }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/attendance">Attendance</a></li>
                        <li class="breadcrumb-item active">Roster Builder</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- SHIFT PRESETS -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>Shift Presets</b></h5></div>
                <div class="card-body">
                    <div id="preset_list_div" class="mb-2"></div>

                    <div class="d-flex align-items-end flex-wrap">
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Name</label>
                            <input type="text" id="preset_name" class="form-control form-control-sm" placeholder="e.g. Morning A" style="width:150px;">
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Start</label>
                            <input type="time" id="preset_start" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">End</label>
                            <input type="time" id="preset_end" class="form-control form-control-sm">
                        </div>
                        <button type="button" class="btn btn-sm btn-primary mb-0" onclick="add_preset();">Add Preset</button>
                        <span id="preset_msg" class="ml-2"></span>
                    </div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="card card-default">
                <div class="card-body">
                    <div class="d-flex align-items-end flex-wrap">
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Month</label>
                            <input type="month" id="filter_month" class="form-control form-control-sm" value="<?= date('Y-m'); ?>">
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Shift Group</label>
                            <select class="form-control form-control-sm" id="filter_shift_group" style="width:130px;">
                                <option value="">-- All --</option>
                                <option value="Morning">Morning</option>
                                <option value="Evening">Evening</option>
                            </select>
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">LOB</label>
                            <select class="form-control form-control-sm select2" id="filter_lob" style="width:160px;">
                                <option value="">-- All --</option>
                                <?php if($lob_list): foreach($lob_list as $l): ?>
                                    <option value="<?= $l->lob; ?>"><?= $l->lob; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">TL</label>
                            <select class="form-control form-control-sm select2" id="filter_tl" style="width:160px;">
                                <option value="">-- All --</option>
                                <?php if($tl_list): foreach($tl_list as $t): ?>
                                    <option value="<?= $t->emp_id; ?>"><?= $t->name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mb-0" onclick="get_roster_grid();">Load Grid</button>
                    </div>
                </div>
            </div>

            <!-- BULK APPLY -->
            <div class="card card-default">
                <div class="card-header"><h5 class="m-0"><b>Bulk Apply</b></h5> <small class="text-muted">Neeche grid me pehle employees select karo (checkbox), fir yahan se ek saath range/value apply karo.</small></div>
                <div class="card-body">
                    <div class="d-flex align-items-end flex-wrap">

                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">From</label>
                            <input type="date" id="bulk_date_from" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">To</label>
                            <input type="date" id="bulk_date_to" class="form-control form-control-sm">
                        </div>

                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Weekdays (khali = sab din)</label><br>
                            <?php $wd = array(1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'); ?>
                            <?php foreach($wd as $num => $lbl): ?>
                                <label class="mr-2" style="font-weight:normal;"><input type="checkbox" class="bulk_weekday" value="<?= $num; ?>"> <?= $lbl; ?></label>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-group mb-0 mr-2">
                            <label class="mb-0">Value</label>
                            <select class="form-control form-control-sm" id="bulk_value" style="width:170px;" onchange="toggle_bulk_custom();">
                                <optgroup label="Shift" id="bulk_shift_optgroup"></optgroup>
                                <option value="AWO">Agent Choice WO</option>
                                <option value="TWO">TL Choice WO</option>
                                <option value="CL">CL</option>
                                <option value="HD">HD</option>
                                <option value="LP">LP</option>
                                <option value="__CUSTOM__">Custom Time...</option>
                                <option value="CLEAR">Clear (khali karo)</option>
                            </select>
                        </div>

                        <div class="form-group mb-0 mr-2" id="bulk_custom_div" style="display:none;">
                            <label class="mb-0">Custom</label>
                            <div class="d-flex">
                                <input type="time" id="bulk_custom_start" class="form-control form-control-sm mr-1">
                                <input type="time" id="bulk_custom_end" class="form-control form-control-sm">
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary mb-0" onclick="bulk_apply();">Apply to Selected</button>
                        <span id="bulk_msg" class="ml-2"></span>
                    </div>
                </div>
            </div>

            <!-- GRID -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><b>Roster Grid</b></h5>
                        <div>
                            <label class="mr-2" style="font-weight:normal;"><input type="checkbox" id="select_all_emp" onclick="toggleSelectAll(this);"> Select All</label>
                            <span id="selected_count_span" class="badge badge-primary">0 selected</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="roster_grid_wrap"></div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
var SHIFT_PRESETS = [];
var DATE_COLUMNS = [];
var GRID_ROWS = [];

// ------------------------------------------------------------------
// SHIFT PRESETS
// ------------------------------------------------------------------

function get_presets()
{
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_shift_presets",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache: false,
        success: function(data){
            SHIFT_PRESETS = data.response ? data.all_record : [];
            renderPresetList();
            renderBulkShiftOptions();
        }
    });
}

function renderPresetList()
{
    var html = "";
    if(SHIFT_PRESETS.length == 0){
        html = "<span class='text-muted'>Koi preset nahi bana — neeche se add karo.</span>";
    } else {
        SHIFT_PRESETS.forEach(function(p){
            html += "<span class='preset-chip'>"+p.shift_name+" ("+p.start_time.substring(0,5)+"-"+p.end_time.substring(0,5)+")"+
                    "<span class='del' onclick='delete_preset("+p.id+")'>&times;</span></span>";
        });
    }
    $("#preset_list_div").html(html);
}

function renderBulkShiftOptions()
{
    var html = "";
    SHIFT_PRESETS.forEach(function(p){
        html += "<option value='SHIFT:"+p.id+"'>"+p.shift_name+" ("+p.start_time.substring(0,5)+"-"+p.end_time.substring(0,5)+")</option>";
    });
    $("#bulk_shift_optgroup").html(html);
}

function add_preset()
{
    var name = $("#preset_name").val().trim();
    var start = $("#preset_start").val();
    var end = $("#preset_end").val();
    if(!name || !start || !end){
        $("#preset_msg").html("<span style='color:red'>Sab fields bharo.</span>");
        return;
    }
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/save_shift_preset",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            shift_name: name, start_time: start, end_time: end
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#preset_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.response){
                $("#preset_name").val(""); $("#preset_start").val(""); $("#preset_end").val("");
                get_presets();
            }
        }
    });
}

function delete_preset(id)
{
    if(!confirm("Ye preset delete karna hai?")) return;
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/delete_shift_preset",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>', id: id },
        cache: false,
        success: function(){ get_presets(); }
    });
}

// ------------------------------------------------------------------
// GRID
// ------------------------------------------------------------------

function get_roster_grid()
{
    var month = $("#filter_month").val();
    if(!month){ alert("Month select karo."); return; }

    $("#roster_grid_wrap").html("<center class='p-4'><font color='blue'><i class='fa fa-spinner fa-spin'></i> Loading...</font></center>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/get_roster_grid",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            month: month,
            shift_group: $("#filter_shift_group").val(),
            lob: $("#filter_lob").val(),
            tl_id: $("#filter_tl").val()
        },
        cache: false,
        success: function(data){
            if(!data.response){
                $("#roster_grid_wrap").html("<center class='p-4'><font color='red'>"+data.message+"</font></center>");
                return;
            }
            DATE_COLUMNS = data.date_columns;
            GRID_ROWS = data.all_record;
            renderGrid();
        },
        error: function(){
            $("#roster_grid_wrap").html("<center class='p-4'><font color='red'>Server error</font></center>");
        }
    });
}

function cellOptionsHtml(dayData, selectedCodeOut)
{
    var currentCode = "CLEAR";
    var currentType = "";

    if(dayData){
        currentType = dayData.roster_type;
        if(dayData.roster_type == "SHIFT"){
            var matched = null;
            SHIFT_PRESETS.forEach(function(p){
                if(p.start_time.substring(0,5) == dayData.shift_start.substring(0,5) && p.end_time.substring(0,5) == dayData.shift_end.substring(0,5)){
                    matched = p;
                }
            });
            currentCode = matched ? ("SHIFT:"+matched.id) : ("CUSTOM:"+dayData.shift_start.substring(0,5)+"-"+dayData.shift_end.substring(0,5));
        } else if(dayData.roster_type == "WO"){
            currentCode = (dayData.wo_type == "TL") ? "TWO" : "AWO";
        } else if(dayData.roster_type){
            currentCode = dayData.roster_type; // CL / HD / LP
        }
    }

    var html = "<option value='CLEAR'"+(currentCode=="CLEAR"?" selected":"")+"> - </option>";
    SHIFT_PRESETS.forEach(function(p){
        var code = "SHIFT:"+p.id;
        html += "<option value='"+code+"'"+(currentCode==code?" selected":"")+">"+p.shift_name+"</option>";
    });
    if(currentCode.indexOf("CUSTOM:") === 0){
        html += "<option value='"+currentCode+"' selected>"+currentCode.substring(7)+"</option>";
    }
    html += "<option value='AWO'"+(currentCode=="AWO"?" selected":"")+">Agent WO</option>";
    html += "<option value='TWO'"+(currentCode=="TWO"?" selected":"")+">TL WO</option>";
    html += "<option value='CL'"+(currentCode=="CL"?" selected":"")+">CL</option>";
    html += "<option value='HD'"+(currentCode=="HD"?" selected":"")+">HD</option>";
    html += "<option value='LP'"+(currentCode=="LP"?" selected":"")+">LP</option>";
    html += "<option value='__CUSTOM__'>Custom...</option>";

    return { html: html, type: currentType };
}

function renderGrid()
{
    var txt = "<table id='roster_grid_table'><thead><tr>";
    txt += "<th class='sticky-col'>Sel</th><th class='sticky-col'>Name</th><th>Agent ID</th><th>TL</th>";
    DATE_COLUMNS.forEach(function(d){
        txt += "<th>"+d.substring(8,10)+"</th>";
    });
    txt += "</tr></thead><tbody>";

    for(var i = 0; i < GRID_ROWS.length; i++){
        var r = GRID_ROWS[i];
        txt += "<tr>";
        txt += "<td class='sticky-col text-center'><input type='checkbox' class='emp-check' value='"+r.emp_id+"' onchange='updateSelectedCount();'></td>";
        txt += "<td class='sticky-col'>"+r.name+"</td>";
        txt += "<td>"+r.msd_id+"</td>";
        txt += "<td>"+(r.tl_name || '-')+"</td>";

        DATE_COLUMNS.forEach(function(d){
            var dayData = r.days[d];
            var opt = cellOptionsHtml(dayData, null);
            txt += "<td><select class='cell-select type-"+opt.type+"' data-emp='"+r.emp_id+"' data-date='"+d+"' onchange='cellChanged(this);'>"+opt.html+"</select></td>";
        });

        txt += "</tr>";
    }
    txt += "</tbody></table>";
    $("#roster_grid_wrap").html(txt);
    updateSelectedCount();
}

function cellChanged(sel)
{
    var value = sel.value;
    var emp_id = $(sel).data('emp');
    var date = $(sel).data('date');

    if(value == "__CUSTOM__"){
        var range = prompt("Time range daalo (format HH:MM-HH:MM), jaise 9:00-17:00");
        if(!range || !/^\d{1,2}:\d{2}-\d{1,2}:\d{2}$/.test(range)){
            alert("Sahi format nahi hai, cancel kar diya.");
            get_roster_grid();
            return;
        }
        value = "CUSTOM:"+range;
    }

    saveCellValue(emp_id, date, value, sel);
}

function saveCellValue(emp_id, date, code, sel)
{
    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/save_roster_cell",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            emp_id: emp_id, roster_date: date, code: code
        },
        cache: false,
        success: function(data){
            if(!data.response){
                alert(data.message);
                get_roster_grid(); // select ko waapas DB ki actual state pe le aao
                return;
            }
            if(sel){
                var type = code.indexOf("SHIFT:")===0 || code.indexOf("CUSTOM:")===0 ? "SHIFT" : (code=="AWO"||code=="TWO" ? "WO" : code);
                $(sel).attr('class', 'cell-select type-'+(code=="CLEAR"?"":type));
            }
        },
        error: function(){ alert("Server error, save nahi hua."); get_roster_grid(); }
    });
}

// ------------------------------------------------------------------
// SELECTION
// ------------------------------------------------------------------

function toggleSelectAll(cb)
{
    $(".emp-check").prop("checked", cb.checked);
    updateSelectedCount();
}

function updateSelectedCount()
{
    var n = $(".emp-check:checked").length;
    $("#selected_count_span").text(n + " selected");
}

function getSelectedEmpIds()
{
    var ids = [];
    $(".emp-check:checked").each(function(){ ids.push($(this).val()); });
    return ids;
}

// ------------------------------------------------------------------
// BULK APPLY
// ------------------------------------------------------------------

function toggle_bulk_custom()
{
    $("#bulk_custom_div").toggle($("#bulk_value").val() == "__CUSTOM__");
}

function bulk_apply()
{
    var emp_ids = getSelectedEmpIds();
    if(emp_ids.length == 0){ alert("Kam se kam 1 employee select karo (grid me checkbox)."); return; }

    var date_from = $("#bulk_date_from").val();
    var date_to = $("#bulk_date_to").val();
    if(!date_from || !date_to){ alert("Date range select karo."); return; }

    var code = $("#bulk_value").val();
    if(code == "__CUSTOM__"){
        var s = $("#bulk_custom_start").val();
        var e = $("#bulk_custom_end").val();
        if(!s || !e){ alert("Custom start/end time daalo."); return; }
        code = "CUSTOM:"+s+"-"+e;
    }

    var weekdays = [];
    $(".bulk_weekday:checked").each(function(){ weekdays.push($(this).val()); });

    if(!confirm(emp_ids.length+" employees ke liye "+date_from+" se "+date_to+" tak apply karna hai. Confirm?")) return;

    $("#bulk_msg").html("<font color='blue'><i class='fa fa-spinner fa-spin'></i> Applying...</font>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/bulk_apply_roster",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            emp_ids: emp_ids, date_from: date_from, date_to: date_to, weekdays: weekdays, code: code
        },
        cache: false,
        success: function(data){
            var color = data.response ? "green" : "red";
            $("#bulk_msg").html("<span style='color:"+color+"'>"+data.message+"</span>");
            if(data.skipped && data.skipped.length){
                var list = "<ul style='font-size:12px;color:#a00;max-height:150px;overflow-y:auto;'>";
                data.skipped.forEach(function(s){ list += "<li>"+s+"</li>"; });
                list += "</ul>";
                $("#bulk_msg").append(list);
            }
            if(data.response){ get_roster_grid(); }
        },
        error: function(){ $("#bulk_msg").html("<span style='color:red'>Server error</span>"); }
    });
}

$(function(){
    if($.fn.select2){ $(".select2").select2({ theme: "bootstrap4", placeholder: "-- All --" }); }
    get_presets();
    get_roster_grid();
});
</script>