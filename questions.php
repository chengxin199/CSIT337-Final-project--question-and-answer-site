<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    include '403.php';
    exit;
}

$sort = $_GET['sort'];

if ($sort === 'new') {
    $order = 'time_posted';
}

if ($sort === 'votes') {
    $order = 'votes';
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Questions - Jupiter</title>
    <style>
        a { color: blue; }

        textarea {
            font-size: 14px;
            border: 0;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div>
            <h4 style="display: inline">All Questions</h4>
            <div class="float-end">
                <button class="btn btn-primary mx-3" type="button" onclick="location.href = 'questions.php?sort=new'">Sort by new</button>
                <button class="btn btn-primary" type="button" onclick="location.href = 'questions.php?sort=votes'">Sort by votes</button>
            </div>
            <hr>
        </div>

        <?php
            if (isset($_SESSION['posted_question'])) {
                echo '<div class="alert alert-primary text-center">Your question has been posted.</div>';
                unset($_SESSION['posted_question']);
            }

            if (isset($_SESSION['deleted_question'])) {
                echo '<div class="alert alert-primary text-center">';
                echo "Question #{$_SESSION['deleted_question']} has been deleted.</div>";
                unset($_SESSION['deleted_question']);
            }

            if (isset($_SESSION['deleted_user'])) {
                echo '<div class="alert alert-primary text-center">';
                echo "User {$_SESSION['deleted_user']} has been deleted.</div>";
                unset($_SESSION['deleted_user']);
            }
        ?>

        <?php
            include 'functions.php';
            $pdo = getDatabaseConnection();

            $sql = "SELECT * FROM questions ORDER BY $order DESC";
            foreach ($pdo->query($sql) as $row) {
                $statement = $pdo->query("SELECT user_id FROM users WHERE username='{$row['username']}'");
                $user_id = $statement->fetch()['user_id'];

                $statement = $pdo->query("SELECT COUNT(*) FROM answers WHERE question_id={$row['question_id']}");
                $numAnswers = $statement->fetchColumn();

                $bg = ($numAnswers == 0) ? "badge rounded-pill bg-secondary" : "badge rounded-pill bg-success";

                $formattedDate = date("M j, Y", strtotime($row['time_posted']));
                $formattedTime = date("g:i a", strtotime($row['time_posted']));

                $rows = (strlen($row['body']) < 100) ? 1 : 2;

            echo <<<EOD
                <div class="d-flex flex-row mb-3 p-3 border border-2 rounded">
                <div class="mx-3 text-center">
                <span class="">{$row['votes']} votes</span>
                    <div>
                        <span class="$bg">$numAnswers answers</span>
                    </div>
            </div>
            <div>
                <h5 class="title" class="mb-0">
                    <a href="question.php?id={$row['question_id']}" style="color: hsl(206, 100%, 40%); text-decoration: none">
                        {$row['title']}
                    </a>
                </h5>
                <textarea class="text-muted" cols="100" rows="$rows" style="resize: none; overflow: hidden;" readonly>{$row['body']}</textarea>
                <div class="mt-2">
                    <small class="">
                    asked by <a href="profile.php?id=$user_id">{$row['username']}</a> on $formattedDate, at $formattedTime
                    </small>
                </div>
            </div>
        </div>
EOD;
    }
    ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
