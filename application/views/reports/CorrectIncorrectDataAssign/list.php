<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/advance_datatable/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script>
function makeDataTable_Basic(tableID)
 {
  var tableSelector = '#'+tableID;

  if (!$(tableSelector).length || !$.fn.DataTable) {
      return null;
  }

  if ($.fn.DataTable.isDataTable(tableSelector)) {
      $(tableSelector).DataTable().destroy();
  }

  return $(tableSelector).DataTable({
                        destroy: true,
                        ordering: true,
                        dom: 'Bfrtip',
                        buttons: [
                            'colvis',
                            { extend: 'print', exportOptions: { columns: ':visible' }  },
                            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL',  download: 'open' },
                            { extend: 'excelHtml5', customize: function( xlsx ) { var sheet = xlsx.xl.worksheets['sheet1.xml']; $('row c[r^="C"]', sheet).attr( 's', '2' ); }}
                          ]
                 });
 }
</script>
  <!-- Bootstrap Switch -->


<div class="content-wrapper">

    <!-- HEADER -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url(); ?>app/home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Copy/Paste Resolution Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- MAIN CARD -->
            <div class="card card-default color-palette-box">

                <!-- CARD HEADER -->
                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <!-- Heading -->
                        <h5 class="m-0">
                            <b>Correct Incorrect Data Assign</b>
                        </h5>

                        <!-- Right Controls -->
                        

                    </div>

                </div>
                <!-- /.card-header -->

                <!-- CARD BODY -->
                <!-- CARD BODY -->
                <div class="card-body">

                    <form method="post" action="">

                        <div class="row" id="add_detail_model">

                            <!-- Number -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="number">Number</label>
                                    <input type="number"
                                        class="form-control"
                                        id="number"
                                        name="number"
                                        placeholder="Enter Number"
                                        min="0">
                                </div>
                            </div>

                            <!-- Dropdown -->
                            <!-- Dropdown -->
                            <div class="col-md-3">
                                <div class="form-group">
                                <label>Agent ID</label>

                                <select class="form-control select2"
                                        id="agent_id"
                                        name="agent_id[]"
                                        multiple="multiple">

                                    <?php
                                    if($user_list != FALSE)
                                    {
                                        foreach($user_list as $userlist)
                                        {
                                            echo "<option value='".$userlist->emp_id."'>
                                                    ".$userlist->user_name." (".$userlist->msd_id.")
                                                </option>";
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date"
                                        class="form-control"
                                        id="date"
                                        name="date">
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button"
                                            class="btn btn-primary form-control" onclick="correct_incorrect_data_assign()">
                                        Submit
                                    </button>
                                </div>
                            </div>

                        </div>

                    </form>

                    <!-- ============================ -->
<!-- ASSIGN SUMMARY CARD -->
<!-- ============================ -->
<div class="card card-default mt-4">
    <div class="card-header">
        <h5 class="m-0"><b>Date-wise Assign Summary</b></h5>
    </div>
    <div class="card-body">

        <div class="row align-items-end">

    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="summary_from_date">From Date</label>
            <input type="date"
                   class="form-control"
                   id="summary_from_date"
                   name="summary_from_date">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="summary_to_date">To Date</label>
            <input type="date"
                   class="form-control"
                   id="summary_to_date"
                   name="summary_to_date"
                   value="<?= date('Y-m-d'); ?>">
        </div>
    </div>

    <div class="col-md-3">
        <button type="button"
                class="btn btn-primary"
                onclick="loadAssignSummary()">
            Submit
        </button>
    </div>

</div>

        <hr>

        <div class="table-responsive">
            <table id="assignSummaryTable" class="table table-bordered table-striped table-sm" style="width:100%">
                <thead>
    <tr>
        <th>Date</th>
        <th>Agent ID</th>
        <th>Agent Name</th>
        <th>Assigned to Agent (Count)</th>
        <th>Day's Total Assigned</th>
    </tr>
</thead>
                <tbody id="assignSummaryBody"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Grand Total:</th>
                        <th id="summaryGrandTotal">0</th>
                        <th>—</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->

        </div>
        <!-- /.container-fluid -->
    </section>

</div>
<!-- /.content-wrapper -->
   
  <script>

    function correct_incorrect_data_assign()
{
    var number = $("#number").val();
    var agent_ids = $("#agent_id").val();   // ye ab array return karega, jaise ["101","102","105"]
    var date = $("#date").val();

    if(number == "" || number <= 0)
    {
        alert("Enter a valid number.");
        return;
    }
    if(agent_ids == null || agent_ids.length == 0)
    {
        alert("Select at least one agent.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>app/reports/correct_incorrect_data_assign",
        dataType: "json",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            number: number,
            agent_id: agent_ids,   // array as it is jayega, CodeIgniter me $this->input->post('agent_id') array milega
            date: date
        },
        cache: false,
        success: function(data) {
            if(data.response) {
                alert("Data assigned successfully.");
                location.reload();
            } else {
                alert("Error: " + data.message);
            }
        },
        error: function() {
            alert("Server error. Please try again later.");
        }
    });
}
    function initializeSelect2()
{
    if (!$.fn.select2) {
        return;
    }

    $("#add_detail_model .select2").select2({
        theme: "bootstrap4",
        width: "100%",
        dropdownParent: $("#add_detail_model"),
        placeholder: "-- Select Agents --",
        multiple: true
    });
    $("#update_detail_model .select2").select2({
        theme: "bootstrap4",
        width: "100%",
        dropdownParent: $("#update_detail_model"),
        placeholder: "-- Select --"
    });
}

initializeSelect2();
   
   var assignSummaryTable = null;

function escapeHtml(str) {
    return $('<div>').text(str || '').html();
}

function loadAssignSummary() {
    var from_date = $('#summary_from_date').val();
    var to_date   = $('#summary_to_date').val();

    $('#assignSummaryBody').html('<tr><td colspan="5" class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>app/reports/assign_summary",
        dataType: "json",
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
            from_date: from_date,
            to_date: to_date
        },
        success: function (response) {
            renderAssignSummary(response);
        },
        error: function () {
            $('#assignSummaryBody').html('<tr><td colspan="5" class="text-center text-danger">Data load karte waqt error aaya</td></tr>');
        }
    });
}

function renderAssignSummary(rows) {

    if (assignSummaryTable) {
        assignSummaryTable.destroy();
        assignSummaryTable = null;
    }

    var $tbody = $('#assignSummaryBody');
    $tbody.empty();

    if (!rows || rows.length === 0) {
        $tbody.html('<tr><td colspan="5" class="text-center">Koi data nahi mila</td></tr>');
        $('#summaryGrandTotal').text('0');
        return;
    }

    var grandTotal = 0;

    $.each(rows, function (i, row) {
        grandTotal += row.count;

        var $tr = $('<tr>');
        $tr.append('<td>' + escapeHtml(row.assign_date) + '</td>');
        $tr.append('<td>' + escapeHtml(row.emp_id) + '</td>');
        $tr.append('<td>' + escapeHtml(row.agent_name || '—') + (row.msd_id ? ' (' + escapeHtml(row.msd_id) + ')' : '') + '</td>');
        $tr.append('<td class="text-center"><span class="badge badge-primary">' + row.count + '</span></td>');
        $tr.append('<td class="text-center"><span class="badge badge-secondary">' + row.day_total + '</span></td>');

        $tbody.append($tr);
    });

    $('#summaryGrandTotal').text(grandTotal);

    assignSummaryTable = makeDataTable_Basic('assignSummaryTable');
}

// Page load pe aaj tak ka summary auto-load kar do
loadAssignSummary();
  
  </script>
