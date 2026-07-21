<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Route Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.route-card{
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.timeline{
    position:relative;
    margin:20px 0;
    padding-left:60px;
}

.timeline:before{
    content:"";
    position:absolute;
    left:24px;
    top:0;
    bottom:0;
    width:3px;
    background:#d9d9d9;
}

.timeline-item{
    position:relative;
    margin-bottom:35px;
}

.timeline-item:last-child{
    margin-bottom:0;
}

.marker{
    position:absolute;
    left:-48px;
    width:28px;
    height:28px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:13px;
    z-index:99;
}

.marker.source{
    background:#28a745;
}

.marker.destination{
    background:#dc3545;
}

.marker.normal{
    background:#6c757d;
}

.stop-box{
    background:#fff;
    border-radius:12px;
    padding:15px;
    box-shadow:0 3px 8px rgba(0,0,0,.08);
}

.stop-name{
    font-size:18px;
    font-weight:600;
}

.stop-time{

    margin-top:8px;

}

.badge-time{

    background:#e9ecef;

    color:#333;

    padding:6px 10px;

    border-radius:30px;

    font-size:13px;

    margin-right:10px;

}

.badge-source{

    background:#28a745;

}

.badge-destination{

    background:#dc3545;

}

.bus-info{

    background:#fff;

    border-radius:15px;

    padding:20px;

    margin-bottom:25px;

    box-shadow:0 4px 15px rgba(0,0,0,.08);

}

.info-title{

    font-size:13px;

    color:#777;

}

.info-value{

    font-size:18px;

    font-weight:bold;

}

</style>

</head>

<body>

<div class="container">

<div class="card route-card">

<div class="card-header bg-success text-white">

<i class="fa fa-bus"></i>

Bus Details

</div>

<div class="card-body">

<div class="bus-info">

<div class="row">

<div class="col-md-3">

<div class="info-title">

Service No

</div>

<div class="info-value">

<?php echo $bus->ServiceNo;?>

</div>

</div>

<div class="col-md-3">

<div class="info-title">

Depot

</div>

<div class="info-value">

<?php echo $bus->DepotName;?>

</div>

</div>

<div class="col-md-3">

<div class="info-title">

Bus Type

</div>

<div class="info-value">

<?php echo $bus->BusType;?>

</div>

</div>

<div class="col-md-3">

<div class="info-title">

Journey Time

</div>

<div class="info-value">

<?php echo $bus->TimeTaken;?>

</div>

</div>

</div>

<hr>

<div class="row">

<div class="col-md-4">

<div class="info-title">

Departure

</div>

<div class="info-value text-success">

<?php echo $bus->DepartureTime;?>

</div>

</div>

<div class="col-md-4">

<div class="info-title">

Arrival

</div>

<div class="info-value text-danger">

<?php echo $bus->ArrivalTime;?>

</div>

</div>

<div class="col-md-4">

<div class="info-title">

Total Stops

</div>

<div class="info-value">

<?php echo $route->TotalStops;?>

</div>

</div>

</div>

</div>

<div class="card mt-3">

<div class="card-header bg-primary text-white">

<i class="fa fa-route"></i>

Journey Timeline

</div>

<div class="card-body">

<div class="timeline">

<?php

$total=count($timeline);

foreach($timeline as $index=>$row){

$class="normal";

$badge="";

$icon="fa-location-dot";

if($index==0){

$class="source";

$badge='<span class="badge bg-success">Source</span>';

$icon="fa-play";

}

if($index==$total-1){

$class="destination";

$badge='<span class="badge bg-danger">Destination</span>';

$icon="fa-flag-checkered";

}

?>

<div class="timeline-item">

<div class="marker <?php echo $class;?>">

<i class="fa <?php echo $icon;?>"></i>

</div>

<div class="stop-box">

<div class="d-flex justify-content-between">

<div>

<div class="stop-name">

<?php echo $row->StopName;?>

</div>

<?php echo $badge;?>

</div>

<div>

<i class="fa fa-bus text-primary"></i>

</div>

</div>

<div class="stop-time">

<span class="badge-time">

Arrival :

<?php echo !empty($row->ArrivalTime)?$row->ArrivalTime:"--";?>

</span>

<span class="badge-time">

Departure :

<?php echo !empty($row->DepartureTime)?$row->DepartureTime:"--";?>

</span>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

<div class="mt-3 mb-5">

<a href="<?php echo base_url('hrtc'); ?>" class="btn btn-secondary">

<i class="fa fa-arrow-left"></i>

Back To Search

</a>

</div>

</div>

</body>

</html>