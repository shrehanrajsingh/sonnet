<?php
include 'conn.php';
include 'desk.php';
if (!isset($_POST['verify'])) die('<no_param_set>');
$gm = $conn->query("SELECT * FROM texts WHERE to_id = " . $config['id'] . " AND seen = false;");
$arr = [];
if ($gm) {
    if ($gm->num_rows > 0) {
        while ($r = $gm->fetch_assoc()) {
            $arr[] = $r;
        }
    }
}
echo json_encode($arr);
