<?php
    include 'desk.php';
    include 'conn.php';
    if(!isset($_POST['input_text'])) die("<error_no_params>");
    if(!isset($_POST['__pid'])) die("<error_no_params>");
    global $config;
    global $conn;
    $input_text = $_POST['input_text'];
    $input_text = str_replace('\'', '&apos;', $input_text);
    $input_text = str_replace("\"", "&quot;", $input_text);
    $input_text = str_replace("<", "&lt;", $input_text);
    $input_text = str_replace(">", "&gt;", $input_text);
    $__pid = $_POST['__pid'];
    try{
        $tq = $conn->query("INSERT INTO post_comments (post_id, author_id, comment_context, comment_likes) VALUES ($__pid, " . $config['id'] . ", '$input_text', '[]')");
        if($tq){
            if($conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . get_single_query_from_id($__pid, 'owner_id', 'posts') . ", '" . get_single_query_from_id($config['id'], 'uname', 'users') . " commented on your post', 0);")){
                die("<comment_added>");
            } else die("<server_error>");
        } else {
            die("<server_error>");
        }
    }
    catch(\Throwable $t){
        die("<server_error>");
    }
?>