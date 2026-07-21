<!DOCTYPE html>
<html>
<head>

<title>Daily Knowledge Booster</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


<style>

body{
    background:#f5f5f5;
}

.main-box{

    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0px 0px 10px #ddd;
    margin-top:30px;

}


.question-box{

    padding:20px;
    margin-bottom:20px;
    border:1px solid #ddd;
    border-radius:8px;

}


.answer-div{

display:none;
margin-top:15px;
padding:15px;
background:#e8fff0;
border-radius:5px;

}


.option{

margin-bottom:10px;

}


.heading{

text-align:center;
margin-bottom:25px;

}
.footer-box{
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px #ddd;
    margin-bottom: 30px;
}

.footer-box h6{
    font-weight: 600;
    margin-bottom: 10px;
}

.footer-box p{
    margin-bottom: 8px;
    color: #666;
    font-size: 14px;
    line-height: 1.7;
}

</style>


</head>
<body>


<div class="container">


<div class="main-box">

<h2 class="heading">
Daily Knowledge Booster
</h2>



<ul class="nav nav-tabs" id="myTab">

<!-- <li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#aptitude">Aptitude</a>
</li>


<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#reasoning">Reasoning</a>
</li> -->


<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#vocabulary">Vocabulary</a>
</li>


<!-- <li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#gk">GK</a>
</li> -->


</ul>




<div class="tab-content mt-4">


<!-- APTITUDE -->


<!-- <div class="tab-pane fade show active" id="aptitude">

<?php $this->load->view('questions_layout',['questions'=>$aptitude]); ?>

</div> -->



<!-- REASONING -->


<!-- <div class="tab-pane fade" id="reasoning">

<?php $this->load->view('questions_layout',['questions'=>$reasoning]); ?>

</div> -->



<!-- VOCABULARY -->


<div class="tab-pane fade show active" id="vocabulary">

<?php $this->load->view('questions_layout',['questions'=>$vocabulary]); ?>

</div>



<!-- GK -->


<!-- <div class="tab-pane fade" id="gk">

<?php $this->load->view('questions_layout',['questions'=>$gk]); ?>

</div> -->



</div>



</div>



</div>
<!-- Footer -->

<footer class="mt-4">
    <div class="footer-box text-center">
        <h6>Daily Knowledge Booster</h6>
        <!-- <p>
            This module is designed for your daily practice and continuous learning.
            Practice a few questions every day to improve your Aptitude, Reasoning,
            English Vocabulary, and General Knowledge.
        </p> -->
        <p>
            This module is designed for your daily practice and continuous learning.
            Practice a few questions every day to improve your 
            English Vocabulary.
        </p>

        <p class="mb-0">
            Made with ❤️ for your regular knowledge upgradation.
        </p>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



<script>


function showAnswer(id)
{

$("#answer_"+id).toggle();

}


</script>


</body>
</html>
