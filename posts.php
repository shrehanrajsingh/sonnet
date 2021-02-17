<?php
include 'conn.php';
include 'desk.php';
if (!isset($_GET['__pid'])) die("No user set");
$pid = $_GET['__pid'];
$get_account_type = $conn->query("SELECT account_type FROM users WHERE id = $pid;");
$accType = "";
if ($get_account_type){
    if ($get_account_type->num_rows > 0){
        while($g = $get_account_type->fetch_assoc()){
            $accType = $g['account_type'];
        }
    }
}
if ($accType == "private"){
    // * In this case, check if user is friends with account
    $check_bond = $conn->query("SELECT id FROM friends WHERE friend_x = " . $config['id'] . " AND friend_y = $pid OR friend_x = " . $pid . " AND friend_y = " . $config['id'] . ";");
    if ($check_bond){
        if($check_bond->num_rows == 0){
            // * User is not friends with account
            die("Account is private and you aren't friends with the account, please be their friend to see their posts");
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Posts</title>
    <style>
        * {
            color: white;
        }

        body {
            margin: 2%;
            /* margin-top: 100px; */
            color: white;
        }

        a {
            color: white;
        }

        ::-webkit-scrollbar {
            visibility: hidden;
        }

        @media screen and (max-width: 768px) {}
    </style>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>

<body class="bg-light">
    <?php #include 'navbar.php'; ?>
    <?php
    $gp = $conn->query("SELECT * FROM posts WHERE owner_id = " . $pid . " ORDER BY upload_date DESC;");
    if ($gp) {
        if ($gp->num_rows > 0) {
            while ($r = $gp->fetch_assoc()) {
    ?>
                <div class="card alert alert-dark bg-dark shadow">
                    <div class="card-header" style="margin-bottom: 5%;">
                        <a href="./profile.php?profid=<?php echo $pid; ?>">
                            <img src="<?php echo get_profile_from_id($pid);?>" width="40" height="40" style="border-radius: 50%;border: 2px solid white" alt />&nbsp;
                            <?php echo get_single_query_from_id($pid, "uname", "users") ?>
                        </a>
                    </div>
                    <center>
                        <a href="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], 'email', 'users')) . '/posts/' . $r['filename']; ?>"><img style="border: 8px solid white; width: auto; height: auto; border-radius: 2%" src="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], 'email', 'users')) . '/posts/' . $r['filename']; ?>" alt></a>
                        <br><br>
                        <div>
                            <h5 style="font-weight: 300; max-height: 500px; overflow-y:scroll;margin-bottom: 5%"><?php echo $r['caption']; ?></h5>
                        </div>
                        <?php
                        if ($pid != $config['id']) {
                        ?>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger" onclick="__send_like_dis(<?php echo $r['id']; ?>, <?php echo $r['id']; ?>)" id="__tar<?php echo $r['id']; ?>">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-info" id="__count<?php echo $r['id']; ?>"><?php echo $conn->query("SELECT id FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows; ?></button>
                                <button type="button" class="btn btn-success" data-target="#__mod_comments_<?php echo $r['id'];?>" data-toggle="modal">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-left-dots-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-info"><?php echo $conn->query("SELECT id FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows; ?></button>
                            </div>
                            <div class="modal fade" id="__mod_comments_<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="color: black;">Post by <?php echo get_single_query_from_id($r['owner_id'], "uname", "users"); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: black;">
                                    <span style="color: black;" aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="max-height: 80vh;">
                                    <ul id="__modal_center_comment<?php echo $r['id']; ?>" style="list-style-type:none;padding:0">
                                    <?php
                                    try {
                                        $get_all_comments = $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . " ORDER BY comment_sent_on DESC;");
                                        if ($get_all_comments) {
                                        if ($get_all_comments->num_rows > 0) {
                                            while ($row = $get_all_comments->fetch_assoc()) {
                                    ?>
                                            <li class="border p-2 border-primary rounded text-left" style="color: black;">
                                                <a href="./search.php?input=@<?php echo get_single_query_from_id($row['author_id'], "uname", "users"); ?>">
                                                <img src="<?php echo get_profile_from_id($row['author_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" />
                                                </a>
                                                <?php echo $row['comment_context']; ?>
                                                <br>
                                                <form onsubmit="event.preventDefault();__like_comment(<?php echo $row['id']; ?>)">
                                                <button type="submit" class="btn btn-danger float-right" id="__comment_row_<?php echo $row['id']; ?>">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                                                    </svg>
                                                    <?php
                                                    if (in_array($config['id'], json_decode($row['comment_likes']))) echo "Liked";
                                                    ?></button>
                                                </form>
                                            </li>
                                            <br><br>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <h1 class="text-center" style="font-weight: 400;" id="__modal_center_comment<?php echo $r['id']; ?>_config__"><small>No Comments</small></h1>
                                    <?php
                                        }
                                        }
                                    } catch (\Throwable $t) {
                                        echo "Operation Cancelled by server. Error Code [5e-324]";
                                    }
                                    ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" class="input-group mb-3" onsubmit="event.preventDefault();__send_comment_request($('#__cid_te_form_txt<?php echo $r['id']; ?>').val(), <?php echo $r['id']; ?>, '__modal_center_comment<?php echo $r['id']; ?>', '<?php echo get_profile_from_id($r['owner_id']) ?>');">
                                    <input type="text" class="form-control" placeholder="Add a comment..." id="__cid_te_form_txt<?php echo $r['id']; ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-left-quote" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v11.586l2-2A2 2 0 0 1 4.414 11H14a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                        <path fill-rule="evenodd" d="M7.066 4.76A1.665 1.665 0 0 0 4 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112zm4 0A1.665 1.665 0 0 0 8 5.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 1 0 .6.58c1.486-1.54 1.293-3.214.682-4.112z" />
                                        </svg>
                                    </button>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>
                            
                        <?php
                        } else {
                        ?>
                            <p class="card-text">
                                <button data-toggle="modal" data-target="#__likes_own_post_<?php echo $r['id']; ?>" style="background: inherit; border:none;text-decoration: underline"><?php echo ($conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows > 1) ? $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows . " Likes" : $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows . " Like"; ?></button>
                                and
                                <button data-toggle="modal" data-target="#__comm_own_post_<?php echo $r['id']; ?>" style="background: inherit; border:none;text-decoration: underline"><?php echo ($conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows > 1) ? $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows . " Comments" : $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows . " Comment"; ?></button>
                            </p>
                            <div class="modal fade" id="__likes_own_post_<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                                <!-- 
                            // * Likes modal
                            -->
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color: black;">Likes</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul style="list-style-type:none;padding:0">
                                                <?php
                                                $get_likes_self_post = $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . " ORDER BY liked_at DESC;");
                                                if ($get_likes_self_post) {
                                                    if ($get_likes_self_post->num_rows > 0) {
                                                        while ($ar = $get_likes_self_post->fetch_assoc()) {
                                                ?>
                                                            <li class="border p-2 border-primary rounded text-left"><a style="text-decoration: none; color:black" href="./search.php?input=@<?php echo get_single_query_from_id($ar['liker_id'], "uname", "users"); ?>"><img src="<?php echo get_profile_from_id($ar['liker_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" /><?php echo get_single_query_from_id($ar['liker_id'], "uname", "users"); ?> on <?php echo date('j F, Y', strtotime($ar['liked_at'])); ?></a></li>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 
                            // * Comments modal
                        -->
                            <div class="modal fade" id="__comm_own_post_<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color: black;">Comments</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul style="list-style-type:none;padding:0">
                                                <?php
                                                $get_comments_self_post = $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . " ORDER BY comment_likes DESC;");
                                                if ($get_comments_self_post) {
                                                    if ($get_comments_self_post->num_rows > 0) {
                                                        while ($ar = $get_comments_self_post->fetch_assoc()) {
                                                ?>
                                                            <li class="border p-2 border-primary rounded text-left"><a style="text-decoration: none; color:black" href="./search.php?input=@<?php echo get_single_query_from_id($ar['author_id'], "uname", "users"); ?>"><img src="<?php echo get_profile_from_id($ar['author_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" /><?php echo $ar['comment_context']; ?></a> <i><sub><?php echo get_single_query_from_id($ar['author_id'], "uname", "users"); ?></sub></i></li>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <br><br>
                        <div class="card-footer" style="font-weight: 100;">
                            Uploaded on <?php echo date('j F, Y', strtotime($r['upload_date'])) ?>
                        </div>
                        <?php
                        ?>
                    </center>

                </div>
    <?php
            }
        }
        else
        {
            ?>
                <h1 class="display-2" style="color: black; position:absolute; top:50%; left: 50%; transform:translate(-50%, -50%); width:100%; text-align:center">User has no posts</h1>
            <?php
        }
    }
    ?>
    <button class="btn btn-primary rounded-circle" onclick="location.replace('./')" style="position: fixed; bottom: 25px; right: 25px;">
        <h1>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
                <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
            </svg>
        </h1>
    </button>
    <script>
        let __send_like_dis = (id, target) => {
            $.ajax({
                method: 'POST',
                url: './like_post.php',
                data: 'post_id=' + id,
                success: (data) => {
                    if (data == "disliked") {
                        $('#__count' + target).text(parseInt($('#__count' + target).text()) - 1);
                        document.getElementById('__tar' + target).innerHTML = document.getElementById('__tar' + target).innerHTML.substr(0, document.getElementById('__tar' + target).innerHTML.length - 5);
                    } else if (data == "liked") {
                        $('#__count' + target).text(parseInt($('#__count' + target).text()) + 1);
                        document.getElementById('__tar' + target).innerHTML += ' Liked';
                    } else;
                },
                error: (XMLHttpRequest, tStat, err) => {
                    alert("Some error occurred");
                }
            })
        };
        let __like_comment = (comment_id) => {
            event.preventDefault();
            if (typeof comment_id !== 'number') {
                snack_back("Unknown error, please try again later");
                return 0;
            }
            $.ajax({
                method: 'POST',
                url: './like_comment.php',
                data: 'comment_cred=' + comment_id,
                success: (data) => {
                switch (data) {
                    case '<invalid_input>':
                    snack_back("Unknown error, please try again later");
                    break;
                    case '<success>':
                    // $('#__comment_row_'+comment_id).html($('#__comment_row_'+comment_id).html()+"Liked");
                    if (document.getElementById(`__comment_row_${comment_id}`).innerHTML.substr(document.getElementById(`__comment_row_${comment_id}`).innerHTML.length - 5) != "Liked") document.getElementById(`__comment_row_${comment_id}`).innerHTML += "Liked";
                    else document.getElementById(`__comment_row_${comment_id}`).innerHTML = document.getElementById(`__comment_row_${comment_id}`).innerHTML.substr(0, document.getElementById(`__comment_row_${comment_id}`).innerHTML.length - 5);
                    break;
                    case '<query_error>':
                    snack_back("Database error, please try again later");
                    break;
                    default:
                    snack_back("Unknown error, please try again later");
                    break;
                }
                // console.log(data);
                }
            })
        };
    </script>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>