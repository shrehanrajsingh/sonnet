<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['config'])) die('<no_params_set>');
    $conf = $_POST['config'];
    $dec = json_decode($conf, true);
    $deliver_address = (int) $dec['to'];
    // * Get original texts
    $gm = $conn->query("SELECT group_texts FROM groups WHERE id = $deliver_address");
    $dat = [];
    if ($gm) while($r_ = $gm->fetch_assoc()) $dat = json_decode($r_['group_texts'], true);
    $dat[] = json_decode($conf, true);
    $send_msg = $conn->query("UPDATE groups SET group_texts = '" . json_encode($dat) . "' WHERE id = $deliver_address;");
    if ($send_msg) die('<success>');
    else die('<error>');
?>