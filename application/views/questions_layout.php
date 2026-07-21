<?php

$count=1;

foreach($questions as $row)
{


?>


<div class="question-box">


<h5>

Q<?php echo $count;?>.

<?php echo $row->question;?>

</h5>


<p>

<b>Topic :</b>

<?php echo $row->topic;?>

</p>



<div class="option">

1. <?php echo $row->option1;?>

</div>


<div class="option">

2. <?php echo $row->option2;?>

</div>


<div class="option">

3. <?php echo $row->option3;?>

</div>


<div class="option">

4. <?php echo $row->option4;?>

</div>




<button class="btn btn-primary btn-sm"

onclick="showAnswer(<?php echo $row->id;?>)">

Show Answer

</button>



<div class="answer-div"

id="answer_<?php echo $row->id;?>">


<h6>

Correct Answer :

Option <?php echo $row->correct_option;?>

</h6>


<p>

<?php echo $row->explanation;?>

</p>


</div>



</div>



<?php

$count++;

}

?>
