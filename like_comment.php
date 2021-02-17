<?php
include 'conn.php';
include 'desk.php';
if (!isset($_POST['comment_cred'])) die("<invalid_input>");
global $conn;
global $config;
$cid = $_POST['comment_cred'];
$root_query = $conn->query("SELECT * FROM post_comments WHERE id = $cid;");
if ($root_query) {
    if ($root_query->num_rows > 0) {
        $r = $root_query->fetch_assoc();
        $cl = json_decode($r['comment_likes']);
        // * Check if already liked, if true, dislike
        if (in_array($config['id'], $cl)) {
            // ! Dislike
            if (($key = array_search($config['id'], $cl)) !== false) {
                unset($cl[$key]);
            }
        } else {
            // ? Like
            $cl[] = $config['id'];
        }
        // * Update 
        // echo gettype(json_encode($cl));
        $update_database = $conn->query("UPDATE post_comments SET comment_likes = '" . json_encode($cl) . "' WHERE id = $cid;");
        if ($update_database) {
            echo '<success>';
        } else {
            echo '<query_error>';
        }
    }
} else {
    echo '<query_error>';
}
