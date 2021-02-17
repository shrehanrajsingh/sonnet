<?php
include 'conn.php';
include 'desk.php';
if (!isset($_FILES['file'])) die("<no_params_set>");
$fname = $_FILES['file']['name'];
$tar = './public/client/users/' . md5($config['email']) . '/rawmsg/' . $fname;
$ftype = pathinfo($tar, PATHINFO_EXTENSION);
$user = $_POST['client'];
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
if ($res == 1) $msg = '<a href="' . $tar . '"><img src="' . $tar .'" width="100" height="100" class="img-thumbnail" alt/></a>' ;
else if ($res == 2) {
    $ranid = rand();
    $msg = '
    <button type="button" class="btn btn-primary" onclick="location.replace(`' . $tar . '`)">
    Video
    </button>
';
}
else if ($res == 3) $msg = '<a href="' . $tar . '" class="alert-link">Unsupported file, click to download</a>';
if (move_uploaded_file($_FILES['file']['tmp_name'], $tar)) {
    if ($conn->query("INSERT INTO texts (from_id, to_id, text_content, seen, liked) VALUES (" .  $config['id'] . ",$user,'$msg', false, false);")) {
        die("<success>");
    }
    else{
        echo $conn->error;
        die("<error>");
    } // * Error here
}
else die("<error>");
