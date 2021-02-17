<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

  <title>Activity</title>
</head>

<body class="bg-light">
  <?php
  include 'conn.php';
  include 'desk.php';
  include 'snackbar.php';
  include 'navbar.php';
  echo '<br><br><br><br>';
  if(isset($_POST['__accept_friend_request'])){
    $id = $_POST['__toReq__'];
    if(accept_friend_request($id)) alert("Friend request accepted");
    else alert("Unknown error, please try again later");
  }
  if (isset($_POST['__reject_friend_request'])){
    $id = $_POST['__toReq__'];
    $delreq = reject_friend_request($id);
    switch($delreq){
      case 1:
        alert("Friend request rejected");
      break;
      case 0:
        alert("Error rejecting request");
      break;
      default:
        alert("Unknown error occurred");
      break;
    }
  }
  if(isset($_POST['__do_mark'])){
    $id_to_mark = $_POST['__act_id__'];
    mark_activity($id_to_mark);
  }
  if(isset($_POST['__del_act'])){
    $id_to_del = $_POST['__act_id__'];
    delete_activity($id_to_del);
  }
  if(isset($_POST['__mark_all'])){
    mark_all_activities($config['id']);
  }
  $inc_act_get = 0;
  $inc_f_get = 0;
  $get_act = $conn->query("SELECT * FROM activity WHERE act_to_id = " . $config['id'] . " ORDER BY act_sent_on DESC;");
  $get_f_req = $conn->query("SELECT * FROM friend_requests WHERE req_to_id = " . $config['id'] . " ORDER BY req_sent_at ASC;");
  if($get_f_req){
    if($get_f_req->num_rows > 0){
      while($row = $get_f_req->fetch_assoc()){
        $inc_f_get++;
        ?>
        <div class="card border-<?php echo $inc_f_get % 2 == 0 ? "danger": "primary"?>">
          <div class="card-body">
            <h4 class="card-title"><?php echo date('j F, Y', strtotime($row['req_sent_at']));?></h4>
            <p class="card-text">
              <?php
                echo "\"" . $row['req_message'] . "\"<br>";
                echo "<a href='./search?input=@" . get_single_query_from_id($row['req_from_id'], 'uname', 'users') . "'>@" . get_single_query_from_id($row['req_from_id'], 'uname', 'users') . "</a> sent you a friend request";
                echo "
                <form method='post'>
                  <div class=\"btn-group\" role=\"group\">
                    <input type='hidden' value='" . $row['id'] . "' name='__toReq__' />
                    <button class='btn btn-outline-" . ($inc_f_get / 2 == 0 ? "danger":"primary") . "' name = '__accept_friend_request'>Accept</button>
                    <button class='btn btn-outline-" . ($inc_f_get / 2 == 0 ? "primary":"danger") . "' name = '__reject_friend_request'>Reject</button>
                  </div>
                </form>
                ";
              ?>
            </p>
          </div>
        </div>
        <?php
      }
    }
  }
  if ($get_act) {
    if ($get_act->num_rows > 0) {
      while($row = $get_act->fetch_assoc()){
        $inc_act_get++;
        ?>
        <div class="card border-<?php echo $inc_act_get % 2 == 0 ? "danger": "primary"?>">
          <div class="card-body">
            <h4 class="card-title"><?php echo date('j F, Y', strtotime($row['act_sent_on']));?></h4>
            <p class="card-text">
              <?php
                echo $row['act_context'];
                echo "
                <div class=\"btn-group\" role=\"group\">
                  <form method='post'>
                    <input type='hidden' value='" . $row['id'] . "' name='__act_id__' />
                    <button class='btn btn-outline-" . ($inc_act_get / 2 == 0 ? "danger":"primary") . "' name = '__do_mark'>Mark as " . ($row['marked_as_read'] == 0 ? 'read' : 'unread') . "</button>
                  </form>
                  <form method='post'>
                    <input type='hidden' value='" . $row['id'] . "' name='__act_id__' />
                    <button class='btn btn-outline-" . ($inc_act_get / 2 == 0 ? "primary":"danger") . "' name = '__del_act'>Delete Activity</button>
                  </form>
                </div>
                ";
              ?>
            </p>
          </div>
        </div>
        <?php
      }
    }
  }
  ?>

  <!-- Optional JavaScript -->
  <!-- Popper.js first, then Bootstrap JS -->
  <form method="POST">
    <button class="btn btn-primary" style="position: fixed; bottom: 1%; right: 1%; border-radius:50%" title="Mark all as read" name="__mark_all">
    <h1><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
      <path d="M6.25 8.043l-.896-.897a.5.5 0 1 0-.708.708l.897.896.707-.707zm1 2.414l.896.897a.5.5 0 0 0 .708 0l7-7a.5.5 0 0 0-.708-.708L8.5 10.293l-.543-.543-.707.707z"/>
    </svg></h1>
    </button>
  </form>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>