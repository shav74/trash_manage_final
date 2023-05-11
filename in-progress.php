<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navStyle.css" />
    <link rel="stylesheet" href="css/mainStyle.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <script src="js/navjs.js" defer></script>
    <title>Articles</title>

    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        .card {
            margin: 3%;
            flex-basis: 90%;
            padding: 5%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
        }
    </style>

</head>

<body>
    <div class="centered">
        <section class="cards">
            <?php include_once('inc/nav.php') ?>

            <?php
            $postQuery = "SELECT * FROM report WHERE is_deleted=0 AND is_read=1 AND cleaning_progress < 3";
            $resultSet = mysqli_query($connection, $postQuery);
            while ($report = mysqli_fetch_assoc($resultSet)) {
                $progress = $report['cleaning_progress'];
                $progress_msg = "";
                if ($progress == 1) {
                    $progress_msg = "Yet to be cleaned";
                } elseif ($progress == 2) {
                    $progress_msg = "Cleaning in progress";
                } else {
                    $progress_msg = "Pending";
                }
                ?>
                <article class="card">
                    <span class="date">
                        <?= date('F jS,Y', strtotime($report['date'])) ?>
                    </span>
                    <a href="respond-to-report.php?id=<?= $report['id'] ?>" style="text-decoration: none">
                        <h2 style="color: #000000;">
                            <?= $report['title'] ?>
                        </h2><br>

                        <h4 style="color: #ff3244;">Progress =
                            <?= $progress_msg ?>
                        </h4><br>

                        <div class="content-text">
                            <p style="color: #000000;">
                                <?= $report['content'] ?>
                            </p>
                        </div>
                    </a>
                </article>
                <?php
            }
            ?>
        </section>
    </div>
</body>

</html>