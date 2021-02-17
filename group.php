<?php
include 'conn.php';
include 'desk.php';
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Groups</title>
    <style>
        body {
            margin: 25px;
            margin-top: 100px;
        }
    </style>
</head>

<body class="bg-light">
    <?php
    include 'navbar.php';
    ?>
    <?php
    global $conn;
    global $config;
    $get_groups = $conn->query("SELECT * FROM groups WHERE JSON_SEARCH(members, 'all', '" . $config['id'] . "') IS NOT NULL;");
    if ($get_groups) {
        if ($get_groups->num_rows > 0) {
            while($r = $get_groups->fetch_assoc()){
                ?>
                <div class="card">
                    <div class="card-header">
                        <img src="<?php echo $r['icon'];?>" alt width="40" height="40" style="border-radius: 50%;">
                        <span style="font-size: 120%;"><?php echo $r['group_name']; ?></span>
                        <?php
                        $get_group = $conn->query("SELECT * FROM groups WHERE id = " . $r['id'] . " AND JSON_SEARCH(members, 'all', '" . $config['id'] . "') IS NOT NULL;");
                        $c__ = 0;
                        if ($get_group){
                            if ($get_group->num_rows > 0){
                                while($r1 = $get_group->fetch_assoc()){
                                    $chk_arr = $r1['group_texts'];
                                    $chk_arr = json_decode($chk_arr, true);
                                    foreach ($chk_arr as $key) {
                                        if (!in_array($config['id'], $key['txt_seen_by'])){
                                            $c__++;
                                        }
                                    }
                                }
                            }
                        }
                        echo $c__ != 0 ? '<span class="badge bg-primary float-right">' . $c__ . '</span>' : null;
                        ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php
                                echo count(json_decode($r['members'])) . " members";
                            ?>
                        </h5>
                        <p class="card-text">
                            <?php
                                echo $r['group_desc'];
                            ?>
                            <br>
                        </p>
                        <a href="./group_home.php?group_id=<?php echo $r['id']; ?>" class="btn btn-primary">Enter</a>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>