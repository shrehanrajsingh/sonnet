<?php
    include 'conn.php';
    include 'desk.php';
    if(!isset($_POST['data'])) die("<no_param_set>");
    if(!isset($_POST['tar'])) die("<no_param_set>");
    $data = $_POST['data'];
    $tar = $_POST['tar'];
    $data = str_replace('\'', '&apos;', $data);
    $data = str_replace("\"", '&quot;', $data);
    $data = str_replace('<', '&lt;', $data);
    $data = str_replace('>', '&gt;', $data);
    try{
        $addMsg = $conn->query("INSERT INTO texts (from_id, to_id, text_content, seen, liked) VALUES (" . $config['id'] . ", $tar, '" . $data . "', false, 0)");
        if($addMsg){
            echo "<success>";
        } else {
            echo "<error>";
        }
    }
    catch(\Throwable $t){
        die("<error>");
    }
?>