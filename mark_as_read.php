<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['mid'])) die('<no_params_set>');
    $m = $_POST['mid'];
    try{
        $edit = $conn->query("UPDATE texts SET seen = true WHERE id = " . $m . " AND seen != true;");
        if($edit) die('<success>');
        else die('<error>');
    }
    catch(\Throwable $t){
        die('<death>');
    }
?>