<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['gid'])) die("{status: false}");
    $__gid = $_POST['gid'];
    $get_group = $conn->query("SELECT group_texts FROM groups WHERE id = $__gid;");
    if ($get_group){
        $result = "";
        while($r = $get_group->fetch_assoc()){
            $result = $r['group_texts'];
        }
        if ($result != ""){
            $c = array(
                "status" => true,
                "conf" => json_decode($result)
            );
            die(json_encode($c));
        }
    } else die ("{status: false}");
?>