<?php

session_start();

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

if (isset($username) && isset($password)) {

    include 'functions.php';
    $pdo = getDatabaseConnection();

    $statement = $pdo->prepare('SELECT user_id, password, is_admin FROM users WHERE username=?');
    $statement->execute(array($username));
    $row = $statement->fetch();

    $storedPassword = $row['password'];

    if (password_verify($password, $storedPassword)) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['is_admin'] = $row['is_admin'];
        header('Location: questions.php?sort=new');
        exit;
    }

    $_SESSION['invalid_login'] = true;

    header('Location: login.php');
    exit;
}

?>

<!doctype html>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Login - Jupiter</title>
  </head>
  <body>
    <div class="container mt-5" style="width: 35%;">
      <h2 class="fw-bold my-4 text-center">Jupiter</h2>
      <h5 class="my-4 text-center">Sign in to your account</h5>
      <form method="post">
        <div class="">
            <input class="form-control" type="text" name="username" placeholder="Username" required>
        </div>
        <div class="my-3">
            <input class="form-control" type="password" name="password" placeholder="Password" required>
        </div>
        <?php
            if (isset($_SESSION['invalid_login'])) {
                echo "<div class='alert alert-danger text-center'>Invalid username or password.</div>";
                unset($_SESSION['invalid_login']);
            }
        ?>
        <div class="my-3 text-center">
            <input class="btn btn-primary" type="submit" value="Submit" style="width: 50% !important;">
        </div>
      </form>
      <div class="my-3 text-center">
          <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
