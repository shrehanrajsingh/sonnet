<?php
include 'conn.php';
include 'desk.php';
if (!isset($_FILES['file'])) die("<no_params_set>");
$fname = $_FILES['file']['name'];
$tar = './public/client/groups/' . md5($_POST['gid']) . '/' . md5($fname . rand(1, 9999) . $_POST['cap']) . "." . pathinfo($fname, PATHINFO_EXTENSION);
$ftype = pathinfo($tar, PATHINFO_EXTENSION);
$res = 0;
/**
 * @param //* $res
 * * 0 -> nothing
 * * 1 -> image
 * * 2 -> video
 */
$validTypes = [
    // * Image
    [
        "jpg",
        "png",
        "jpeg"
    ],
    // * Video
    [
        "mp4",
        "m4a",
        "m4v",
        "f4v",
        "f4a",
        "m4b",
        "m4r",
        "f4b",
        "mov",
        "ogg",
        "oga",
        "ogv",
        "ogx"
    ]
];
if (in_array(strtolower($ftype), $validTypes[0])) { // ? Is it Image
    $res = 1;
} else if (in_array(strtolower($ftype), $validTypes[1])) { // ? Is it video
    $res = 2;
} else $res = 3; // * Other
$msg = "";
if ($res == 1) $msg = '<a href="' . $tar . '"><img src=' . $tar .' width=100 height=100 class=img-thumbnail alt/></a><br><br><p style=p-2>' . $_POST['cap'] . '</p>' ;
else if ($res == 2) {
    $ranid = rand();
    $msg = '
    <button type="button" class="btn btn-primary" onclick="location.replace(`' . $tar . '`)">
    Video
    </button>
';
}
else if ($res == 3) $msg = '<a href="' . $tar . '" class="alert-link">Unsupported file, click to download</a>';
// if (file_exists())
if (move_uploaded_file($_FILES['file']['tmp_name'], $tar)) {
    $gid = $_POST['gid'];
    $msg = str_replace("\"", "&quot;", $msg);
    // $msg = str_replace("<", "&lt;", $msg);
    // $msg = str_replace(">", "&gt;", $msg);
    $conf = json_encode(array(
        "from" => intval($config['id']),
        "to" => $_POST['gid'],
        "content" => $msg,
        "txt_sent_at" => $_SERVER['REQUEST_TIME'],
        "txt_likes" => 0,
        "txt_seen_by" => []
    ));
    $gm = $conn->query("SELECT group_texts FROM groups WHERE id = $gid;");
    $dat = [];
    if ($gm) while($r_ = $gm->fetch_assoc()) $dat = json_decode($r_['group_texts'], true);
    $dat[] = json_decode($conf, true);
    if ($conn->query("UPDATE groups SET group_texts = '" . json_encode($dat) . "' WHERE id = $gid;")) {
        die("<success>");
    }
    else{
        echo $conn->error;
        die("<error>");
    } // * Error here
}
else die("<error>");
