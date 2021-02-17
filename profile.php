<?php
include 'conn.php';
include 'desk.php';
include 'snackbar.php';
if (!isset($_GET['profid'])) die("No profile to show");
$pid = $_GET['profid'];
if (intval($pid) < 0) die("Error, can't find account with that id");
if (isset($_POST['__unf'])) {
}
if (isset($_POST['__makef'])) {
    // * Send friend request
    $sf = send_friend_request($pid);
    switch ($sf) {
        case 0:
            alert("Error occurred while sending request");
            break;
        case 1:
            alert("Request sent");
            break;
        case 2:
            alert("One pending request already exists");
            break;
        case 3:
            alert("You and user are now friends");
            break;
        case 4:
            alert("You and user are already friends");
            break;
        default:
            alert("Unknown error occurred");
            break;
    }
    // alert("Hello World");
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

    <title><?php echo get_single_query_from_id($pid, "dispname", "users") ?></title>
    <style>
        body {
            margin: 24px;
            /* margin-left: 24px; */
            margin-top: 120px;
        }

        .display-4 {
            font-size: auto;
        }

        table {
            text-align: center;
        }

        button {
            margin-bottom: 10px;
        }

        @media screen and (max-width: 768px) {
            body {
                margin-right: 10px;
            }
        }
    </style>
</head>
<?php include 'navbar.php'; ?>

<body class="bg-light">
    <div class="alert alert-default bg-white md-col-12 shadow p-4">
        <img src="<?php echo get_profile_from_id($pid); ?>" alt width="50" height="50" class="rounded-circle" style="position:absolute; top:-5%;left:50%;transform:translate(-50%, 0%);">
        <h1 class="display-4 text-center"><?php echo get_single_query_from_id($pid, "uname", "users"); ?></h1>
        <br>
        <h3 style="font-weight: 100;"><i>"<?php
                                            echo get_single_query_from_id($pid, "status", "users");
                                            ?>"
            </i></h3>
        <h6 style="font-weight: 500;">Member since: <?php echo date("j F, Y", strtotime(get_single_query_from_id($pid, "reg_date", "users"))); ?></h6>
        <br>
        <div>
            <table class="table table-light table-striped table-hover align-middle shadow">
                <thead>
                    <tr>
                        <th scope="col">Posts</th>
                        <th scope="col">Friends</th>
                        <!-- <th scope="col">Following</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $conn->query("SELECT * FROM posts WHERE owner_id = " . $pid . ";")->num_rows; ?></td>
                        <!-- // * friend_x is following, friend_y is follower -->
                        <td><?php echo $conn->query("SELECT * FROM friends WHERE friend_y = " . $pid . " OR friend_x = $pid;")->num_rows; ?></td>
                        <!-- <td><?php echo $conn->query("SELECT * FROM friends WHERE friend_x = " . $pid . ";")->num_rows; ?></td> -->
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- // * Check if user followes person -->
        <?php
        if (isset($_POST['__lp'])) {
            alert(handle_like($_POST['__pid']));
        }
        $chk_f = $conn->query("SELECT id FROM friends WHERE friend_x = " . $pid . " AND friend_y = " . $config['id'] . " OR friend_x = " . $config['id'] . " AND friend_y = " . $pid . ";");
        $qn = $chk_f->num_rows;
        if ($qn > 0) {
            // * User is friend with profile author
            // * Show posts
        ?>
            <br>
            <div class="container">
                <form method="POST">
                    <button type="submit" name="__unf" class="btn btn-danger btn-block">Unfriend</button>
                </form>
                <form method="GET" action="./posts.php">
                    <input type="hidden" name="__pid" value="<?php echo $pid; ?>">
                    <button type="submit" class="btn btn-primary btn-block">View all posts</button>
                </form>
            </div>
            <?php
        } else {
            if ($pid != $config['id']) {

            ?>
                <br>
                <div class="container">
                    <form method="POST">
                        <button type="submit" name="__makef" class="btn btn-primary btn-block">Send friend request</button>
                    </form>
                </div>
        <?php
            } else {
                // * User settings
                // * To be implemented
                ?>
                <form method="GET" action="./posts.php">
                    <input type="hidden" name="__pid" value="<?php echo $pid; ?>">
                    <button type="submit" class="btn btn-primary btn-block">View all posts</button>
                </form>
                <?php
            }
        }
        ?>
    </div>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>