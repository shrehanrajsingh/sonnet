<?php
include 'desk.php';
if (!isset($_COOKIE['__sonnet_user_credits'])) header('location:./login');
if (isset($_POST['__upload_btn_sub'])) {
  $credits = array(
    "uid" => $config['id'],
    "email" => $config['email'],
    "file" => $_FILES['__uploadFile'],
    "caption" => $_POST['__upload_post_caption']
  );
  upload_post($credits);
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

  <title>Dashboard</title>
  <style>
    body {
      overflow-x: hidden;
      margin-bottom: 120px;
    }
  </style>
  <link rel="stylesheet" href="./styles.css">
</head>

<body class="bg-light">
  <?php
  include 'snackbar.php';
  if (isset($_POST["__logout"])) {
    setcookie("__sonnet_user_credits", "", time() - 3600);
    header("location:./login");
  }
  ?>
  <br><br><br><br>
  <nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top" style="background-color: #3485fd;">
    <div class="container-fluid">
      <a class="navbar-brand" href="./" title="<?php echo $config['uname']; ?>"><img src="<?php echo 'public/client/users/' . md5($config["email"]) . '/profile/' . $config['profile_name']; ?>" width="50" height="50" style="border-radius:50%" alt></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="javascript:void(0)">Home</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)">Sonnet TV</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="./activity.php">Activity</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
              Tools
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#__upload_schema">Upload</a></li>
              <li><a class="dropdown-item" href="./direct.php">Direct
                  <?php
                  global $conn;
                  global $config;
                  if ($conn->query("SELECT * FROM texts WHERE to_id = " . $config['id'] . " AND seen = false;")->num_rows > 0) echo "
                <span class=\"badge bg-secondary\">" . $conn->query("SELECT * FROM texts WHERE to_id = " . $config['id'] . " AND seen = false;")->num_rows . "</span>
                ";
                  ?>
                </a></li>
              <li>
              <li><a class="dropdown-item" href="./group.php">Groups</a></li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="javascript:void(0)">API</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <form class="d-flex" action="/search" method="GET">
              <input class="form-control" list="__search_global" id="__search_global_input" name="input" placeholder="@account, #trend...." style="border-top-right-radius: 0%; border-bottom-right-radius:0%;margin-right:8px">
              <button class="btn btn-outline-light" type="submit" style="border-top-left-radius: 0%; border-bottom-left-radius:0%">Search</button>
            </form>
          </li>
        </ul>
        <form method="post" class="d-flex">
          <button type="submit" class="btn btn-danger" name="__logout">Logout</button>
        </form>
      </div>
    </div>
  </nav>
  <!-- <div class="card bg-light mb-3 shadow" style="max-width: 18rem;margin-left:20px;">
    <div class="card-header text-center"><?php echo $config['dispname']; ?></div>
    <div class="card-body">
      <h5 class="card-title"><img src="<?php echo 'public/client/users/' . md5($config["email"]) . '/profile/' . $config['profile_name']; ?>" class="img-thumbnail" alt/></h5>
      <p class="card-text">
        <button class="btn btn-info btn-block">Start a live video</button>
      </p>
    </div>
  </div> -->
  <!-- STORIES SECTION TO BE INSERTED HERE -->
  <!-- <div>

    </div> -->
  <div id="__upload_schema" class="modal fade" tabindex="-1" aria-labelledby="Upload Modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="Upload Modal">New Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data" id="__upload_form">
            <div class="form-file form-file-lg mb-3">
              <input type="file" class="form-file-input" required id="customFileLg" name="__uploadFile" onchange="$('#__curr_up_file_name').text(this.files[0].name.substr(0, this.files[0].name.lastIndexOf('.')))">
              <label class="form-file-label" for="customFileLg">
                <span class="form-file-text" id="__curr_up_file_name">Choose file...</span>
                <span class="form-file-button">Browse</span>
              </label>
            </div>
            <div class="form-group">
              <label for="__upload_post_caption">Caption</label>
              <textarea class="form-control" name="__upload_post_caption" id="__upload_post_caption" rows="3"></textarea>
            </div>
            <br>
            <div class="progress" id="__upload_p_id" style="display: none;">
              <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 99%;" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100">Uploading...</div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="__upload_btn_sub" class="btn btn-primary" onclick="$('#__upload_p_id').css('display', 'block');">Upload</button>
        </div>

        </form>
      </div>
    </div>
  </div>
  <span style="display:block; margin-bottom:1%;"></span>
  <!-- POSTS SECTION -->
  <div>
    <?php
    global $conn;
    $get_friends = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";");
    if ($get_friends) {
      if ($get_friends->num_rows > 0) {
        while ($row = $get_friends->fetch_assoc()) {
          $a = "";
          if ($config['id'] == $row['friend_x']) $a = $row['friend_y'];
          else $a = $row['friend_x'];
          $get_posts = $conn->query("SELECT * FROM posts WHERE owner_id = " . $a . " ORDER BY upload_date DESC;");
          if ($get_posts) {
            if ($get_posts->num_rows > 0) {
              while ($r = $get_posts->fetch_assoc()) {
    ?>
                <div class="card text-left resp shadow">
                  <div class="card-header">
                    <a href="./profile.php?profid=<?php echo $r['owner_id']?>"><img width="40" height="40" style="border-radius: 50%;" alt src="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], "email", "users")) . '/profile/' . get_single_query_from_id($r['owner_id'], "profile_name", "users"); ?>" /></a>
                    <h1 class="display-4" onclick="location.href='./profile.php?profid=<?php echo $r['owner_id'];?>'" style="font-size: 20px;display:inline;margin-left:2%;font-weight:500;"><?php echo get_single_query_from_id($r['owner_id'], "uname", "users"); ?></h1>
                  </div>
                  <div class="card-body">
                    <img class="card-img-top" style="max-height: 500px;max-width:500px" src="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], "email", "users")) . '/posts/' . $r['filename']; ?>" alt="" />
                    <br><br>
                    <h5 class="card-title"><?php echo date('j F, Y', strtotime($r['upload_date'])); ?></h5>
                    <p class="card-text"><?php echo $r['caption']; ?></p>
                    <div class="btn-group" role="group">
                      <?php
                      if ($conn->query("SELECT id FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows != 0) {
                        // ? LIKES
                      ?>
                        <form method="POST" onsubmit="event.preventDefault();__send_like_dis($('#__pid<?php echo $r['id']; ?>').val(), <?php echo $r['id']; ?>)">
                          <input type="hidden" id="__pid<?php echo $r['id']; ?>" value="<?php echo $r['id']; ?>" />
                          <button type="submit" id="__tar<?php echo $r['id']; ?>" name="__handle_like" class="btn btn-danger">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                            </svg> Liked</button>
                        </form>
                      <?php
                      } else {
                        // ? DISLIKES
                      ?>
                        <form method="POST" onsubmit="event.preventDefault();__send_like_dis($('#__pid<?php echo $r['id']; ?>').val(), <?php echo $r['id']; ?>)">
                          <input type="hidden" id="__pid<?php echo $r['id']; ?>" value="<?php echo $r['id']; ?>" />
                          <button type="submit" id="__tar<?php echo $r['id']; ?>" name="__handle_like" class="btn btn-outline-danger">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" />
                            </svg>
                          </button>
                        </form>
                      <?php
                      }
                      ?>
                      <button class="btn btn-outline-primary disabled" id="__count<?php echo $r['id']; ?>"><?php echo $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows; ?></button>
                      <button class="btn btn-success" data-target="#__mod_comments_<?php echo $r['id']; ?>" data-toggle="modal">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-left-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        </svg>
                      </button>
                      <button class="btn btn-outline-success disabled" aria-disabled="true" disabled><?php echo $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows; ?></button>
                    </div>
                  </div>
                </div>
                <div class="modal fade" id="__mod_comments_<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Post by <?php echo get_single_query_from_id($r['owner_id'], "uname", "users"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
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
                                  <li class="border p-2 border-primary rounded">
                                    <a href="./search?input=@<?php echo get_single_query_from_id($row['author_id'], "uname", "users"); ?>">
                                      <img src="<?php echo get_profile_from_id($row['author_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" />
                                    </a>
                                    <?php echo $row['comment_context']; ?>
                                    <br>
                                    <form onsubmit="__like_comment(<?php echo $row['id']; ?>)">
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
              }
            }
          }
        }
      }
    }
    ?>
    <!-- GET YOUR OWN POSTS -->
    <?php
    $get_own_posts = $conn->query("SELECT * FROM posts WHERE owner_id = " . $config['id'] . ";");
    if ($get_own_posts) {
      if ($get_own_posts->num_rows > 0) {
        while ($r = $get_own_posts->fetch_assoc()) {
    ?>
          <div class="card text-left resp shadow">
            <div class="card-header">
              <a href="./profile.php?profid=<?php echo $config['id']; ?>"><img width="40" height="40" style="border-radius: 50%;" alt src="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], "email", "users")) . '/profile/' . get_single_query_from_id($r['owner_id'], "profile_name", "users"); ?>" /></a>
              <h1 class="display-4" onclick="location.href='./profile.php?profid=<?php echo $config['id']; ?>'" style="font-size: 20px;display:inline;margin-left:2%;font-weight:500;"><?php echo get_single_query_from_id($r['owner_id'], "uname", "users"); ?> (you)</h1>
            </div>
            <div class="card-body">
              <img class="card-img-top" style="max-height: 500px;max-width:500px" src="<?php echo './public/client/users/' . md5(get_single_query_from_id($r['owner_id'], "email", "users")) . '/posts/' . $r['filename']; ?>" alt="" />
              <br><br>
              <h5 class="card-title"><?php echo date('j F, Y', strtotime($r['upload_date'])); ?></h5>
              <p class="card-text">
                <?php echo $r['caption']; ?>
                <br><br>
                <button data-toggle="modal" data-target="#__likes_own_post_<?php echo $r['id']; ?>" style="background: inherit; border:none;color:blue"><?php echo ($conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows > 1) ? $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows . " Likes" : $conn->query("SELECT * FROM post_likes WHERE post_id = " . $r['id'] . ";")->num_rows . " Like"; ?></button>
                and
                <button data-toggle="modal" data-target="#__comm_own_post_<?php echo $r['id']; ?>" style="background: inherit; border:none;color:red"><?php echo ($conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows > 1) ? $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows . " Comments" : $conn->query("SELECT * FROM post_comments WHERE post_id = " . $r['id'] . ";")->num_rows . " Comment"; ?></button>
              </p>
              <div class="btn-group" role="group">
                <button class="btn btn-outline-danger" onclick="__send_delete_post_request(<?php echo $r['id']; ?>)">Delete post</button>
              </div>
            </div>
          </div>
          <div class="modal fade" id="__likes_own_post_<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
            <!-- 
              // * Likes modal
             -->
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Likes</h5>
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
                          <li class="border p-2 border-primary rounded"><a style="text-decoration: none; color:black" href="./search?input=@<?php echo get_single_query_from_id($ar['liker_id'], "uname", "users"); ?>"><img src="<?php echo get_profile_from_id($ar['liker_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" /><?php echo get_single_query_from_id($ar['liker_id'], "uname", "users"); ?> on <?php echo date('j F, Y', strtotime($ar['liked_at'])); ?></a></li>
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
                  <h5 class="modal-title">Comments</h5>
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
                          <li class="border p-2 border-primary rounded"><a style="text-decoration: none; color:black" href="./search?input=@<?php echo get_single_query_from_id($ar['author_id'], "uname", "users"); ?>"><img src="<?php echo get_profile_from_id($ar['author_id']); ?>" width="30" height="30" style="border-radius: 50%; margin-right:5px" /><?php echo $ar['comment_context']; ?></a> <i><sub><?php echo get_single_query_from_id($ar['author_id'], "uname", "users"); ?></sub></i></li>
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
      }
    }
    ?>
  </div>
  <!-- FLOATING BUTTON -->
  <button title="Your Activity" class="btn btn-primary <?php
                                                        global $conn;
                                                        if (($conn->query("SELECT * FROM activity WHERE act_to_id = " . $config['id'] . " AND marked_as_read = 0")->num_rows > 0) || ($conn->query("SELECT * FROM friend_requests WHERE req_to_id = " . $config['id'] . " AND req_accepted = false;")->num_rows > 0)) echo "pulse btn-info";
                                                        else echo "";
                                                        ?>" style="position:fixed;bottom:1%; right:1%;border-radius:50%;z-index:20000000000" onclick="location.replace('./activity.php')">
    <h1><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-mailbox" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M4 4a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3zm0-1h8a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4zm2.646 1A3.99 3.99 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3H6.646z" />
        <path fill-rule="evenodd" d="M11.793 8.5H9v-1h5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.354-.146l-.853-.854z" />
        <path d="M5 7c0 .552-.448 0-1 0s-1 .552-1 0a1 1 0 0 1 2 0z" />
      </svg></h1>
  </button>
  <script>
    var __upload_schema = document.getElementById('__upload_schema')
    __upload_schema.addEventListener('show.bs.modal', function(event) {
      // Button that triggered the modal
      var button = event.relatedTarget
      // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here
      // and then do the updating in a callback.
      //
      // Update the modal's content.
      var modalTitle = __upload_schema.querySelector('.modal-title')
      var modalBodyInput = __upload_schema.querySelector('.modal-body input')
    })
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
          snack_back("Some error occurred");
        }
      })
    }
    let __send_delete_post_request = (id) => {
      $.ajax({
        method: 'POST',
        url: './delete_post.php',
        data: 'post_id=' + id,
        success: (data) => {
          // if(data == "success"){
          //   snack_back("Post deleted successfully");
          // } else {
          //   snack_back("Error deleting post");
          // }
          switch (data) {
            case "<success>":
              snack_back("Post successfully deleted");
              break;
            case "<query_not_deleted>":
              snack_back("Error deleting post");
              break;
            case "<file_not_deleted>":
              snack_back("Error deleting post");
              break;
            case "<column_not_found>":
              snack_back("Error deleting post");
              break;
            case "<query_error>":
              snack_back("Error deleting post");
              break;
            default:
              snack_back("Unknown error");
              break;
          }
        },
        error: (XMLHttpRequest, tStat, err) => {
          snack_back("Some error occurres");
        }
      })
    };
    let __send_comment_request = (txt, postId, res_target) => {
      $.ajax({
        method: 'POST',
        url: './add_comment.php',
        data: 'input_text=' + txt + "&__pid=" + postId,
        success: (data) => {
          switch (data) {
            case '<error_no_params>':
              snack_back("Unknown error, please try again later");
              break;
            case '<server_error>':
              snack_back("Server error, please try again later");
              break;
            case '<comment_added>':
              snack_back("Comment successfully added");
              try {
                document.getElementById(res_target + '_config__').innerHTML = '';
              } catch (e) {};
              document.getElementById(res_target).innerHTML += `
              <li class="border p-2 border-primary rounded">${txt}</li>
              `;
              break;
            default:
              snack_back("Error handling request, please try again later");
              console.log(data);
              break;
          }
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
    let g_act = [];
    let __get_notification = () => {
      $.ajax({
        method: 'POST',
        url: './get_notification.php',
        data: 'nid=' + Date.now().toString(),
        success: (data) => {
          let el;
          try {
            for (x in data) {
              if (data.charAt(x) == "<" || data.charAt(x) == ">") return 0;
            }
            el = JSON.parse(data);
            if (el != g_act && el.length != g_act.length) {
              g_act = el;
              __n_callback();
            } else return 0;
          } catch (e) {
            return 0;
          }
        }
      })
    };
    let __n_callback = () => {
      if (g_act.length > 1) {
        snack_back(`You have ${g_act.length} unread activity notifications`);
      } else if (g_act.length == 1) {
        snack_back(g_act[0].act_context);
      }
    }
    let g_msg = [];
    let __get_messages = () => {
      $.ajax({
        method: 'POST',
        url: './get_direct_notifications.php',
        data: 'verify=1',
        success: (data) => {
          let el;
          try {
            el = JSON.parse(data);
            if (el != g_msg && el.length != g_msg.length) {
              g_msg = el;
              __m_callback();
            } else return 0;
          } catch (e) {
            return 0;
          }
        }
      })
    };
    let __m_callback = () => {
      if (g_msg.length > 1) {
        snack_back(`You have ${g_msg.length} unread messages`);
      } else if (g_msg.length == 1) {
        snack_back(`You have an unread message`);
      }
    }
    let gg_msg = -1;
    let __get_group_messages = () => {
      $.ajax({
        method: 'POST',
        url: './get_group_notifications.php',
        data: 'verify=1',
        success: (data) => {
          if(gg_msg != data && gg_msg != 0){
            if (data != 0) gg_msg = data;
            else return 0;
            __o_callback();
          }
        }
      })
    }
    let __o_callback = () => {
      snack_back("You have unread group messages");
      gg_msg = 0;
    }
    setInterval(_ => __get_notification(), 1000);
    setInterval(_ => __get_messages(), 1000);
    setInterval(_ => __get_group_messages(), 1000);
    // setTimeout(()=>{
    // }, 1000)
  </script>
  <br><br><br>
  <?php
  // include 'footer.php';
  ?>
  <!-- <br>
  <br><br><br>
  <br>
  <br><br><br><br><br><br>
  <br><br><br> -->
  <!-- Optional JavaScript -->
  <!-- Popper.js first, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>