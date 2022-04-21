<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    include '403.php';
    exit;
}

$username = $_SESSION['username'];

$title = $_POST['title'] ?? null;
$body = $_POST['body'] ?? null;

if (isset($title) && isset($body)) {

    include 'functions.php';
    $pdo = getDatabaseConnection();

    $statement = $pdo->prepare("INSERT INTO questions (username, title, time_modified, body) VALUES (?, ?, NULL, ?)");
    $statement->execute(array($username, $title, $body));

    $_SESSION['posted_question'] = true;

    header('Location: questions.php?sort=new');
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ask a question - Jupiter</title>
    <link rel="stylesheet" href="style.css">
    <style>

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <div>
            <h4>Ask a Question</h4>
            <hr>
        </div>
        <form method="post">
            <div class="mb-3">
                <label class="fw-bold">Title</label>
                <label class="form-label small d-block text-muted">Use a descriptive title that is also concise</label>
                <input class="form-control" type="text" name="title" maxlength="150" required>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Body</label>
                <label class="form-label small d-block text-muted">Include all the information someone would need to answer your question</label>
                <textarea class="form-control" name="body" id="" maxlength="30000" cols="20" rows="10" required></textarea>
            </div>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
    </div>

</body>
</html>
