<?php
include 'conn.php';
include 'desk.php';
if (!isset($_POST['obj'])) die('<no_params_set>');
$obj = $_POST['obj'];
$obj = json_decode($obj, true);
$obj['members'][] = intval($config['id']);
// print_r($obj['members']);
try {
    $make = $conn->query("INSERT INTO groups (group_name, members, admins, group_desc, icon, group_texts, founder) VALUES (
        '" . $obj["name"] . "',
        '" . json_encode($obj["members"]) . "'
        , '[" . $config['id'] . "]',
         '" . $obj["desc"] . "', 
         './temp/group_main.jpg',
         '{}',
         '" . $config['id'] . "'
         )"
         );
    if ($make){
        $stat = [];
        foreach ($obj['members'] as $key) {
            if ($key == $config['id']) continue;
            if ($conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES ($key, 'You were added to group: " . $obj["name"] . "', 0);")) $stat[] = 1;
            else $stat[] = 0;
        }
        if(in_array(0, $stat)) die('<error>');
        else die('<success>');
    }
    else die('<error>');
} catch (\Throwable $t) {
    die('<error>');
}
?>
