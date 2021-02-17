<?php
$config = json_decode($_COOKIE['__sonnet_user_credits'], true);
if(isset($_POST["__logout"])){
  setcookie("__sonnet_user_credits", "", time()-3600);
  header("location:./login.php");
}
include 'conn.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top" style="background-color: #3485fd;">
  <div class="container-fluid">
    <a class="navbar-brand" href="./" title="<?php echo $config['uname']; ?>"><img src="<?php echo 'public/client/users/' . md5($config["email"]) . '/profile/' . $config['profile_name']; ?>" width="50" height="50" style="border-radius:50%" alt></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="./">Home</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Sonnet TV</a>
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
            <li><a class="dropdown-item" href="./group.php">Group</a></li>
            <li><a class="dropdown-item" href="./friends.php">Direct
            <?php
                  global $conn;
                  global $config;
                  if ($conn->query("SELECT * FROM texts WHERE to_id = " . $config['id'] . " AND seen = false;")->num_rows > 0) echo "
                <span class=\"badge bg-secondary\">" . $conn->query("SELECT * FROM texts WHERE to_id = " . $config['id'] . " AND seen = false;")->num_rows . "</span>
                ";
                  ?>
            </a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">API</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <form class="d-flex" action="./search" method="GET">
            <input class="form-control mr-2" type="search" name="input" placeholder="@account, #trend...." aria-label="Search" style="border-top-right-radius: 0%; border-bottom-right-radius:0%">
            <button class="btn btn-outline-light input-group-text" type="submit" style="border-top-left-radius: 0%; border-bottom-left-radius:0%">Search</button>
          </form>
        </li>
      </ul>
      <form method="post" class="d-flex">
        <button type="submit" class="btn btn-danger" name="__logout">Logout</button>
      </form>
    </div>
  </div>
</nav>
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
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>