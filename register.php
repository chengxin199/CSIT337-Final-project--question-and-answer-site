<?php

session_start();

$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password1'] ?? null;

if (isset($username) && isset($email) && isset($password)) {

    include 'functions.php';
    $pdo = getDatabaseConnection();

    // Check if username is taken
    $statement = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $statement->execute(array($username));
    $num_rows = $statement->rowCount();
    if ($num_rows > 0) {
        $_SESSION['username_taken'] = true;
        header('Location: register.php');
        exit;
    }

    // Check if email is taken
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $statement->execute(array($email));
    $num_rows = $statement->rowCount();
    if ($num_rows > 0) {
        $_SESSION['email_taken'] = true;
        header('Location: register.php');
        exit;
    }

    $statement = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $statement->execute(array($username, $email, password_hash($password, PASSWORD_DEFAULT)));

    header('Location: login.php');
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Jupiter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="width: 35%;">
      <h2 class="fw-bold my-4 text-center">Jupiter</h2>
      <h5 class="my-4 text-center">Register for an account</h5>
      <form method="post" oninput="password2.setCustomValidity(password1.value != password2.value ? 'Passwords do not match.' : '')">
        <div class="">
            <input class="form-control" type="text" name="username" placeholder="Username" required>
        </div>
        <div class="my-3">
            <input class="form-control" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="my-3">
            <input class="form-control" type="password" name="password1" placeholder="Password" required>
        </div>
        <div class="my-3">
            <input class="form-control" type="password" name="password2" placeholder="Confirm password" required>
        </div>
        <?php
            if (isset($_SESSION['username_taken'])) {
                echo "<div class='alert alert-danger text-center'>Username is taken. Please enter another.</div>";
                unset($_SESSION['username_taken']);
            }

            if (isset($_SESSION['email_taken'])) {
                echo "<div class='alert alert-danger text-center'>Email is taken. Please enter another.</div>";
                unset($_SESSION['email_taken']);
            }
        ?>
        <div class="my-3 text-center">
            <input class="btn btn-primary" type="submit" value="Submit" style="width: 50% !important;">
        </div>
      </form>
      <div class="my-3 text-center">
          <p>Already have an account? <a href="login.php">Sign in</a></p>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
