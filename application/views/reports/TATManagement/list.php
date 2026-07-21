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

<style>
.tat-page .tat-toolbar {
    display: grid;
    grid-template-columns: minmax(170px, 1fr) minmax(170px, 1fr) minmax(120px, 160px) auto;
    gap: 10px;
    align-items: end;
    margin-bottom: 14px;
}
.tat-page .filter-field label {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: #6b7280;
}
.tat-page .tat-badge {
    display: inline-block;
    min-width: 48px;
    padding: 4px 9px;
    border-radius: 999px;
    background: #eaf3ff;
    color: #1f5d99;
    font-weight: 800;
    text-align: center;
}
.tat-page .tat-empty {
    color: #9ca3af;
    font-weight: 700;
}
.tat-page .tat-note {
    margin-bottom: 12px;
    color: #6b7280;
    font-size: 13px;
}
@media(max-width: 900px) {
    .tat-page .tat-toolbar { grid-template-columns: 1fr 1fr; }
    .tat-page .tat-toolbar .filter-actions { grid-column: 1 / -1; }
}
@media(max-width: 640px) {
    .tat-page .tat-toolbar { grid-template-columns: 1fr; }
}
</style>

<div class="content-wrapper tat-page">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>app/home">Home</a></li>
                        <li class="breadcrumb-item active">TAT Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h5 class="m-0"><b>TAT Management</b></h5>
                </div>
                <div class="card-body">
                    <div class="tat-note">
                        TAT values are displayed directly from complaint attributes: TAT1, TAT2, TAT3 and TAT4.
                    </div>
                    <div class="tat-toolbar">
                        <div class="filter-field">
                            <label>Department</label>
                            <select id="filter_department" class="form-control form-control-sm">
                                <option value="">All Departments</option>
                                <?php if($department_list != FALSE){ foreach($department_list as $department){ echo "<option value='".$department->Departname_E."'>".$department->Departname_E."</option>"; }} ?>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>Attribute</label>
                            <input type="text" id="filter_attribute" class="form-control form-control-sm" placeholder="Type attribute keyword">
                        </div>
                        <div class="filter-field">
                            <label>TAT Days</label>
                            <input type="number" id="filter_tat_days" class="form-control form-control-sm" min="0" placeholder="Any TAT value">
                        </div>
                        <div class="filter-actions">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="resetTatFilters();">Reset</button>
                        </div>
                    </div>
                    <div id="tat_list_div"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var tatTable = null;
var baseUrl = "<?php echo base_url(); ?>";
var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
var csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";

function escapeHtml(value) {
    return $("<div>").text(value == null ? "" : value).html();
}

function csrfPayload() {
    var payload = {};
    payload[csrfName] = csrfHash;
    return payload;
}

function tatValue(value) {
    if(value === null || value === undefined || value === "") {
        return "<span class='tat-empty'>-</span>";
    }
    return "<span class='tat-badge'>"+escapeHtml(value)+"</span>";
}

function makeTatDataTable() {
    if($.fn.DataTable.isDataTable("#tat_table")) {
        $("#tat_table").DataTable().destroy();
    }
    tatTable = $("#tat_table").DataTable({
        destroy: true,
        ordering: true,
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: 'Bfrtip',
        buttons: ['colvis', { extend: 'print', exportOptions: { columns: ':visible' } }, { extend: 'excelHtml5' }]
    });
    applyTatFilters();
}

$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if(settings.nTable.id !== "tat_table") return true;
    var tatDays = $("#filter_tat_days").val();
    if(tatDays === "") return true;
    var row = settings.aoData[dataIndex];
    var node = row ? row.nTr : null;
    if(!node) return true;
    return $("td:eq(3)", node).data("search") == tatDays ||
           $("td:eq(4)", node).data("search") == tatDays ||
           $("td:eq(5)", node).data("search") == tatDays ||
           $("td:eq(6)", node).data("search") == tatDays;
});

function applyTatFilters() {
    if(!tatTable) return;
    tatTable.column(1).search($("#filter_department").val());
    tatTable.column(2).search($("#filter_attribute").val());
    tatTable.draw();
}

function resetTatFilters() {
    $("#filter_department").val("");
    $("#filter_attribute,#filter_tat_days").val("");
    applyTatFilters();
}

function getAllTatMappings() {
    $("#tat_list_div").html("<center><font color='blue'><b><i class='fa fa-spinner fa-spin'></i> Wait, Loading...</b></font></center>");
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: baseUrl + "app/reports/get_all_tat_mappings",
        data: csrfPayload(),
        cache: false,
        success: function(data) {
            if(data.response == true) {
                var txt = "<table class='table table-bordered table-striped table-sm' id='tat_table' style='font-size:13px;'>";
                txt += "<thead><tr><th>Sr.No.</th><th>Department</th><th>Attribute</th><th>TAT1</th><th>TAT2</th><th>TAT3</th><th>TAT4</th></tr></thead><tbody>";
                for(var i = 0; i < data.total_record; i++) {
                    var row = data.all_record[i];
                    txt += "<tr>";
                    txt += "<td>"+(i+1)+"</td>";
                    txt += "<td>"+escapeHtml(row.department_name)+"</td>";
                    txt += "<td>"+escapeHtml(row.attribute_name)+"</td>";
                    txt += "<td data-search='"+escapeHtml(row.TAT1 || "")+"' data-order='"+escapeHtml(row.TAT1 || 0)+"'>"+tatValue(row.TAT1)+"</td>";
                    txt += "<td data-search='"+escapeHtml(row.TAT2 || "")+"' data-order='"+escapeHtml(row.TAT2 || 0)+"'>"+tatValue(row.TAT2)+"</td>";
                    txt += "<td data-search='"+escapeHtml(row.TAT3 || "")+"' data-order='"+escapeHtml(row.TAT3 || 0)+"'>"+tatValue(row.TAT3)+"</td>";
                    txt += "<td data-search='"+escapeHtml(row.TAT4 || "")+"' data-order='"+escapeHtml(row.TAT4 || 0)+"'>"+tatValue(row.TAT4)+"</td>";
                    txt += "</tr>";
                }
                txt += "</tbody></table>";
                $("#tat_list_div").html(txt);
                makeTatDataTable();
            } else {
                $("#tat_list_div").html("<center><font color='red'><b>"+escapeHtml(data.message)+"</b></font></center>");
            }
        }
    });
}

$("#filter_department,#filter_attribute,#filter_tat_days").on("keyup change", applyTatFilters);

getAllTatMappings();
</script>
