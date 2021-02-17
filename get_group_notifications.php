<?php
    include 'conn.php';
    include 'desk.php';
    if (!isset($_POST['verify'])) die();
    $group_msg_count = 0;
    $get_group = $conn->query("SELECT * FROM groups WHERE JSON_SEARCH(members, 'all', '" . $config['id'] . "') IS NOT NULL;");
    if ($get_group){
        if ($get_group->num_rows > 0){
            while($r = $get_group->fetch_assoc()){
                $chk_arr = $r['group_texts'];
                $chk_arr = json_decode($chk_arr, true);
                foreach ($chk_arr as $key) {
                    if (!in_array($config['id'], $key['txt_seen_by'])) $group_msg_count++;
                }
            }
        }
    }
    echo $group_msg_count;
?>