<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    include '403.php';
    exit;
}

$id = $_GET['id'] ?? null;

include 'functions.php';

$username = $_SESSION['username'];

$editQuestion = $_GET['edit_question'] ?? null;
if (isset($editQuestion)) {
    $newBody = $_POST['new-question-body'];

    $pdo = getDatabaseConnection();

    $statement = $pdo->prepare("UPDATE questions SET body = ?, time_modified = CURRENT_TIMESTAMP WHERE question_id = ?");
    $statement->execute(array($newBody, $editQuestion));

    $_SESSION['modified_question'] = true;

    header("Location: question.php?id=$id");
    exit;
}

$editAnswer = $_GET['edit_answer'] ?? null;
if (isset($editAnswer)) {
    $newBody = $_POST['new-answer-body'];

    $pdo = getDatabaseConnection();

    $sql = "UPDATE answers SET body = '$newBody', time_modified = CURRENT_TIMESTAMP WHERE answer_id=$editAnswer";
    $pdo->query($sql);

    header("Location: question.php?id=$id#$editAnswer");
    exit;
}

$body = $_POST['body'] ?? null;
if (isset($body)) {

    $pdo = getDatabaseConnection();

    $statement = $pdo->prepare("INSERT INTO answers (question_id, username, body) VALUES (?, ?, ?)");
    $statement->execute(array($id, $username, $body));
    $row = $statement->fetch();

    $_SESSION['posted_answer'] = true;

    header("Location: question.php?id=$id");
    exit;
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php' ?>
    <div class="container mt-4">
        <div>
            <h4>Question #<?= $_GET['id'] ?></h4>
            <hr>
        </div>
        <?php
            $pdo = getDatabaseConnection();

            $question_id = $_GET['id'] ?? null;

            $statement = $pdo->prepare("SELECT * FROM questions WHERE question_id = ?");
            $statement->execute(array($question_id));
            $row = $statement->fetch();

            $time_posted = date("F j, Y \a\\t g:i a", strtotime($row['time_posted']));
            if ($row['time_modified']) {
                $time_modified = date("F j, Y \a\\t g:i a", strtotime($row['time_modified']));
            }
        ?>
        <div>
          <?php
            if (isset($_SESSION['modified_question'])) {
                echo '<div class="alert alert-primary text-center">Question has been edited.</div>';
                unset($_SESSION['modified_question']);
            }
            if (isset($_SESSION['deleted_answer'])) {
                echo "<div class='alert alert-primary text-center'>Answer #{$_SESSION['deleted_answer']} has been deleted.</div>";
                unset($_SESSION['deleted_answer']);
            }
            if (isset($_SESSION['posted_answer'])) {
                echo "<div class='alert alert-primary text-center'>Your answer has been posted.</div>";
                unset($_SESSION['posted_answer']);
            }
            ?>
            <div class="d-flex">
                <div class="mx-3">
                    <?php
                        $statement = $pdo->query("SELECT vote FROM question_votes WHERE question_id={$row['question_id']} AND voter={$_SESSION['user_id']}");
                        $vote = $statement->fetch()['vote'] ?? null;
                    ?>
                    <form method="post" action='<?= "upvote_question.php?qid=$id" ?>'>
                        <button class="btn btn-light">
                            <?php if ($vote == 1) : ?>
                                <svg name="upvote-answer" class="svg-icon iconArrowUpLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="cornflowerblue" d="M2 25h32L18 9 2 25Z"></path></svg>
                            <?php else: ?>
                                <svg name="upvote-answer" class="svg-icon iconArrowUpLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="grey" d="M2 25h32L18 9 2 25Z"></path></svg>
                            <?php endif ?>
                        </button>
                    </form>
                    <div class="text-center">
                        <?= $row['votes'] ?> votes
                    </div>
                    <form method="post" action='<?= "downvote_question.php?qid=$id" ?>'>
                        <button class="btn btn-light">
                            <?php if ($vote == -1) : ?>
                                <svg aria-hidden="true" class="svg-icon iconArrowDownLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="orange" d="M2 11h32L18 27 2 11Z"></path></svg>
                            <?php else: ?>
                                <svg aria-hidden="true" class="svg-icon iconArrowDownLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="grey" d="M2 11h32L18 27 2 11Z"></path></svg>
                            <?php endif ?>
                        </button>
                    </form>
                </div>
                <div class="flex-fill">
                    <h5 style="color: hsl(206, 100%, 40%) !important"><a><?= $row['title'] ?></a></h5>
                    <textarea class="p-2" name="" id="content" cols="" rows="" style="width: 100%; background: #f9f9f9 !important; height: 150px !important;" readonly><?= $row['body'] ?></textarea>
                    <!-- Edit question form -->
                    <form method="post" action="question.php?id=<?=$id?>&edit_question=<?=$row['question_id']?>" class="" id="question-form" style="display: none; visibility: hidden">
                        <textarea class="p-2" name="new-question-body" id="" cols="" rows="" style="width: 100%; background: #f9f9f9 !important; height: 150px;"><?= $row['body'] ?></textarea>
                        <div class="my-2">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button class="btn btn-secondary" type="button" id="cancel-edit-btn">Cancel</button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="d-flex mt-4">
                <div class="flex-fill">
                    <?php
                        $asker_id = $pdo->query("SELECT user_id FROM users WHERE username = '{$row['username']}'")->fetch()['user_id'];
                    ?>
                    <span class=""><?= "asked by <a href='profile.php?id=$asker_id'>{$row['username']}</a> at " . $time_posted ?></span>
                </div>
                <div class="flex-fill">
                    <?php if (isset($time_modified)): ?>
                        <span class="text-muted"><?= "edited at " . $time_modified ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($username == $row['username'] || $_SESSION['is_admin']): ?>
                        <div id="question-controls">
                            <button class="btn btn-primary" id="edit-question-btn">Edit</button>
                            <button class="btn btn-secondary" onclick="location.href = 'delete_question.php?id=<?=$id?>'">Delete</button>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <hr>
        <h3 class="mb-3">Answers</h3>
        <div>
            <?php $sql = "SELECT * FROM answers WHERE question_id = $id"; ?>
            <?php foreach ($pdo->query($sql) as $row): ?>
                <div class="d-flex flex-row mb-3 px-3 py-3 border border-2 rounded" id="<?= $row['answer_id'] ?>">
                    <div class="mx-3">
                        <?php
                            $statement = $pdo->query("SELECT vote FROM answer_votes WHERE answer_id={$row['answer_id']} AND voter={$_SESSION['user_id']}");
                            $vote = $statement->fetch()['vote'] ?? null;
                        ?>
                        <form method="post" action='<?= "upvote_answer.php?qid=$id&aid={$row['answer_id']}" ?>'>
                            <button class="btn btn-light">
                                <?php if ($vote == 1) : ?>
                                    <svg class="svg-icon iconArrowUpLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="cornflowerblue" d="M2 25h32L18 9 2 25Z"></path></svg>
                                <?php else: ?>
                                    <svg class="svg-icon iconArrowUpLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="grey" d="M2 25h32L18 9 2 25Z"></path></svg>
                                <?php endif ?>
                            </button>
                        </form>
                        <div class="text-center">
                            <?= $row['votes'] ?> votes
                        </div>
                        <form method="post" action=<?= "downvote_answer.php?qid=$id&aid={$row['answer_id']}" ?>>
                            <button class="btn btn-light">
                                <?php if ($vote == -1) : ?>
                                    <svg aria-hidden="true" class="svg-icon iconArrowDownLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="orange" d="M2 11h32L18 27 2 11Z"></path></svg>
                                <?php else: ?>
                                    <svg aria-hidden="true" class="svg-icon iconArrowDownLg" width="30" height="30" style="cursor: pointer;" viewBox="0 0 36 36"><path fill="grey" d="M2 11h32L18 27 2 11Z"></path></svg>
                                <?php endif ?>
                            </button>
                        </form>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex mb-2">
                            <div class="flex-fill">
                                <?php
                                    $answerer_id = $pdo->query("SELECT user_id FROM users WHERE username = '{$row['username']}'")->fetch()['user_id'];
                                ?>
                                <span><?= "<a href='profile.php?id=$answerer_id'>{$row['username']}</a> at $time_posted" ?></span>
                            </div>
                            <div class="flex-fill text-muted">
                                <?php
                                    if ($row['time_modified']) {
                                        $time_modified = date("F j, Y \a\\t g:i a", strtotime($row['time_modified']));
                                        echo "edited at $time_modified";
                                    }
                                    ?>
                            </div>
                            <div>
                                <?php if ($username === $row['username'] || $_SESSION['is_admin']): ?>
                                    <div id="answer-controls-<?= $row['answer_id'] ?>">
                                        <button class="btn btn-primary edit" value="<?= $row['answer_id'] ?>">Edit</button>
                                        <button class="btn btn-secondary" onclick="location.href = 'delete_answer.php?id=<?=$row['answer_id']?>&q=<?= $id ?>'">Delete</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <textarea class="p-2" name="" id="content-<?= $row['answer_id'] ?>" cols="" rows="" style="width: 100%; background: #f9f9f9 !important; height: 150px;" readonly><?= $row['body'] ?></textarea>
                        <!-- Edit answer form -->
                        <form method="post" action="question.php?id=<?=$id?>&edit_answer=<?=$row['answer_id']?>" id="answer-form-<?= $row['answer_id'] ?>" style="display: none; visibility: hidden">
                            <textarea class="p-2" name="new-answer-body" id="" cols="" rows="" style="width: 100%; background: #f9f9f9 !important; height: 150px !important"><?= $row['body'] ?></textarea>
                            <div class="my-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <button class="btn btn-secondary cancel" type="button" value="<?= $row['answer_id'] ?>">Cancel</button>
                            </div>
                        </form>
                        <?php
                            $time_posted = date("F j, Y \a\\t g:i a", strtotime($row['time_posted']));
                            $time_modified = date("F j, Y \a\\t g:i a", strtotime($row['time_modified']));
                        ?>
                        <div class="my-2"></div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="p-4 my-5 rounded" style="background-color: #f1f2f3">
                <h4>Add answer</h4>
                <form method="post">
                    <textarea class="form-control" name="body" placeholder=""></textarea>
                    <button class="btn btn-primary mt-3" type="submit" value="Submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const cancelEditQuestionBtn = document.getElementById('cancel-edit-btn');
        cancelEditQuestionBtn.addEventListener('click', (event) => {
            let form = document.getElementById('question-form');
            form.style.visibility = 'hidden';
            form.style.display = 'none';

            let content = document.getElementById('content');
            content.style.visibility = 'visible';
            content.style.display = 'unset';

            let defaultControls = document.getElementById('question-controls');
            defaultControls.style.visibility = 'visible';
            defaultControls.style.display = 'unset';
        });

        const editQuestionBtn = document.getElementById('edit-question-btn') ?? null;
        if (editQuestionBtn) {
        editQuestionBtn.addEventListener('click', (event) => {

            let form = document.getElementById('question-form');
            form.style.visibility = 'visible';
            form.style.display = 'unset';

            let content = document.getElementById('content');
            content.style.visibility = 'hidden';
            content.style.display = 'none';

            let defaultControls = document.getElementById('question-controls');
            defaultControls.style.visibility = 'hidden';
            defaultControls.style.display = 'none';

            form[0].focus();
        });
    }

        document.querySelectorAll('.edit').forEach(item => {
            item.addEventListener('click', event => {

            const aid = item.value;

            let form = document.getElementById(`answer-form-${aid}`);
            form.style.visibility = 'visible';
            form.style.display = 'unset';

            let content = document.getElementById(`content-${aid}`);
            content.style.visibility = 'hidden';
            content.style.display = 'none';

            let defaultControls = document.getElementById(`answer-controls-${aid}`);
            defaultControls.style.visibility = 'hidden';
            defaultControls.style.display = 'none';

            form[0].focus();
        });
    });

    document.querySelectorAll('.cancel').forEach(item => {
            item.addEventListener('click', event => {

            const aid = item.value;

            let form = document.getElementById(`answer-form-${aid}`);
            form.style.visibility = 'hidden';
            form.style.display = 'none';

            let content = document.getElementById(`content-${aid}`);
            content.style.visibility = 'visible';
            content.style.display = 'unset';

            let defaultControls = document.getElementById(`answer-controls-${aid}`);
            defaultControls.style.visibility = 'visible';
            defaultControls.style.display = 'unset';
        });
    });

    </script>

</body>
</html>
