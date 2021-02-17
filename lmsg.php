<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['uid'])) die('<no_params_set>');
    $uid = $_POST['uid'];
    try{
        $update = $conn->query("UPDATE texts SET liked = true WHERE id = " . $uid . ";");
        if ($update) die('<success>');
        else die('<error>');
    }
    catch(\Throwable $t){
        die('<error>');
    }
?>