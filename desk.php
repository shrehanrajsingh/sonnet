<?php
include('./conn.php');

if (isset($_COOKIE['__sonnet_user_credits'])) $config = json_decode($_COOKIE['__sonnet_user_credits'], true);
?>
<?php
function alert($msg)
{
    echo "<script>snack_back('" . $msg . "');</script>";
}

function setInterval($f, $milliseconds)
{
    $seconds=(int)$milliseconds/1000;
    while(true)
    {
        $f();
        sleep($seconds);
    }
}

function get_profile_from_id($id){
    return './public/client/users/' . md5(get_single_query_from_id($id, 'email', 'users')) . '/profile/' . get_single_query_from_id($id, 'profile_name', 'users');
}

function create_new_user($cred, $prof_file)
{
    global $conn;
    if ($conn->query("SELECT * FROM users WHERE email='" . $cred['email'] . "' OR uname = '" . $cred['uname'] . "';")->num_rows > 0) {
        alert("User with specified email already exists");
    } else {
        if ($conn->query("INSERT INTO users (dispname, uname, email, password, status, profile_name, is_verified, account_type) VALUES ('" . $cred['dispname'] . "', '" . $cred['uname'] . "', '" . $cred['email'] . "', '" . md5($cred['pass']) . "', '" . $cred['user_status'] . "','" . $prof_file["name"] . "',false, '" . $cred['account_type'] . "')")) {
            // mail($cred['email'], "Sonnet Company", "
            //     <div class='shadow mb-5 p-3 rounded bg-white'>
            //         <h1 class='display-3'>Account successfully created!!</h1>
            //         <p>Join the fun in Sonnet, create your profile today!</p>
            //     </div>
            // ");
            if (upload_profile_pic($prof_file, $cred['email'])) alert("Account created");
            else alert("Server error, please try again later");
            // header('location:./login');
        } else {
            alert("Couldn't create account, please try again later");
        }
    }
}

function send_friend_request($cred, $msg = "User wants to be your friend")
{
    global $conn;
    global $config;
    // * Check is request exists
    /* 
    * RETURN TYPE
    * 0 -> Error
    * 1 -> Friend request sent
    * 2 -> Request already exists
    * 3 -> You and user are now friends
    * 4 -> Already friends
    */
    $m = str_replace('\'', "\\'", $msg);
    $m = str_replace("\"", '\\"', $m);
    try {
        $check_friend_request = $conn->query("SELECT id FROM friend_requests WHERE req_from_id = '" . $config['id'] . "' OR req_to_id = '" . $config['id'] . "' LIMIT 1");
        $check_already_friends = $conn->query("SELECT id FROM friends WHERE friend_x = " . $cred . " AND friend_y = " . $config['id'] . " OR friend_x = " . $config['id'] . " AND friend_y = " . $cred . ";");
        if ($check_already_friends->num_rows != 0) return 4; // * 4 -> Already friends
        if ($check_friend_request->num_rows != 0) return 2; //* 2 -> Friend request exists
        $get_account_type = $conn->query("SELECT account_type FROM users WHERE id = " . $cred . ";");
        $get_account_type = $get_account_type->fetch_assoc();
        // while($r = $get_account_type->fetch_assoc()) print_r($r);
        // print_r($get_account_type);
        if ($get_account_type['account_type'] == 'private') {
            // * Add friend request to database
            $add_friend_request = $conn->query("INSERT INTO friend_requests (req_from_id, req_to_id, req_message, req_accepted) VALUES (" . $config['id'] . ", " . $cred . ", '" . $m . "', false)");
            if ($add_friend_request) {
                create_activity('<friend_request_sent>', $cred);
                return 1;
            } // * 1 -> Friend request sent
            else return 0; // * 0 -> Error
        } else if ($get_account_type['account_type'] == "public") {
            // * Directly add follower
            $add_follower = $conn->query("INSERT INTO friends (friend_x, friend_y) VALUES (" . $config['id'] . ", " . $cred . ")");
            if ($add_follower) {
                create_activity('<new_friend>', $cred);
                return 3;
            } //* 3 -> You and user are now friends
            else return 0;
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function get_single_query_from_id($id, $col, $table)
{
    global $conn;
    return $conn->query("SELECT $col FROM $table WHERE id = " . $id . " LIMIT 1;")->fetch_assoc()[$col];
}
function create_activity($context, $extend)
{
    global $conn;
    global $config;
    switch ($context) {
        case '<friend_request_sent>':
            $g = get_single_query_from_id($extend, 'dispname', 'users');
            $conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $config['id'] . ", 'You sent a friend request to $g', false)");
            break;
        case '<new_friend>':
            $g = get_single_query_from_id($extend, 'dispname', 'users');
            $conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $config['id'] . ", 'You and $g are now friends', false)");
            $conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $extend . ", 'You and " . $config['dispname'] . " are now friends', false)");
            break;
        default:
            break;
    }
}

function accept_friend_request($id)
{
    global $conn;
    global $config;
    $acc_req = $conn->query("SELECT * FROM friend_requests WHERE id = $id LIMIT 1;");
    if ($acc_req) {
        if ($acc_req->num_rows > 0) {
            while ($r = $acc_req->fetch_assoc()) {
                $extend = $r['req_from_id'];
                $finalQuery = $conn->query("INSERT INTO friends (friend_x, friend_y) VALUES (" . $r['req_from_id'] . ", " . $r['req_to_id'] . ")");
                if ($finalQuery) {
                    if ($conn->query("DELETE FROM friend_requests WHERE id = $id")) {
                        if ($conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $config['id'] . ", 'You and " . get_single_query_from_id($extend, 'uname', 'users') . " are now friends', false)") && $conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $extend . ", 'You and " . $config['dispname'] . " are now friends', false)")) return 1;
                    } else return 0;
                } else return 0;
            }
        } else return 0;
    }
}

function reject_friend_request($id){
    global $conn;
    global $config;
    try{
        $del = $conn->query("DELETE FROM friend_requests WHERE id = $id");
        if ($del) return 1;
        else return 0;
    }
    catch(\Throwable $t){
        return 0;
    }
}

function upload_post($cred)
{
    global $conn;
    global $config;
    $tar_dir = "./public/client/users/" . md5($cred["email"]) . "/posts/";
    $tar_file = $tar_dir . basename($cred["file"]["name"]);
    if (getimagesize($cred["file"]["tmp_name"]) == 0) return 0;
    if (move_uploaded_file($cred["file"]["tmp_name"], $tar_file)) {
        if ($conn->query("INSERT INTO posts (owner_id, filename, caption) VALUES (" . $cred['uid'] . ", '" . $cred['file']['name'] . "', '" . $cred['caption'] . "')")) {
            // * Send notification (activity) to their friends/followers
            $getall = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";");
            if($getall){
                if($getall->num_rows > 0){
                    while($j = $getall->fetch_assoc()){
                        $friend_ = $config['id'] == $j['friend_x'] ? $j['friend_y'] : $j['friend_x'];
                        if($conn->query("INSERT INTO activity (act_to_id, act_context, marked_as_read) VALUES (" . $friend_ . ", \"Check out " . $config['uname'] . "\'s new post!\", 0)")) return 1;
                    }
                }
            }
        }
    }
}

function handle_like($id){
    global $conn;
    global $config;
    // * Check if user has already liked the post or not
    $check_like = $conn->query("SELECT * FROM post_likes WHERE post_id = $id AND liker_id = " . $config['id'] . ";");
    if($check_like){
        if($check_like->num_rows == 0){
            // * LIKE
            if($conn->query("INSERT INTO post_likes (post_id, liker_id) VALUES ($id, " . $config['id'] . ")")){
                return "liked";
            } else return 0;
        }
        else
        {
            // * DISLIKE
            if($conn->query("DELETE FROM post_likes WHERE post_id = $id AND liker_id = " . $config['id'])){
                return "disliked";
            } else return 0;
        }
    }
}

function login_user($cred)
{
    global $conn;
    if ($conn->query("SELECT * FROM users WHERE email='" . $cred["email"] . "' AND password='" . md5($cred["pass"]) . "';")->num_rows == 0) {
        alert("Credentials error or account does not exist");
    } else {
        while ($row = $conn->query("SELECT * FROM users WHERE email='" . $cred["email"] . "' AND password='" . md5($cred["pass"]) . "';")->fetch_assoc()) {
            setcookie("__sonnet_user_credits", json_encode($row));
            header('location:./');
            break;
        }
    }
}

function upload_profile_pic($fvar, $td_complete)
{
    mkdir('./public/client/users/' . md5($td_complete) . '/');
    mkdir('./public/client/users/' . md5($td_complete) . '/profile/');
    mkdir('./public/client/users/' . md5($td_complete) . '/posts/');
    mkdir('./public/client/users/' . md5($td_complete) . '/rawmsg/');
    $tar_dir = "./public/client/users/" . md5($td_complete) . "/profile/";
    $tar_file = $tar_dir . basename($fvar["name"]);
    if (getimagesize($fvar["tmp_name"]) == false) {
        return 0;
    }
    if (move_uploaded_file($fvar["tmp_name"], $tar_file)) {
        return 1;
    }
}

function mark_activity($id)
{
    global $conn;
    try {
        if ($conn->query("SELECT id FROM activity WHERE id = $id AND marked_as_read = false;")->num_rows != 0) {
            $conn->query("UPDATE activity SET marked_as_read = true WHERE id = $id;");
        } else {
            if ($conn->query("SELECT id FROM activity WHERE id = $id AND marked_as_read = true;")->num_rows != 0)
                $conn->query("UPDATE activity SET marked_as_read = false WHERE id = $id;");
        }
    } catch (\Throwable $t) {
        throw $t; //! ONLY FOR DEVELOPMENT
    } finally {
        return 1;
    }
}
function delete_activity($id)
{
    global $conn;
    try {
        $conn->query("DELETE FROM activity WHERE id = " . $id . ";");
    } catch (\Throwable $t) {
        throw $t; //! ONLY FOR DEVELOPMENT
    } finally {
        return 1;
    }
}

function mark_all_activities($id){
    global $conn;
    try{
        $conn->query("UPDATE activity SET marked_as_read = true WHERE act_to_id = $id;");
    }
    catch(\Throwable $t){
        throw $t; //! ONLY FOR DEVELOPMENT
    }
    finally{
        return 1;
    }
}

if (isset($_POST['__submit_signup'])) {
    str_replace("<", "&lt;", $_POST['__user_name']);
    str_replace(">", "&gt;", $_POST['__user_name']);
    str_replace("'", "&apos;", $_POST['__user_name']);
    str_replace("\"", "&quot;", $_POST['__user_name']);
    str_replace("&", "&amp;", $_POST['__user_name']);

    str_replace("<", "&lt;", $_POST['__user_email']);
    str_replace(">", "&gt;", $_POST['__user_email']);
    str_replace("'", "&apos;", $_POST['__user_email']);
    str_replace("\"", "&quot;", $_POST['__user_email']);
    str_replace("&", "&amp;", $_POST['__user_email']);

    str_replace("<", "&lt;", $_POST['__user_pass']);
    str_replace(">", "&gt;", $_POST['__user_pass']);
    str_replace("'", "&apos;", $_POST['__user_pass']);
    str_replace("\"", "&quot;", $_POST['__user_pass']);
    str_replace("&", "&amp;", $_POST['__user_pass']);

    str_replace("<", "&lt;", $_POST['__user_status']);
    str_replace(">", "&gt;", $_POST['__user_status']);
    str_replace("'", "&apos;", $_POST['__user_status']);
    str_replace("\"", "&quot;", $_POST['__user_status']);
    str_replace("&", "&amp;", $_POST['__user_status']);
    $atype = $_POST['__user_acc_type'];
    if ($atype !== "public" && $atype !== "private") $atype = "public";
    $credits = array(
        "uname" => $_POST['__user_name'],
        "email" => $_POST['__user_email'],
        "pass" => $_POST['__user_pass'],
        "dispname" => $_POST['__display_name'],
        "user_status" => $_POST['__user_status'],
        "account_type" => $atype
    );
    create_new_user($credits, $_FILES['__user_profile']);
}
if (isset($_POST['__submit_login'])) {
    $credits = array(
        "email" => $_POST['__user_email'],
        "pass" => $_POST['__user_pass']
    );
    login_user($credits);
}

?>