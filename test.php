<?php
$link = mysqli_connect('localhost', 'newm_mwi', 'OCs*isNNJI6^g4xI', 'newm_mwi');

$result= mysqli_query($link, 'SELECT * FROM `income_reports`');
while($row = mysqli_fetch_array($result)){
    $income_type = $row['income_type'];
    if($income_type=='DIRECT INCOME'){
        $user_id = $row['user_id'];
        $amount = $row['amount'];
        mysqli_query($link, "UPDATE `users` SET `direct`=`direct`+$amount WHERE `id`='$user_id'");
    } elseif($income_type=='MATCHING INCOME'){
        $user_id = $row['user_id'];
        $amount = $row['amount'];
        mysqli_query($link, "UPDATE `users` SET `matching`=`matching`+$amount WHERE `id`='$user_id'");
    } elseif($income_type=='DIRECT TEAM MATCHING INCOME'){
        $user_id = $row['user_id'];
        $amount = $row['amount'];
        mysqli_query($link, "UPDATE `users` SET `direct_team_matching`=`direct_team_matching`+$amount WHERE `id`='$user_id'");
    }
}




?>