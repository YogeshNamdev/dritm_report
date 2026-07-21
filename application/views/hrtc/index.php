<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <title><?php echo isset($title) ? $title : 'HRTC Route Search'; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>

        body{
            background:#f4f6f9;
        }

        .search-card{

            margin-top:40px;

            border-radius:12px;

            box-shadow:0 0 15px rgba(0,0,0,.1);

        }

        .card-header{

            background:#198754;

            color:#fff;

            font-size:22px;

            font-weight:bold;

        }

        .loader{

            display:none;

            text-align:center;

            padding:40px;

        }

        #resultArea{

            display:none;

        }

        #noResult{

            display:none;

        }

        .select2-container--default .select2-selection--single{

            height:38px;

        }

        .select2-selection__rendered{

            line-height:36px !important;

        }

        .select2-selection__arrow{

            height:36px !important;

        }

    </style>

</head>

<body>

<div class="container">

<div class="card search-card">

<div class="card-header">

<i class="fa fa-bus"></i>

HRTC Route Search

</div>

<div class="card-body">

<div class="row">

<div class="col-md-5">

<label>

<strong>Source</strong>

</label>

<select

class="form-control"

id="source"

name="source">

<option value="">Select Source</option>

<?php foreach($stops as $stop){ ?>

<option value="<?php echo $stop->StopId; ?>">

<?php echo $stop->StopName; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-5">

<label>

<strong>Destination</strong>

</label>

<select

class="form-control"

id="destination"

name="destination">

<option value="">Select Destination</option>

<?php foreach($stops as $stop){ ?>

<option value="<?php echo $stop->StopId; ?>">

<?php echo $stop->StopName; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-2">

<label>&nbsp;</label>

<button

type="button"

id="btnSearch"

class="btn btn-success w-100">

<i class="fa fa-search"></i>

Search

</button>

</div>

</div>

</div>

</div>

<div class="loader" id="loader">

<div class="spinner-border text-success"></div>

<br><br>

Searching Routes...

</div>

<div

class="alert alert-warning mt-3"

id="noResult">

No Bus Available For Selected Route.

</div>

<div

id="resultArea"

class="card mt-3">

<div class="card-header bg-primary text-white">

Available Buses

</div>

<div class="card-body">

<table

class="table table-bordered table-striped"

id="resultTable">

<thead>

<tr>

<th width="8%">#</th>

<th>Service No</th>

<th>Depot</th>

<th>Bus Type</th>

<th>Departure</th>

<th>Arrival</th>

<th>Duration</th>

<th>Total Stops</th>

<th width="120">

Action

</th>

</tr>

</thead>

<tbody>

</tbody>

</table>

</div>

</div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(function(){

$("#source").select2({

placeholder:"Select Source"

});

$("#destination").select2({

placeholder:"Select Destination"

});

});

</script>
<script>

var base_url = "<?php echo base_url(); ?>";

</script>

<script src="<?php echo base_url();?>assets/js/hrtc.js"></script>
</body>

</html>