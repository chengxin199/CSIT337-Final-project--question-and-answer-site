<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    include '403.php';
    exit;
}

$id = $_GET['id'] ?? $_SESSION['user_id'];
$view = $_GET['view'] ?? null;

include 'functions.php';
$pdo = getDatabaseConnection();

$statement = $pdo->query("SELECT * FROM users WHERE user_id = $id");
$row = $statement->fetch();

$username = $row['username'];
$date = $row['registration_date'];

$formattedDate = date("d M Y", strtotime($date));

$statement = $pdo->query("SELECT COUNT(*) FROM questions WHERE username='$username'");
$numQuestions = $statement->fetchColumn();

$statement = $pdo->query("SELECT COUNT(*) FROM answers WHERE username='$username'");
$numAnswers = $statement->fetchColumn();

$statement = $pdo->query("SELECT SUM(votes) FROM questions WHERE username='$username'");
$numQuestionVotes = $statement->fetchColumn() ?? 0;

$statement = $pdo->query("SELECT SUM(votes) FROM answers WHERE username='$username'");
$numAnswerVotes = $statement->fetchColumn() ?? 0;
?>

<!doctype html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <?php if ($_SESSION['username'] === $row['username']): ?>
                <title>My profile - Jupiter</title>
            <?php else: ?>
                <title><?= $row['username'] ?>'s profile - Jupiter</title>
            <?php endif; ?>
</head>
  <body>
  <?php include 'navbar.php' ?>
    <div class="container mt-4">
        <!--  -->
        <div>
            <?php if ($_SESSION['username'] === $row['username']): ?>
                <h4>My profile</h4>
            <?php else: ?>
                <h4><?= $row['username'] ?>'s profile</h4>
            <?php endif; ?>
            <hr>
        </div>

        <!-- Profile information -->
        <div class="d-flex flex-row mb-3">
            <div class="d-flex flex-column" style="margin-right: px;">
                <div class="row">
                    <span class="display-6"><?= "{$row['username']}" ?>
                    <?php if ($row['is_admin']): ?>
                        <span class="badge bg-primary my-auto" style="font-size: 13px; position: relative; top: -8px">Administrator</span>
                        <?php endif ?>
                    </span>
                </div>
                <div>
                    <h6 class="text-muted mt-1"><?= "joined $formattedDate" ?></h6>
                </div>
            </div>
            <div class="d-flex flex-column mx-5 my-auto">
                <?php if ($id != $_SESSION['user_id']): ?>
                    <button class="btn btn-primary" onclick="location.href = 'mailto:<?=$row['email']?>'">Contact</button>
                <?php endif ?>
                <?php if ($_SESSION['is_admin'] && $id != $_SESSION['user_id']): ?>
                    <button class="btn btn-secondary mt-1" onclick="location.href = 'delete_user.php?id=<?=$id?>'">Delete</button>
                <?php endif ?>
            </div>
            <div class="flex-fill">
                <div class="border border-3 p-2 rounded">
                    <div class="row">
                        <div class="col" style="width: 170px">
                            <?= "<span class='text-muted'>Questions:</span> <br> <h4>$numQuestions</h4>" ?>
                        </div>
                        <div class="col" style="width: 170px">
                            <?= "<span class='text-muted'>Answers:</span> <br> <h4>$numAnswers</h4>" ?>
                        </div>
                        <div class="col" style="width: 170px">
                            <?= "<span class='text-muted'>Question votes:</span> <br> <h4>$numQuestionVotes</h4>" ?>
                        </div>
                        <div class="col" style="width: 170px">
                            <?= "<span class='text-muted'>Answer votes:</span> <br> <h4>$numAnswerVotes</h4>" ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <!-- View content buttons -->
        <div class="d-flex mb-3">
            <div class="mx-auto">
                <button class="btn btn-primary mx-2" style="width: 200px" onclick="location.href = 'profile.php?id=<?=$id?>'">View Questions</button>
                <button class="btn btn-primary mx-2" style="width: 200px" onclick="location.href = 'profile.php?id=<?=$id?>&view=a'">View Answers</button>
            </div>
        </div>

        <!-- Questions or answers -->
        <div class="d-flex">
            <div class="d-flex mx-5">
                <div class="">
                    <?php
                        $pdo = getDatabaseConnection();

                        if (!isset($view)) {
                            $sql = "SELECT * FROM questions WHERE username = '{$row['username']}'";
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
                                <div class="d-flex mb-3 p-3 border border-2 rounded">
                                <div class="mx-3 text-center">
                                <span class="">{$row['votes']} votes</span>
                                <div>
                                <span class="$bg">$numAnswers answers</span>
                                </div>
                                </div>
                                <div>
                                <h5 class="title">
                                <a href="question.php?id={$row['question_id']}" style="color: hsl(206, 100%, 40%); text-decoration: none">
                                {$row['title']}
                                </a>
                                </h5>
                                <textarea class="text-muted" cols="100" rows="$rows" style="resize: none; overflow: hidden;" readonly>{$row['body']}</textarea>
                                <div class="mt-2">
                                    asked by <a href="profile.php?id=$user_id">{$row['username']}</a> on $formattedDate, at $formattedTime
                                </div>
                                </div>
                                </div>
                                EOD;
                            }
                        } else {
                            $sql = "SELECT * FROM answers WHERE username = '{$row['username']}'";
                            foreach ($pdo->query($sql) as $row) {
                                $statement = $pdo->query("SELECT user_id FROM users WHERE username='{$row['username']}'");
                                $user_id = $statement->fetch()['user_id'];

                                $formattedDate = date("M j, Y", strtotime($row['time_posted']));
                                $formattedTime = date("g:i a", strtotime($row['time_posted']));

                                $rows = (strlen($row['body']) < 100) ? 1 : 2;

                                echo <<<EOD
                                <div class="d-flex mb-3 p-3 border border-2 rounded">
                                    <div class="mx-2 my-auto text-center" style="width: 80px">
                                        <span class="">{$row['votes']} votes</span>
                                    </div>
                                    <div>
                                <div class="mb-2">
                                    answered to <a href="question.php?id={$row['question_id']}">Question #{$row['question_id']}</a> on $formattedDate, at $formattedTime
                                </div>
                                <textarea class="text-muted" cols="100" rows="$rows" style="resize: none; overflow: hidden;" readonly>{$row['body']}</textarea>
                                </div>
                                </div>
                                EOD;
                            }
                        }


                        ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
