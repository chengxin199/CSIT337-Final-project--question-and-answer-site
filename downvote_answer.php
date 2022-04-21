<?php

session_start();

$question_id = $_GET['qid'];
$answer_id = $_GET['aid'];

$user_id = $_SESSION['user_id'];

if (isset($answer_id)) {

    include 'functions.php';
    $pdo = getDatabaseConnection();

    $statement = $pdo->query("SELECT vote FROM answer_votes WHERE voter=$user_id AND answer_id = $answer_id");
    $num_rows = $statement->rowCount();
    $row = $statement->fetch();

    if ($num_rows === 0) {
        $statement = "INSERT INTO answer_votes (answer_id, voter, vote) VALUES ($answer_id, $user_id, -1)";
        $pdo->query($statement);

        $statement = "SELECT votes FROM answers WHERE answer_id=$answer_id";
        $result = $pdo->query($statement);
        $row = $result->fetch();
        $votes = $row['votes'];

        $newVotes = $votes - 1;

        $statement = "UPDATE answers set votes = $newVotes WHERE answer_id=$answer_id";
        $pdo->query($statement);

        header("Location: question.php?id=$question_id#$answer_id");
        exit;
    }

    if ($row['vote'] === -1) {

        $statement = "SELECT votes FROM answers WHERE answer_id=$answer_id";
        $result = $pdo->query($statement);
        $row2 = $result->fetch();
        $votes = $row2['votes'];

        $newVotes = $votes + 1;

        $statement = "DELETE FROM answer_votes WHERE voter=$user_id AND answer_id = $answer_id";
        $pdo->query($statement);

            $statement = "UPDATE answers set votes = $newVotes WHERE answer_id=$answer_id";
            $pdo->query($statement);

        header("Location: question.php?id=$question_id#$answer_id");
        exit;
    }

    if ($row['vote'] == 1) {

        $statement = "SELECT votes FROM answers WHERE answer_id=$answer_id";
        $result = $pdo->query($statement);
        $row2 = $result->fetch();
        $votes = $row2['votes'];

        $newVotes = $votes - 2;

        $statement = "UPDATE answer_votes SET vote = -1 WHERE voter=$user_id AND answer_id = $answer_id";
        $pdo->query($statement);

            $statement = "UPDATE answers set votes = $newVotes WHERE answer_id=$answer_id";
            $pdo->query($statement);

        header("Location: question.php?id=$question_id#$answer_id");
        exit;
    }

    // if ($row['vote'] == -1) {
    //     $newVotes = $votes + 2;

    //     $statement = "UPDATE answer_votes set vote = 1 WHERE answer_id=$answer_id AND voter=$user_id";
    //     $pdo->query($statement);
    // }

    //     $statement = "UPDATE answers set votes = $newVotes WHERE answer_id=$answer_id";
    //     $pdo->query($statement);

    //     header("Location: question.php?id=$question_id");
    //     exit;

}
