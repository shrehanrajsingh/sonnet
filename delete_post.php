<?php
include 'desk.php';
global $conn;
global $config;
if(!isset($_POST['post_id'])) die("<error [object Object]> -> Access declined");
$a = $_POST['post_id'];
$q = $conn->query("SELECT * FROM posts WHERE id = $a;");
if($q){
    if($q->num_rows > 0){
        while($r = $q->fetch_assoc()){
            $owner_em = get_single_query_from_id($r['owner_id'], "email", "users");
            $post_file = $r['filename'];
            $file_url = './public/client/users/' . md5($owner_em) . '/posts/' . $post_file;
            if(unlink($file_url)){
                if($conn->query("DELETE FROM posts WHERE id = " . $r['id'] . ";")) echo "<success>";
                else echo "<query_not_deleted>";
            }
            else echo "<file_not_deleted>";
        }
    }
    else echo "<column_not_found>";
}
else echo "<query_error>";
?>