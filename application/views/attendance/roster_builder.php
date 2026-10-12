<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>

<style>
    :root {
        --rb-shift-bg: #e7f3ff; --rb-shift-fg: #0b5ea8; --rb-shift-bd: #b6dcff;
        --rb-wo-bg:    #eef0f3; --rb-wo-fg:    #5a6472; --rb-wo-bd:    #d7dbe0;
        --rb-cl-bg:    #e7f6ea; --rb-cl-fg:    #1e7b34; --rb-cl-bd:    #bfe6c8;
        --rb-hd-bg:    #fff4de; --rb-hd-fg:    #a5680a; --rb-hd-bd:    #f5d99b;
        --rb-lp-bg:    #fdeaea; --rb-lp-fg:    #c0392b; --rb-lp-bd:    #f5c2be;
        --rb-blank-fg: #adb5bd;
        --rb-weekend:  #fff9ec;
    }

    .rb-toolbar { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 10px 14px; }
    .rb-toolbar .form-group { margin-bottom: 0; }
    .rb-toolbar label { font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #8a94a3; margin-bottom: 3px; font-weight: 600; }
    .rb-toolbar select, .rb-toolbar input[type=month] { min-width: 110px; }

    .rb-legend { display: flex; flex-wrap: wrap; gap: 8px; margin: 2px 0 14px; }
    .rb-legend .chip { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 3px 10px; border-radius: 20px; border: 1px solid transparent; cursor: pointer; user-select: none; transition: box-shadow .12s ease, transform .08s ease; }
    .rb-legend .chip:hover { box-shadow: 0 0 0 2px rgba(0,0,0,.06); }
    .rb-legend .chip.active { box-shadow: 0 0 0 2px rgba(0,0,0,.28); font-weight: 700; }
    .rb-legend .chip.shift { background: var(--rb-shift-bg); color: var(--rb-shift-fg); border-color: var(--rb-shift-bd); }
    .rb-legend .chip.wo    { background: var(--rb-wo-bg);    color: var(--rb-wo-fg);    border-color: var(--rb-wo-bd); }
    .rb-legend .chip.cl    { background: var(--rb-cl-bg);    color: var(--rb-cl-fg);    border-color: var(--rb-cl-bd); }
    .rb-legend .chip.hd    { background: var(--rb-hd-bg);    color: var(--rb-hd-fg);    border-color: var(--rb-hd-bd); }
    .rb-legend .chip.lp    { background: var(--rb-lp-bg);    color: var(--rb-lp-fg);    border-color: var(--rb-lp-bd); }

    #roster_grid_wrap { overflow: auto; max-height: 68vh; border: 1px solid #e3e6ea; border-radius: 8px; }
    #roster_grid_table { border-collapse: separate; border-spacing: 0; font-size: 12px; white-space: nowrap; width: 100%; }
    #roster_grid_table th, #roster_grid_table td { border-bottom: 1px solid #eef0f2; border-right: 1px solid #eef0f2; padding: 4px 6px; }

    #roster_grid_table thead th { position: sticky; top: 0; background: #2b3542; color: #fff; z-index: 3; font-weight: 600; border-color: #3a4553; padding: 6px; }
    #roster_grid_table thead tr.rb-date-row th { font-size: 12.5px; }
    #roster_grid_table thead tr.rb-day-row th { font-size: 10px; font-weight: 500; color: #b7c0cc; text-transform: uppercase; padding-top: 0; padding-bottom: 5px; top: 26px; }
    #roster_grid_table thead th.rb-weekend { background: #3a3126; }

    #roster_grid_table td.sticky-col, #roster_grid_table th.sticky-col { position: sticky; left: 0; background: #fff; z-index: 2; box-shadow: 2px 0 4px rgba(0,0,0,.04); }
    #roster_grid_table th.sticky-col { background: #2b3542; z-index: 4; }
    #roster_grid_table tbody tr:nth-child(even) td.sticky-col { background: #fafbfc; }
    #roster_grid_table tbody tr:hover td { background: #f4f8fd; }
    #roster_grid_table tbody tr:hover td.sticky-col { background: #eef4fb; }
    #roster_grid_table td.rb-weekend { background: var(--rb-weekend); }
    #roster_grid_table tbody tr:hover td.rb-weekend { background: #fdf3dc; }

    #roster_grid_table tbody tr.rb-row-selected td { background: #fff3cd; }
    #roster_grid_table tbody tr.rb-row-selected td.sticky-col { background: #ffe9a8; }
    #roster_grid_table tbody tr.rb-row-selected td.rb-weekend { background: #ffe9a8; }

    .cell-select { width: 96px; font-size: 11px; padding: 3px 4px; border: 1px solid #d7dbe0; border-radius: 5px; background: #fff; color: #3a4453; cursor: pointer; transition: box-shadow .12s ease; }
    .cell-select:hover { box-shadow: 0 0 0 2px rgba(43,53,66,.08); }
    .cell-select:focus { outline: none; box-shadow: 0 0 0 2px rgba(11,94,168,.35); }
    .cell-select.rb-cell-active, td.rb-cell-active-td { box-shadow: 0 0 0 2px #0b5ea8 inset; }
    td.rb-cell-active-td { background: #eaf3ff !important; }
    .cell-select.type-SHIFT { background: var(--rb-shift-bg); color: var(--rb-shift-fg); border-color: var(--rb-shift-bd); }
    .cell-select.type-WO    { background: var(--rb-wo-bg);    color: var(--rb-wo-fg);    border-color: var(--rb-wo-bd); }
    .cell-select.type-CL    { background: var(--rb-cl-bg);    color: var(--rb-cl-fg);    border-color: var(--rb-cl-bd); }
    .cell-select.type-HD    { background: var(--rb-hd-bg);    color: var(--rb-hd-fg);    border-color: var(--rb-hd-bd); }
    .cell-select.type-LP    { background: var(--rb-lp-bg);    color: var(--rb-lp-fg);    border-color: var(--rb-lp-bd); }
    .cell-select.type- { color: var(--rb-blank-fg); }

    .rb-left { display:inline-block; width:96px; text-align:center; font-size:11px; padding:3px 4px; border-radius:5px; background:#f3e3e3; color:#8a3b3b; border:1px dashed #e2c4c4; }
    .preset-chip { display:inline-flex; align-items:center; background:#eef0f3; border-radius:16px; padding:4px 6px 4px 12px; margin:3px; font-size:12.5px; color:#3a4453; }
    .preset-chip .del { color:#c0392b; cursor:pointer; margin-left:8px; font-weight:bold; padding:0 6px; border-radius:50%; }
    .preset-chip .del:hover { background:#fdeaea; }

    #bulk_apply_btn .badge { margin-left: 4px; }
    .rb-empty-state { text-align:center; padding: 48px 16px; color: #8a94a3; }
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

            <!-- MAIN CONTENT: ROSTER GRID -->
            <div class="card card-default">

                <div class="card-header">
                    <div class="rb-toolbar">

                        <div class="form-group">
                            <label>Month</label>
                            <input type="month" id="filter_month" class="form-control form-control-sm" value="<?= date('Y-m'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Shift Group</label>
                            <select class="form-control form-control-sm" id="filter_shift_group">
                                <option value="">All</option>
                                <option value="Morning">Morning</option>
                                <option value="Evening">Evening</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>LOB</label>
                            <select class="form-control form-control-sm select2" id="filter_lob" style="width:170px;">
                                <option value="">All</option>
                                <?php if($lob_list): foreach($lob_list as $l): ?>
                                    <option value="<?= $l->lob; ?>"><?= $l->lob; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>TL</label>
                            <select class="form-control form-control-sm select2" id="filter_tl" style="width:170px;">
                                <option value="">All</option>
                                <?php if($tl_list): foreach($tl_list as $t): ?>
                                    <option value="<?= $t->emp_id; ?>"><?= $t->name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-dark" onclick="get_roster_grid();">
                            <i class="fa fa-sync-alt"></i> Load
                        </button>

                        <div class="form-group">
                            <label>Specific Date</label>
                            <input type="date" id="filter_specific_date" class="form-control form-control-sm" onchange="renderGrid();">
                        </div>
                        <div class="form-group">
                            <label>Employee</label>
                            <select class="form-control form-control-sm select2" id="filter_employee" style="width:190px;" onchange="renderGrid();">
                                <option value="">All</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="clearViewFilters();" title="Specific Date / Employee / type-chip filters hata do">
                            <i class="fa fa-times"></i> Clear View Filters
                        </button>

                        <div class="ml-auto d-flex align-items-center" style="gap:8px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#presets_model">
                                <i class="fa fa-clock"></i> Manage Shifts
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" id="bulk_apply_btn" data-toggle="modal" data-target="#bulk_model" disabled>
                                <i class="fa fa-layer-group"></i> Bulk Apply <span class="badge badge-light">0</span>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                        <div class="rb-legend">
                            <span class="chip shift" data-type="SHIFT" onclick="toggleTypeFilter('SHIFT', this);" title="Click: sirf Shift-waale employees dikhao">Shift</span>
                            <span class="chip wo" data-type="WO" onclick="toggleTypeFilter('WO', this);" title="Click: sirf Week-Off-waale employees dikhao">Week Off</span>
                            <span class="chip cl" data-type="CL" onclick="toggleTypeFilter('CL', this);" title="Click: sirf CL-waale employees dikhao">CL</span>
                            <span class="chip hd" data-type="HD" onclick="toggleTypeFilter('HD', this);" title="Click: sirf Half-Day-waale employees dikhao">Half Day</span>
                            <span class="chip lp" data-type="LP" onclick="toggleTypeFilter('LP', this);" title="Click: sirf LP-waale employees dikhao">LP</span>
                        </div>
                        <div>
                            <label class="mr-2 mb-0" style="font-weight:normal;font-size:13px;"><input type="checkbox" id="select_all_emp" onclick="toggleSelectAll(this);"> Select all</label>
                            <span id="selected_count_span" class="badge badge-secondary">0 selected</span>
                        </div>
                    </div>

                    <div id="roster_grid_wrap"><div class="rb-empty-state"><i class="fa fa-spinner fa-spin"></i> Loading...</div></div>

                </div>
            </div>

        </div>
    </section>
</div>

<!-- SHIFT PRESETS MODAL -->
<div class="modal fade" id="presets_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Manage Shift Presets</b></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="preset_list_div" class="mb-3"></div>
                <hr>
                <div class="form-row align-items-end">
                    <div class="col-5">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">Name</label>
                        <input type="text" id="preset_name" class="form-control form-control-sm" placeholder="e.g. Morning A">
                    </div>
                    <div class="col-3">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">Start</label>
                        <input type="time" id="preset_start" class="form-control form-control-sm">
                    </div>
                    <div class="col-3">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">End</label>
                        <input type="time" id="preset_end" class="form-control form-control-sm">
                    </div>
                    <div class="col-1 p-0 text-center">
                        <button type="button" class="btn btn-sm btn-primary" onclick="add_preset();"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div id="preset_msg" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- BULK APPLY MODAL -->
<div class="modal fade" id="bulk_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Bulk Apply</b> <span id="bulk_selected_label" class="badge badge-primary ml-1"></span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="form-row">
                    <div class="col-6 form-group">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">From</label>
                        <input type="date" id="bulk_date_from" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 form-group">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">To</label>
                        <input type="date" id="bulk_date_to" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group">
                    <label class="mb-1" style="font-size:12px;color:#8a94a3;">Weekdays (khali = sab din)</label><br>
                    <?php $wd = array(1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'); ?>
                    <?php foreach($wd as $num => $lbl): ?>
                        <label class="mr-2" style="font-weight:normal;font-size:13px;"><input type="checkbox" class="bulk_weekday" value="<?= $num; ?>"> <?= $lbl; ?></label>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label class="mb-1" style="font-size:12px;color:#8a94a3;">Value</label>
                    <select class="form-control form-control-sm" id="bulk_value" onchange="toggle_bulk_custom();">
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

                <div class="form-row" id="bulk_custom_div" style="display:none;">
                    <div class="col-6 form-group">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">Custom Start</label>
                        <input type="time" id="bulk_custom_start" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 form-group">
                        <label class="mb-1" style="font-size:12px;color:#8a94a3;">Custom End</label>
                        <input type="time" id="bulk_custom_end" class="form-control form-control-sm">
                    </div>
                </div>

                <div id="bulk_msg"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="bulk_apply();">Apply</button>
            </div>
        </div>
    </div>
</div>

<script>
var SHIFT_PRESETS = [];
var DATE_COLUMNS = [];
var GRID_ROWS = [];
var DAY_ABBR = { 1:'Mon', 2:'Tue', 3:'Wed', 4:'Thu', 5:'Fri', 6:'Sat', 0:'Sun' };
var ACTIVE_TYPE_FILTER = null; // 'SHIFT' | 'WO' | 'CL' | 'HD' | 'LP' | null (legend-chip click se set hota hai)

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
        html = "<span class='text-muted' style='font-size:13px;'>Koi preset nahi bana — neeche se add karo.</span>";
    } else {
        SHIFT_PRESETS.forEach(function(p){
            html += "<span class='preset-chip'>"+p.shift_name+" &middot; "+p.start_time.substring(0,5)+"-"+p.end_time.substring(0,5)+
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
        $("#preset_msg").html("<span style='color:#c0392b;font-size:13px;'>Sab fields bharo.</span>");
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
            var color = data.response ? "#1e7b34" : "#c0392b";
            $("#preset_msg").html("<span style='color:"+color+";font-size:13px;'>"+data.message+"</span>");
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

    $("#roster_grid_wrap").html("<div class='rb-empty-state'><i class='fa fa-spinner fa-spin'></i> Loading...</div>");

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
                $("#roster_grid_wrap").html("<div class='rb-empty-state'>"+data.message+"</div>");
                return;
            }
            DATE_COLUMNS = data.date_columns;
            GRID_ROWS = data.all_record;
            populateEmployeeFilter();
            renderGrid();
        },
        error: function(){
            $("#roster_grid_wrap").html("<div class='rb-empty-state'>Server error</div>");
        }
    });
}

function isWeekend(dateStr)
{
    var d = new Date(dateStr+"T00:00:00");
    var dow = d.getDay(); // 0=Sun, 6=Sat
    return dow === 0;
}

function cellOptionsHtml(dayData)
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

// ------------------------------------------------------------------
// VIEW FILTERS (Specific Date / Employee / legend-type chips) — ye
// sirf ye decide karte hain ki already-loaded GRID_ROWS/DATE_COLUMNS
// me se kya dikhana hai. Koi naya AJAX nahi, koi data/logic change
// nahi — sirf display-level filtering.
// ------------------------------------------------------------------

function getVisibleDateColumns()
{
    var specificDate = $("#filter_specific_date").val();
    if(specificDate && DATE_COLUMNS.indexOf(specificDate) !== -1){
        return [specificDate];
    }
    return DATE_COLUMNS;
}

function rowMatchesTypeFilter(r, visibleDates)
{
    if(!ACTIVE_TYPE_FILTER) return true;
    for(var i = 0; i < visibleDates.length; i++){
        var dayData = r.days[visibleDates[i]];
        if(dayData && !dayData.left && dayData.roster_type === ACTIVE_TYPE_FILTER){
            return true;
        }
    }
    return false;
}

function getVisibleRows(visibleDates)
{
    var empFilter = $("#filter_employee").val();
    return GRID_ROWS.filter(function(r){
        if(empFilter && String(r.emp_id) !== String(empFilter)) return false;
        if(!rowMatchesTypeFilter(r, visibleDates)) return false;
        return true;
    });
}

function populateEmployeeFilter()
{
    var prevVal = $("#filter_employee").val();
    var html = "<option value=''>All</option>";
    GRID_ROWS.forEach(function(r){
        html += "<option value='"+r.emp_id+"'>"+r.name+" ("+r.msd_id+")</option>";
    });
    $("#filter_employee").html(html);
    var stillExists = GRID_ROWS.some(function(r){ return String(r.emp_id) === String(prevVal); });
    $("#filter_employee").val(stillExists ? prevVal : "").trigger("change");
}

function toggleTypeFilter(type, el)
{
    if(ACTIVE_TYPE_FILTER === type){
        ACTIVE_TYPE_FILTER = null;
        $(".rb-legend .chip").removeClass("active");
    } else {
        ACTIVE_TYPE_FILTER = type;
        $(".rb-legend .chip").removeClass("active");
        $(el).addClass("active");
    }
    renderGrid();
}

function clearViewFilters()
{
    $("#filter_specific_date").val("");
    $("#filter_employee").val("").trigger("change");
    ACTIVE_TYPE_FILTER = null;
    $(".rb-legend .chip").removeClass("active");
    renderGrid();
}

function renderGrid()
{
    if(GRID_ROWS.length == 0){
        $("#roster_grid_wrap").html("<div class='rb-empty-state'>Koi employee nahi mila in filters ke saath.<br><small>Filters badlo ya pehle Employees page se agents add karo.</small></div>");
        return;
    }

    var visibleDates = getVisibleDateColumns();
    var visibleRows = getVisibleRows(visibleDates);

    if(visibleRows.length == 0){
        $("#roster_grid_wrap").html("<div class='rb-empty-state'>Is Date / Employee / Type filter ke saath koi match nahi mila.<br><small>'Clear View Filters' se reset karo.</small></div>");
        return;
    }

    var txt = "<table id='roster_grid_table'><thead>";

    txt += "<tr class='rb-date-row'><th class='sticky-col' rowspan='2'>Employee</th><th rowspan='2'>Agent ID</th><th rowspan='2'>TL</th>";
    visibleDates.forEach(function(d){
        var wknd = isWeekend(d);
        txt += "<th class='"+(wknd?'rb-weekend':'')+"'>"+d.substring(8,10)+"</th>";
    });
    txt += "</tr><tr class='rb-day-row'>";
    visibleDates.forEach(function(d){
        var dow = new Date(d+"T00:00:00").getDay();
        var wknd = isWeekend(d);
        txt += "<th class='"+(wknd?'rb-weekend':'')+"'>"+DAY_ABBR[dow]+"</th>";
    });
    txt += "</tr></thead><tbody>";

    for(var i = 0; i < visibleRows.length; i++){
        var r = visibleRows[i];
        txt += "<tr>";
        txt += "<td class='sticky-col'><label class='mb-0' style='font-weight:normal;cursor:pointer;'>" +
                    "<input type='checkbox' class='emp-check mr-1' value='"+r.emp_id+"' onchange='updateSelectedCount(); toggleRowHighlight(this);'>"+r.name+"</label></td>";
        txt += "<td>"+r.msd_id+"</td>";
        txt += "<td>"+(r.tl_name || '-')+"</td>";

        visibleDates.forEach(function(d){
            var dayData = r.days[d];
            var wknd = isWeekend(d);
            if(dayData && dayData.left){
                txt += "<td class='"+(wknd?'rb-weekend':'')+"'><span class='rb-left'>Left</span></td>";
                return;
            }
            var opt = cellOptionsHtml(dayData);
            txt += "<td class='"+(wknd?'rb-weekend':'')+"'><select class='cell-select type-"+opt.type+"' data-emp='"+r.emp_id+"' data-date='"+d+"' onchange='cellChanged(this);' onfocus='cellFocused(this);' onblur='cellBlurred(this);' onkeydown='cellKeyNav(event, this);'>"+opt.html+"</select></td>";
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
                get_roster_grid();
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
    $(".emp-check").each(function(){ toggleRowHighlight(this); });
    updateSelectedCount();
}

function updateSelectedCount()
{
    var n = $(".emp-check:checked").length;
    $("#selected_count_span").text(n + " selected");
    $("#bulk_apply_btn").prop("disabled", n == 0).find(".badge").text(n);
    $("#bulk_selected_label").text(n + " employee" + (n==1?"":"s"));
}

function getSelectedEmpIds()
{
    var ids = [];
    $(".emp-check:checked").each(function(){ ids.push($(this).val()); });
    return ids;
}

function toggleRowHighlight(cb)
{
    $(cb).closest("tr").toggleClass("rb-row-selected", cb.checked);
}

// ------------------------------------------------------------------
// CELL FOCUS HIGHLIGHT + ARROW-KEY NAVIGATION (UI only, no logic change)
// ------------------------------------------------------------------

function cellFocused(sel)
{
    $(".cell-select.rb-cell-active").removeClass("rb-cell-active");
    $("td.rb-cell-active-td").removeClass("rb-cell-active-td");
    $(sel).addClass("rb-cell-active");
    $(sel).closest("td").addClass("rb-cell-active-td");
}

function cellBlurred(sel)
{
    $(sel).removeClass("rb-cell-active");
    $(sel).closest("td").removeClass("rb-cell-active-td");
}

function cellKeyNav(e, sel)
{
    var key = e.key;
    if(key !== "ArrowUp" && key !== "ArrowDown" && key !== "ArrowLeft" && key !== "ArrowRight") return;

    var $td = $(sel).closest("td");
    var $tr = $td.closest("tr");
    var colIndex = $td.index();
    var $target = null;

    if(key === "ArrowUp"){
        var $prevTr = $tr.prev("tr");
        if($prevTr.length){ $target = $prevTr.children().eq(colIndex).find(".cell-select"); }
    } else if(key === "ArrowDown"){
        var $nextTr = $tr.next("tr");
        if($nextTr.length){ $target = $nextTr.children().eq(colIndex).find(".cell-select"); }
    } else if(key === "ArrowLeft"){
        var $prevTd = $td.prev("td");
        if($prevTd.length){ $target = $prevTd.find(".cell-select"); }
    } else if(key === "ArrowRight"){
        var $nextTd = $td.next("td");
        if($nextTd.length){ $target = $nextTd.find(".cell-select"); }
    }

    if($target && $target.length){
        e.preventDefault();
        $target.get(0).focus();
    }
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

    $("#bulk_msg").html("<div class='mt-2' style='color:#0b5ea8;font-size:13px;'><i class='fa fa-spinner fa-spin'></i> Applying...</div>");

    $.ajax({
        type: "POST", dataType: "json",
        url: "<?php echo base_url(); ?>app/attendance/bulk_apply_roster",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            emp_ids: emp_ids, date_from: date_from, date_to: date_to, weekdays: weekdays, code: code
        },
        cache: false,
        success: function(data){
            var color = data.response ? "#1e7b34" : "#c0392b";
            var txt = "<div class='mt-2' style='color:"+color+";font-size:13px;'>"+data.message+"</div>";
            if(data.skipped && data.skipped.length){
                txt += "<ul style='font-size:12px;color:#c0392b;max-height:130px;overflow-y:auto;margin-top:6px;'>";
                data.skipped.forEach(function(s){ txt += "<li>"+s+"</li>"; });
                txt += "</ul>";
            }
            $("#bulk_msg").html(txt);
            if(data.response){ get_roster_grid(); }
        },
        error: function(){ $("#bulk_msg").html("<div class='mt-2' style='color:#c0392b;font-size:13px;'>Server error</div>"); }
    });
}

$(function(){
    if($.fn.select2){ $(".select2").select2({ theme: "bootstrap4", placeholder: "All" }); }
    get_presets();
    get_roster_grid();
});
</script>