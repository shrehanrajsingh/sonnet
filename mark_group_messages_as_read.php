<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['gid'])) die();
    if (!isset($_POST['txid'])) die();
    $gid = $_POST['gid'];
    $txid = $_POST['txid'];
    $get_group = $conn->query("SELECT group_texts FROM groups WHERE id = $gid;");
    if ($get_group){
        if ($get_group->num_rows > 0){
            while($r = $get_group->fetch_assoc()){
                $txts = json_decode($r['group_texts'], true);
                if (in_array((int) $config['id'], $txts[$txid]["txt_seen_by"])) die();
                $txts[$txid]["txt_seen_by"][] = (int) $config['id'];
                $upd = $conn->query("UPDATE groups SET group_texts = '" . json_encode($txts) . "' WHERE id = $gid;");
                if (!$upd) die('<error>');
                else die('<success>');
            }
        }
    }
?>