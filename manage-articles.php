<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="css/navStyle.css" />
    <link rel="stylesheet" href="css/mainStyle.css" />
    <link rel="stylesheet" href="css/card.css" />
    <link
      rel="stylesheet"
      href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"
    />
    <script src="js/navjs.js" defer></script>
    <style>
    </style>
  </head>
  <body>
    <?php include_once('inc/nav.php')?>

    <?php
    $postQuery = "SELECT * FROM article";
    $resultSet = mysqli_query($connection, $postQuery);
    while($article = mysqli_fetch_assoc($resultSet)){
      ?>
      <div class="card-contrainer">
        <a href="article.php?id=<?=$article['id']?>" style="text-decoration: none">
        <div class="card">
          <div class="card-text">
            <span class="date"><?= date('F jS,Y',strtotime($article['date']))?></span>
                <h2><?php echo $article['title']?></h2>
                <p class="articleContent">
                    <!-- content here -->
                    <?php echo $article['main_points']?>
                </p>
            </div>
        </div>
    </a>
    </div>
    <center>
        <b>
        <a href="delete-article.php?id=<?=$article['id']?>">
        <p id="delete-para" style="color: red;">DELETE ARTICLE</p></a>
        </b>
    </center>
    <?php
    }
    ?>

  </body>
</html>
