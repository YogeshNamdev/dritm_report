
  <!-- Bootstrap Switch -->
<script src="<?= base_url(); ?>includes/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>


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
                        <li class="breadcrumb-item active">Fee Structure Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
        <section class="content">
            <div class="container-fluid">

                <div class="card card-default">

                    <div class="card-header">
                        <h3 class="card-title m-0">Fee Structure Master</h3>
                    </div>

                    <div class="card-body">

                        <form id="fee_structure_add_details" method="post" enctype= "multipart/form-data" >
                        <div class="row">

                        <!-- LEFT FORM -->
                            <div class="col-lg-4">
                            <div class="fee-panel">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control" id="course_id" name="course_id">
                                        <option value="0">-- Select Course --</option>
                                        <?php
                                        if($courses != FALSE){
                                            foreach($courses as $courselist){
                                                echo "<option value='".$courselist->id."'>".$courselist->course_name."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>


                                <?php foreach($types as $t){ ?>
                                <div class="fee-item">
                                <input type="hidden" name="type_id[]" value="<?=$t->id?>">
                                <label><?=$t->fee_name?></label>
                                <input type="number" class="form-control amount" name="amount[]" placeholder="Enter amount">
                                </div>
                                <?php } ?>


                                <div class="form-group mt-3">
                                    <button type="button" 
                                    class="btn btn-primary submit-btn"
                                    onclick="add_fee_structure();">
                                    Save Fee Structure
                                    </button>
                                </div>

                                <div id="err_msg"></div>

                                </div>

                            </div>



                            <!-- RIGHT TABLE -->
                            <div class="col-lg-8">
                                <div id="fee_stucture_list_div">

                                </div>
                            </div>


                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </section>

</div>

<div class="modal fade" id="edit_fee_structure_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit batch</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="edit_fee_structure_add_details" method="post" enctype= "multipart/form-data" >
              <input type="hidden" id="edit_course_hidden" name="edit_course_hidden" />

              
              <div class="form-group">
                    <label for="role_id">Course</label>
                    <select class="form-control" id="edit_course_id" name="edit_course_id" disabled>
                    <option value="0">-- Select --</option>
                    <?php
                        if($courses != FALSE){
                            foreach($courses as $courselist){
                                echo "<option value='".$courselist->id."'>".$courselist->course_name."</option>";
                            }
                        }
                    ?>
                    </select>
                </div>
                <?php foreach($types as $t){ ?>
                <div class="fee-item">

                    <input type="hidden" id="edit_type_id" name="edit_type_id[]" value="<?=$t->id?>">

                    <label><?=$t->fee_name?></label>

                    <input type="number"
                        class="form-control edit_amount" name="edit_amount[]" id="edit_amount"
                        data-type="<?=$t->id?>"
                        placeholder="Enter amount">

                </div>
                <?php } ?>
              <div class="form-group" id="edit_err_msg"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary esubmit-btn" onclick="edit_fee_structure();">Submit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
  </div>
  <script>
    function add_fee_structure(){

    $("#err_msg").html("");

    var course_id = $("#course_id").val();
    var valid = true;
    var message = "";

    // COURSE VALIDATION
    if(course_id == "0" || course_id == ""){
        valid = false;
        message = "Please select course";
        $("#course_id").focus();
    }

    // AMOUNT VALIDATION (loop all inputs)
    $(".amount").each(function(){

        if($(this).val().trim() == "" || $(this).val() <= 0){

            valid = false;

            var label = $(this).closest(".fee-item").find("label").text();

            message = "Please enter valid amount for "+label;

            $(this).focus();

            return false; // stop loop
        }

    });

    if(!valid){
        $("#err_msg").html("<span style='color:red;font-weight:bold'>"+message+"</span>");
        return;
    }

    // AJAX START
    $("#err_msg").html("<span style='color:blue'><i class='fa fa-spinner fa-spin'></i> Saving...</span>");

    var base_url = '<?php echo base_url(); ?>';

    let myForm = document.getElementById('fee_structure_add_details');
    let formData = new FormData(myForm);

    $(".submit-btn").prop("disabled", true);

    $.ajax({
        type: "POST",
        url: base_url+"app/fee_structure/save",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache:false,

        success:function(data){

            if(data.response == true){

                $("#err_msg").html("<span style='color:green;font-weight:bold'>Saved Successfully</span>");
                $("#fee_structure_add_details")[0].reset();
                 get_all_fee_structure();

            }else{
                $("#err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
            }

            $(".submit-btn").prop("disabled", false);
        },

        error:function(){
            $("#err_msg").html("<span style='color:red'>Server error</span>");
            $(".submit-btn").prop("disabled", false);
        }

    });

}


   

    function get_all_fee_structure()
{
    $("#fee_stucture_list_div").html(
        "<center><b><i class='fa fa-spinner fa-spin'></i> Loading...</b></center>"
    );

    var base_url = '<?php echo base_url(); ?>';

    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: base_url+"app/fee_structure/get_all_fee_structure",
        data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' },
        cache:false,

        success:function(data){

            if(data.response == true){

                /* ---------- GROUP DATA BY COURSE ---------- */
                let grouped = {};

                for(let i=0;i<data.total_record;i++){

                    let r = data.all_record[i];

                    if(!grouped[r.course_id]){
                        grouped[r.course_id] = {
                            course_name : r.course_name,
                            fees : []
                        };
                    }

                    grouped[r.course_id].fees.push({
                        fee_name : r.fee_name,
                        amount : r.amount
                    });
                }

                /* ---------- BUILD TABLE ---------- */

                let txt = "<table class='table table-bordered table-sm'>";
                txt += "<thead>";
                txt += "<tr>";
                txt += "<th width='5%'>#</th>";
                txt += "<th>Course</th>";
                txt += "<th>Fee Details</th>";
                txt += "<th width='120'>Action</th>";
                txt += "</tr>";
                txt += "</thead><tbody>";

                let sr=1;

                for(let course_id in grouped){

                    let c = grouped[course_id];

                    /* make fee list html */
                    let fee_html = "";

                    for(let j=0;j<c.fees.length;j++){

                        fee_html += 
                        "<div style='border-bottom:1px solid #eee;padding:3px 0'>"
                        +"<b>"+c.fees[j].fee_name+"</b> : ₹ "+c.fees[j].amount
                        +"</div>";
                    }

                    txt += "<tr>";
                    txt += "<td>"+(sr++)+"</td>";
                    txt += "<td>"+c.course_name+"</td>";
                    txt += "<td>"+fee_html+"</td>";

                    txt += "<td align='center'>";

                    /* EDIT -> send course_id */
                    txt += "<i class='fa fa-edit' style='color:blue;cursor:pointer' data-toggle='modal' data-target='#edit_fee_structure_modal'";
                    txt += "onclick='get_fee_structure_detail_by_id("+course_id+")'></i>";

                    txt += "&nbsp;&nbsp;";

                    /* DELETE -> send course_id */
                    txt += "<i class='fa fa-trash' style='color:red;cursor:pointer' ";
                    txt += "onclick='delete_fee_structure("+course_id+")'></i>";

                    txt += "</td>";
                    txt += "</tr>";
                }

                txt += "</tbody></table>";

                $("#fee_stucture_list_div").html(txt);

            }else{
                $("#fee_stucture_list_div").html(
                    "<center style='color:red'><b>"+data.message+"</b></center>"
                );
            }
        }
    });
}

   function get_fee_structure_detail_by_id(course_id)
    {
    var base_url = '<?php echo base_url(); ?>';
    $("#edit_course_id").val(course_id);
    $("#edit_course_hidden").val(course_id);

    // clear old values
    $(".edit_amount").val("");

    $.ajax({

        type:"POST",
        dataType:"JSON",
        url: base_url+"app/fee_structure/get_fee_structure_detail_by_id",

        data:{
            '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
            'course_id':course_id
        },

        success:function(data){

            if(data.response == true){

                // LOOP ALL RECORDS
                for(let i=0;i<data.total_record;i++){

                    let type_id = data.all_record[i].fee_type_id;
                    let amount  = data.all_record[i].amount;

                    // FIND INPUT WITH SAME TYPE
                    $(".edit_amount[data-type='"+type_id+"']").val(amount);
                }

                // open modal
                $("#edit_fee_structure_modal").modal("show");
            }

        }
    });
}
   
    function edit_fee_structure()
    {
     $("#edit_err_msg").html("");

    var course_id = $("#edit_course_id").val();
    var course_hidden = $("#edit_course_hidden").val();
    
    var valid = true;
    var message = "";

    // COURSE VALIDATION
    if(course_id == "0" || course_id == ""){
        valid = false;
        message = "Please select course";
        $("#course_id").focus();
    }

    // AMOUNT VALIDATION (loop all inputs)
    $(".edit_amount").each(function(){

        if($(this).val().trim() == "" || $(this).val() <= 0){

            valid = false;

            var label = $(this).closest(".fee-item").find("label").text();

            message = "Please enter valid amount for "+label;

            $(this).focus();

            return false; // stop loop
        }

    });

    if(!valid){
        $("#edit_err_msg").html("<span style='color:red;font-weight:bold'>"+message+"</span>");
        return;
    }

    // AJAX START
    $("#edit_err_msg").html("<span style='color:blue'><i class='fa fa-spinner fa-spin'></i> Saving...</span>");

    var base_url = '<?php echo base_url(); ?>';

    let myForm = document.getElementById('edit_fee_structure_add_details');
    let formData = new FormData(myForm);

    $(".esubmit-btn").prop("disabled", true);

    $.ajax({
        type: "POST",
        url: base_url+"app/fee_structure/edit_fee_structure",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        cache:false,

        success:function(data){

            if(data.response == true){

                $("#edit_err_msg").html("<span style='color:green;font-weight:bold'>Update Successfully</span>");
                $("#edit_fee_structure_add_details")[0].reset();
                 get_all_fee_structure();

            }else{
                $("#edit_err_msg").html("<span style='color:red;font-weight:bold'>"+data.message+"</span>");
            }

            $(".esubmit-btn").prop("disabled", false);
        },

        error:function(){
            $("#edit_err_msg").html("<span style='color:red'>Server error</span>");
            $(".esubmit-btn").prop("disabled", false);
        }

    });
    }
   
   function delete_fee_structure(course_id)
    {
     var conf = confirm("Are you sure to delete this user?");
     if(conf == true)
      {
       var base_url = '<?php echo base_url(); ?>';
           $.ajax({
                   type: "POST",
                   dataType: "JSON",
                   url: base_url+"app/fee_structure/delete_fee_structure",
                   data: { '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>' , 'course_id':course_id },
                   cache: false,
                   success: function (data, textStatus, jqXHR) {
                            
                            if(data.response == true)
                             {
                             get_all_fee_structure();
                             }
                            else
                             {
                              alert(data.message);
                             }
                            
                   }
                 });
      }
    }
   
   

   
   
   get_all_fee_structure();
   
  
  </script>
