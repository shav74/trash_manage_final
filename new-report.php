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
    <link
      rel="stylesheet"
      href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"
    />
    <script src="js/navjs.js" defer></script>
    <title>Articles</title>

    <style>
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }
        .card{
            margin: 3%;
            flex-basis: 90%;
            padding: 5%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
        }
    </style>

</head>
<body>
    <?php include_once('inc/nav.php')?>
    <div class="centered">
        <section class="cards">

            <?php
                $postQuery = "SELECT * FROM location WHERE u_id=$_SESSION[user_id]";
                $resultSet = mysqli_query($connection, $postQuery);
                while($location = mysqli_fetch_assoc($resultSet)){
                ?>
                <article class="card">
                    <a href="write-report.php?loc_id=<?=$location['loc_id']?>" style="text-decoration: none">
                    <p style="color: #000000;"><?= $location['loc_name']?></p>
                    </a>
                </article>
            <?php
                }
            ?>
        </section>    
    </div>
</body>
</html>