<?php
    include 'conn.php';
    include 'desk.php';
    if(!isset($_POST['fid'])) die("<no_params_set>");
    $fid = $_POST['fid'];
    $res = [];
    try{
        // $q = $conn->query("SELECT * FROM texts WHERE from_id = " . $config['id'] . " OR to_id = " . $config['id'] . " AND from_id = $fid OR to_id = $fid;");
        $q = $conn->query("SELECT * FROM texts WHERE from_id = " . $config['id'] . " AND to_id = " . $fid . " OR from_id = $fid AND to_id = " . $config['id'] . ";");
        if($q){
            if($q->num_rows > 0){
                while($r = $q->fetch_assoc()){
                    $res[] = $r;
                }
            } else die(json_encode([]));
        } else die("<error>");
        echo json_encode($res);
    }
    catch(\Throwable $t){
        die("<error>");
    }
?>