<!DOCTYPE html>
<html>
<head>

<title>Daily Knowledge Booster</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>

:root{
    --brand-1:#4f46e5;
    --brand-2:#0ea5a4;
    --ink:#1f2430;
    --muted:#6b7280;
    --line:#e7e9ee;
    --success-bg:#e9fbf1;
    --success-line:#b7ecd0;
    --success-text:#0f8a4b;
    --shadow:0 10px 30px rgba(31,36,48,0.08);
}

body{
    background:#f4f5fa;
    font-family:'Inter', 'Poppins', sans-serif;
    color:var(--ink);
}

/* ---------- Top banner ---------- */

.dkb-banner{
    background:linear-gradient(120deg, var(--brand-1) 0%, var(--brand-2) 100%);
    border-radius:16px;
    padding:32px 30px;
    margin-top:26px;
    color:#ffffff;
    box-shadow:var(--shadow);
    position:relative;
    overflow:hidden;
}

.dkb-banner::after{
    content:"";
    position:absolute;
    right:-40px;
    top:-40px;
    width:180px;
    height:180px;
    border-radius:50%;
    background:rgba(255,255,255,0.12);
}

.dkb-banner-icon{
    width:56px;
    height:56px;
    border-radius:14px;
    background:rgba(255,255,255,0.18);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
    margin-bottom:14px;
}

.dkb-banner h2{
    font-family:'Poppins', sans-serif;
    font-weight:700;
    font-size:28px;
    margin-bottom:6px;
}

.dkb-banner p{
    margin:0;
    opacity:0.92;
    font-size:14.5px;
    max-width:560px;
}

/* ---------- Main card ---------- */

.main-box{
    background:white;
    padding:28px;
    border-radius:16px;
    box-shadow:var(--shadow);
    margin-top:22px;
    border:1px solid var(--line);
}

.heading{
    text-align:center;
    margin-bottom:25px;
}

/* ---------- Tabs, pill style ---------- */

.nav-tabs{
    border-bottom:none;
    background:#f1f2f8;
    padding:6px;
    border-radius:12px;
    gap:6px;
}

.nav-tabs .nav-link{
    border:none;
    border-radius:9px;
    font-weight:600;
    font-size:14.5px;
    color:var(--muted);
    padding:10px 20px;
    transition:all 160ms ease;
}

.nav-tabs .nav-link:hover{
    color:var(--brand-1);
}

.nav-tabs .nav-link.active{
    background:linear-gradient(120deg, var(--brand-1), var(--brand-2));
    color:#ffffff;
    box-shadow:0 6px 14px rgba(79,70,229,0.28);
}

/* ---------- Question cards ---------- */

.question-box{
    padding:22px;
    margin-bottom:18px;
    border:1px solid var(--line);
    border-radius:12px;
    background:#fbfbfe;
    transition:box-shadow 160ms ease, transform 160ms ease;
    position:relative;
}

.question-box:hover{
    box-shadow:0 8px 20px rgba(31,36,48,0.08);
    transform:translateY(-2px);
}

.question-box .q-title{
    font-family:'Poppins', sans-serif;
    font-weight:600;
    font-size:16.5px;
    margin-bottom:16px;
    padding-right:40px;
}

.option{
    margin-bottom:10px;
    padding:10px 14px;
    border:1px solid var(--line);
    border-radius:8px;
    background:#ffffff;
    font-size:14.5px;
    transition:border-color 140ms ease, background 140ms ease;
}

.option:hover{
    border-color:var(--brand-1);
    background:#f5f5ff;
}

.answer-div{
    display:none;
    margin-top:15px;
    padding:16px 18px;
    background:var(--success-bg);
    border:1px solid var(--success-line);
    border-radius:10px;
    color:var(--success-text);
    font-size:14.5px;
    line-height:1.6;
}

.btn-show-answer{
    background:linear-gradient(120deg, var(--brand-1), var(--brand-2));
    color:#ffffff;
    border:none;
    border-radius:8px;
    font-weight:600;
    font-size:13.5px;
    padding:8px 18px;
    box-shadow:0 4px 10px rgba(79,70,229,0.25);
    transition:transform 140ms ease, box-shadow 140ms ease;
}

.btn-show-answer:hover{
    color:#ffffff;
    transform:translateY(-1px);
    box-shadow:0 6px 14px rgba(79,70,229,0.32);
}

/* ---------- Footer ---------- */

.footer-box{
    background:#ffffff;
    padding:26px 28px;
    border-radius:16px;
    box-shadow:var(--shadow);
    margin-bottom:30px;
    border-top:4px solid transparent;
    border-image:linear-gradient(120deg, var(--brand-1), var(--brand-2));
    border-image-slice:1;
}

.footer-box h6{
    font-family:'Poppins', sans-serif;
    font-weight:700;
    font-size:16px;
    margin-bottom:10px;
    color:var(--ink);
}

.footer-box p{
    margin-bottom:8px;
    color:var(--muted);
    font-size:14px;
    line-height:1.7;
}

.footer-box .footer-heart{
    color:#ef4444;
}

</style>


</head>
<body>


<div class="container">

<!-- Top banner -->
<div class="dkb-banner">
    <div class="dkb-banner-icon">📚</div>
    <h2>Daily Knowledge Booster</h2>
    <p>Every problem looks small once you build the habit of learning something new, every single day.</p>
</div>

<div class="main-box">

<h2 class="heading">
Vocabulary Practice
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
            Made with <span class="footer-heart">❤️</span> for your regular knowledge upgradation.
        </p>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



<script>


function showAnswer(id)
{

$("#answer_"+id).slideToggle(180);

}


</script>


</body>
</html>