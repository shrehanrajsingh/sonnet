<?php
include 'conn.php';
include 'desk.php';
include 'snackbar.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Signup</title>
    <style>
        body{
            margin:25px;
            padding:25px;
            overflow-y:scroll;
        }
    </style>
  </head>
  <body>
    <form class="shadow-lg p-3 mb-5 rounded bg-white" method="POST" enctype="multipart/form-data">
    <h1 class="display-3">Make an account at Sonnet</h1>
    <hr>
      <div class="form-row">
        <div class="form-group">
            <label for="__user_name">Username</label>
            <input type="text" class="form-control" id="__user_name" name="__user_name" autofocus required>
        </div>
        <div class="form-group">
          <label for="__user_email">Email</label>
          <input type="email" class="form-control" id="__user_email" name="__user_email" required>
        </div>
        <div class="form-group">
          <label for="__user_pass">Password</label>
          <input type="password" class="form-control" id="__user_pass" name="__user_pass" required>
        </div>
      </div>
      <div class="form-group">
        <label for="__display_name">Display name</label>
        <input type="text" class="form-control" id="__display_name" name="__display_name" required>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="__user_status">Status</label>
          <input type="text" class="form-control" id="__user_status" name="__user_status" required>
        </div>
        <br>
        <div class="form-file form-file-sm">
          <input type="file" class="form-file-input" id="__user_profile" name="__user_profile" required accept="image/*">
          <label class="form-file-label" for="customFileSm">
            <span class="form-file-text">Choose profile pic...</span>
            <span class="form-file-button">Browse</span>
          </label>
        </div>
        <br>
        <label>Choose account type</label>
        <select class="form-select" name="__user_acc_type" aria-label="Default select example">
          <option value="private" selected>Private</option>
          <option value="public">Public</option>
        </select>
        <br>
      <button type="submit" name="__submit_signup" class="btn btn-primary btn-block" style="margin-bottom:8px">Create account</button>
      <center><a href="./login.php" class="alert-link" style="font-weight:300">Login</a></center>
    </form>
    <br>
    <?php
    include './footer.php';
    ?>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  </body>
</html>