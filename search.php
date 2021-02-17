<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <title>Search Sonnet</title>
    <style>
        .hover-scale{
            transition: 0.4s ease;
        }
        .hover-scale:hover{
            transform:scale(1.005);
        }
    </style>
  </head>
  <body>
    <?php
        include 'navbar.php';
        include 'breadcrumb.php';
    ?>
    <br>
    <?php
        include 'desk.php';
        include 'conn.php';
        include 'snackbar.php';
        if(isset($_POST['__sendRequest'])){
            $callback = send_friend_request($_POST['__requestId'], isset($_POST['__friend_caption']) ? $_POST['__friend_caption'] : "User wants to be your friend");
            switch($callback){
                case 0:
                    alert("Server error, please try again later");
                break;
                case 1:
                    alert("Friend request sent");
                    // try{header('location:./activity.php');}catch(\Throwable $t){};
                break;
                case 2:
                    alert("Request already exists");
                break;
                case 3:
                    alert("You and user are now friends");
                break;
                case 4:
                    alert("You and user are already friends");
                break;
                default:
                    alert("Error");
                break;
            }
        }
        if (isset($_GET['user'])) {
            // It's a specific user
        }
        if (isset($_GET['input']) and substr($_GET['input'], 0, 1) == "@") {
            // It's a global search of users
            try {
                $doContinue = array();
                $__c = 0;
                $check_al = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";");
                    if($check_al){
                        if($check_al->num_rows > 0){
                            while($r1 = $check_al->fetch_assoc()){
                                $r2 = "";
                                if($r1['friend_x'] == $config['id']) $r2 = $r1['friend_y'];
                                else $r2 = $r1['friend_x'];
                                $doContinue[] = $r2;
                            }
                        }
                    } else;
                $ren = $conn->query("SELECT id, uname, dispname, email, profile_name, status, reg_date, is_verified, account_type FROM users WHERE uname LIKE '%" . substr($_GET['input'], 1, strlen($_GET['input'])) . "%' AND id <> " . $config['id'] . ";");
                if($ren->num_rows == 0)echo "<h1 class='display-4 text-center'>No Users Found</h1>";
                while($row = $ren->fetch_assoc()){
                    $isFriend = 0;
                    $isSpec = 0;
                    if($row['id'] == $config['id']) $isSpec = 1;
                    if(in_array($row['id'], $doContinue)) $isSpec = 1;
                    // while($r1 = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";")->fetch_assoc()){
                    //     $r2 = "";
                    //     if($r1['friend_x'] == $config['id']) $r2 = $r1['friend_y'];
                    //     else $r2 = $r1['friend_x'];
                    //     if($row['id'] == $r2) $doContinue = true;
                    // }
                    // if($doContinue) continue;
                    if($conn->query("SELECT id FROM friend_requests WHERE req_from_id = " . $config['id'] . " AND req_to_id = " . $row['id'] . ";")->num_rows != 0) $isFriend = 1;
                ?>
                <div class="alert">
                <div class="card mb-3 shadow-sm" style="max-width: 100%;">
                    <div class="row g-0">
                        <div class="col-sm-4">
                        <img src="<?php echo 'public/client/users/' . md5($row["email"]) . '/profile/' . $row['profile_name'];?>" style="width:100%; height:100%;"  alt>
                        </div>
                        <div class="col-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php 
                                    echo $row['dispname'];
                                    if($row['is_verified'] == 1){
                                        ?>
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                <path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
                                            </svg>
                                        <?php
                                    }
                                ?>
                            </h5>
                            <p class="card-text"><?php echo $row['status'];?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Joined <?php echo date('j F, Y',strtotime($row['reg_date']));?>
                                    <br>
                                    <?php
                                    if($row["account_type"] == "private"){
                                        if($isFriend ==  0){
                                            if ($conn->query("SELECT id FROM friends WHERE friend_x = " . $config['id'] . " AND friend_y = " . $row['id'] . ";")->num_rows > 0 || $conn->query("SELECT id FROM friends WHERE friend_y = " . $config['id'] . " AND friend_x = " . $row['id'] . ";")->num_rows > 0){
                                                ?>
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-primary" onclick="location.href = './profile.php?profid=<?php echo $row['id'];?>';">Go to profile</button>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {
                                    ?>
                                        <form method="post">
                                            <input type="hidden" name="__requestId" value="<?php echo $row['id'];?>" />
                                            <div class="input-group mb-3">
                                                <input type="text" name="__friend_caption" class="form-control" placeholder="Add a message">
                                                <button class="btn btn-primary" name="__sendRequest">
                                                Send Request
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M11 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM1.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zm9.974.056v-.002.002zM6 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm4.5 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                                    <path fill-rule="evenodd" d="M13 7.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                                                </svg>
                                                </button>
                                            </div>
                                        </form>
                                    <?php
                                        }} else {
                                            // else
                                            // {
                                            ?>
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-primary btn-disabled" aria-disabled="true" disabled>Pending...</button>
                                                </div>
                                            <?php
                                            // }
                                        }
                                    }
                                    else if ($row["account_type"] == "public"){
                                        // if (!$conn->query("SELECT id FROM friends WHERE friend_x = " . $row['id'] . " AND friend_y = " . $config['id'] . " OR friend_x = " . $config['id'] . " AND friend_y = " . $row['id'] . ";")){
                                        ?>
                                        <form method="post">
                                            <input type="hidden" name="__requestId" value="<?php echo $row['id'];?>" />
                                            <button class="btn btn-primary" type="submit" name="__sendRequest">
                                            Follow
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M11 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM1.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zm9.974.056v-.002.002zM6 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm4.5 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                                <path fill-rule="evenodd" d="M13 7.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0v-2z"/>
                                            </svg>
                                            </button>
                                        </form>
                                        <form method="POST" style="margin-top: 8px;" action="./profile.php?profid=<?php echo $row['id'];?>">
                                            <button class="btn btn-primary" onclick="location.href='./profile.php?profid=<?php echo $row['id'];?>'">Go to profile</button>
                                        </form>
                                        <?php
                                    }
                                    ?>
                                </small>
                            </p>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php
                $__c++;
            }
            } catch (\Throwable $th) {
                die("<h1 class='display-4 text-center'>Operation cancelled by server on account of manual breakage</h1><center><small class='display-4' style='font-size:20px'>Please contact administration for further information</small></center>");
            }
        }
        if (isset($_GET['input']) and substr($_GET['input'], 0, 1) == "#") {
            // It's a global trand search
        }
    ?>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  </body>
</html>