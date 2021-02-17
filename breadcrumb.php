<!-- <br><br><br><br> -->
<?php
    $req = $_SERVER['REQUEST_URI'];
    $req = substr($req, 8, strlen($req)); // ! REMOVE IN PRODUCTION, (this snippet removes /sonnet/ from url)
    $req = explode("?", $req)[0];
    $req = explode("/", $req);
    ?>
    <nav aria-label="breadcrumb bg-light">
      <ol class="breadcrumb bg-light shadow-sm border" style="border-radius: 0%;">
        <li class="breadcrumb-item"><a href="./">home</a></li>
        <?php
        if(sizeof($req) != 1){
          for($key = 0; $key < count($req); $key++) {
            if($key == count($req) - 1 ) break;
            ?>
              <li class="breadcrumb-item"><a href="./<?php echo $req[$key];?>"><?php echo $req[$key];?></a></li>
            <?php
          }
          ?>
            <li class="breadcrumb-item"><a href="./<?php echo $req[count($req)-1];?>"><?php echo $req[count($req)-1];?></a></li>
          <?php
        }
        else {
          ?><li class="breadcrumb-item"><?php echo $req[0];?></li>
          <?php
        }
        ?>
        
      </ol>
    </nav>
    <?php
    ;
?>