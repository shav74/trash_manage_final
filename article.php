<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
    $article_id = $_GET['id'];
    $query = "SELECT * FROM article WHERE id=$article_id";
    $result_set = mysqli_query($connection,$query);
    $article = mysqli_fetch_assoc($result_set);

    $imageQuery = "SELECT * FROM image WHERE article_id=$article_id";
    $imageResults = mysqli_query($connection,$imageQuery);
    $images = mysqli_fetch_assoc($imageResults);
?>

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
        body{
            padding-left: 5%;
            padding-right: 5%;
        }
        h2{
            margin-bottom: 20px;
        }
        .date{
            font-size: small;
            background: rgb(220, 0, 0);
            border-radius: 3px;
        }
        .articleContent{
            font-size: small;
        }
        .summary{
          background:rgb(224, 221, 130);
          border-radius: 5px;
          padding: 1%;
        }
        .article-image{
          width: 100%;
          max-width: 400px;
          height: auto;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            padding: 0px;
        }
        .image-view{
            margin: 1%;
            flex: 50%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
            white-space: normal;
            overflow: hidden;
        }
    </style>
</head>
<body>
<?php include_once('inc/nav.php')?>

<div class="card">
        <div class="card-text-article">
          <span class="date"><?= date('F jS,Y',strtotime($article['date']))?></span>
          <h2><?php echo $article['title']?></h2>

          <?php
            // Include the database configuration file
            include_once 'inc/connection.php';

            // Get images from the database
            $query = $connection->query("SELECT * FROM image WHERE article_id = '$article_id'");

            if($query->num_rows > 0){
                while($row = $query->fetch_assoc()){
                    $imageURL = "final/uploads/".$row["file_name"];
            ?>  
              <div class="image-container">
                <img class="image-view" src="<?php echo $imageURL; ?>" alt="" />
              </div>
            <?php }
            }else{ ?>
                <p>No image(s) found...</p>
           <?php } ?>

          <h3>Quick Summary</h3>
          <div class="summary">
            <h5><?php echo $article['main_points']?></h3>
          </div>
          <br>
          <p class="articleContent">
            <!-- content here -->
            <?php echo $article['content']?>
          </p>
        </div>
      </div>
</body>
</html>