<?php
  include 'desk.php';
  include 'snackbar.php';
  if(isset($_COOKIE['__sonnet_user_credits'])) header('location:./');
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Login</title>
    <style>
        body{
            margin:25px;
            padding:25px;
            overflow:hidden;
        }
    </style>
  </head>
  <body class="bg-light">
    <form class="shadow-lg p-5 mb-5 bg-white rounded" method="POST" enctype="multipart/form-data">
    <h1 class="display-3">Login to Sonnet</h1>
    <hr>
      <div class="mb-3">
        <label for="__user_email" class="form-label">Email address</label>
        <input type="email" class="form-control" name="__user_email" id="__user_email" autofocus required>
      </div>
      <div class="mb-3">
        <label for="__user_pass" class="form-label">Password</label>
        <input type="password" class="form-control" name="__user_pass" id="__user_pass" required>
      </div>
      <button type="submit" name="__submit_login" class="btn btn-primary btn-block" style="margin-bottom:8px">Login</button>
      <center><a href="./signup.php" class="alert-link" style="font-weight:400">Create account</a></center>
    </form>

    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <?php
    include 'footer.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  </body>
</html>