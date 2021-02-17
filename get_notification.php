<?php

use function PHPSTORM_META\type;

include 'conn.php';
    include 'desk.php';
    if(!isset($_POST['nid'])) die('<no_param_set>');
    $dt = $_POST['nid'];
    $gm = $conn->query("SELECT * FROM activity WHERE act_to_id = " . $config['id'] . " AND marked_as_read = false;");
    $arr = [];
    if ($gm){
        if ($gm->num_rows > 0){
            while($r = $gm->fetch_assoc()){
                // if (strtotime($r['act_sent_on']) - substr($dt, 0, 10) < 1800 ) $arr[] = $r;
                if (time() - strtotime($r['act_sent_on']) < 1800 ) $arr[] = $r;
            }
        }
    }
    echo json_encode($arr);
?>