<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['id'])) die();
    $picid = (int) $_POST['id'];
    die(get_profile_from_id($picid));
?>