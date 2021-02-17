<?php
include 'conn.php';
include 'desk.php';
$frontend_border_arrays = ['primary', 'secondary', 'dark', 'danger', 'warning', 'info', 'success'];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Friends</title>
</head>
<style>
    body{
        margin: 25px;
    }
    div{
        margin-bottom: 20px;
    }
</style>
<body class="bg-light">
    <button class="btn btn-primary float-right" onclick="location.href='./new_group'">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-people-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
        </svg> New Group
    </button>
    <?php
    function __main__(){
        global $conn;
        global $config;
        global $frontend_border_arrays;
        $state = 0;
        $get_all_friends = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";");
        $chatted_partners = array();
        $starta = array();
        if ($get_all_friends) {
            if ($get_all_friends->num_rows > 0) {
                while ($r = $get_all_friends->fetch_assoc()) {
                    $that = $r['friend_x'] == $config['id'] ? $r['friend_y'] : $r['friend_x'];
                    // $is_chatted = $conn->query("SELECT * FROM texts WHERE from_id = " . $that . " OR to_id = " . $that . " AND from_id = " . $config['id'] . " OR to_id = " . $config['id'] . ";");
                    $is_chatted = $conn->query("SELECT * FROM texts WHERE from_id = " . $config['id'] . " AND to_id = " . $that . " OR from_id = $that AND to_id = " . $config['id'] . ";");
                    if ($is_chatted) {
                        if ($is_chatted->num_rows > 0) {
                            $chatted_partners[] = $r;
                        } else {
                            $starta[] = $r;
                        }
                    }
                }
            }
        }
        ?>
        <!-- // ? SEGMENT 1, continue chat -->
        <?php
        if ($chatted_partners) {
            $state = 1;
        ?>
            <h3 class="display-4" style="font-size: 40px;">Continue chatting with-</h3><br>
            <?php
            for ($i = 0; $i < count($chatted_partners); $i++) {
                $el = $chatted_partners[$i];
                $un = $el['friend_x'] == $config['id'] ? $el['friend_y'] : $el['friend_x'];
                $chk_txt = $conn->query("SELECT * FROM texts WHERE from_id = " . $un . " AND to_id = " . $config['id'] . ";");
                $tlen = 0;
                if ($chk_txt) {
                    if ($chk_txt->num_rows > 0){
                        while($t = $chk_txt->fetch_assoc()){
                            if ($t['seen'] == false) $tlen++;
                        }
                    }
                }
            ?>
                <?php $rd = $frontend_border_arrays[array_rand($frontend_border_arrays)];?>
                <div onclick="location.replace('./direct.php?client=<?php echo $un; ?>')" style="cursor: pointer;" class="shadow border p-2">
                    <img src="<?php echo get_profile_from_id($un); ?>" width="50" height="50" style="border-radius: 50%;" alt>
                        <?php if ($tlen){?><span class="badge bg-<?php echo $rd;?> float-right"><?php echo $tlen?></span><?php } ?>
                    <span class="p-3 display-4" style="font-size: 20px;"><?php echo get_single_query_from_id($un, "uname", "users"); ?></span>
                </div>
            <?php
            }
        }
        if ($starta) {
            $state = 1;
            ?>
            <h3 class="display-4" style="font-size: 40px;">Start a chat with-</h3><br>
            <?php
            for ($i = 0; $i < count($starta); $i++) {
                $el = $starta[$i];
                $un = $el['friend_x'] == $config['id'] ? $el['friend_y'] : $el['friend_x'];
                $chk_txt = $conn->query("SELECT * FROM texts WHERE from_id = " . $un . " AND to_id = " . $config['id'] . ";");
                $tlen = 0;
                if ($chk_txt) {
                    if ($chk_txt->num_rows > 0){
                        while($t = $chk_txt->fetch_assoc()){
                            if ($t['seen'] == false) $tlen++;
                        }
                    }
                }
            ?>
                <?php $rd = $frontend_border_arrays[array_rand($frontend_border_arrays)];?>
                <div onclick="location.replace('./direct.php?client=<?php echo $un; ?>')" style="cursor: pointer; border-width: 100px" class="shadow border p-2">
                    <img src="<?php echo get_profile_from_id($un); ?>" width="50" height="50" style="border-radius: 50%;" alt>
                    <?php if ($tlen){?><span class="badge bg-<?php echo $rd;?> float-right"><?php echo $tlen?></span><?php } ?>
                    <span class="p-3 display-4" style="font-size: 20px;"><?php echo get_single_query_from_id($un, "uname", "users"); ?></span>
                </div>
        <?php
            }
        }
        if (!$state){
            ?>
                <h1 class="display-3">You currently have no friends</h1>
            <?php
        }
    }
    // setInterval(function(){__main__();}, 1000);
    __main__();
    ?>
    <button class="btn btn-primary rounded-circle" onclick="location.replace('./')" style="position: fixed; bottom: 25px; right: 25px;">
        <h1>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
                <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
            </svg>
        </h1>
    </button>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>