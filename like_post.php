<?php
include 'desk.php';

$pid = "";
if(isset($_POST['post_id'])){
    $pid = $_POST['post_id'];
    $res = handle_like($pid);
    if($res == "liked") echo "liked";
    else if ($res == "disliked") echo "disliked";
    else echo "failure";
}
else echo "Network Cancelled";
?>