<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
    $report_id = $_GET['id'];
    $query = "SELECT * FROM report WHERE id=$report_id";
    $report_result_set = mysqli_query($connection,$query);
    $report = mysqli_fetch_assoc($report_result_set);

?>

<?php 

	// check for form submission
	if (isset($_POST['submit'])) {

		$comments = mysqli_real_escape_string($connection, $_POST['comments']);
		$cleanup_method = mysqli_real_escape_string($connection, $_POST['cleanup-method']);
			// prepare database query
		$query = "UPDATE report SET importance='$cleanup_method', comments='$comments', is_read=1 WHERE id=$report_id";

		$result_set = mysqli_query($connection, $query);

		verify_query($result_set);

    header('Location: report-list.php');
				
	}
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
            font-size: medium;
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
        input {
            display: block;
            width: 100%;
            padding: 5px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 14px;
        }

        select {
            display: block;
            width: 100%;
            padding: 5px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 5px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            background-color: rgb(0,200,0);
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            padding: 0px;
        }
        .image-view{
            margin: 1%;
            flex: 50%;
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
          <span class="date"><?= date('F jS,Y',strtotime($report['date']))?></span>
          <h2><?php echo $report['title']?></h2>

          <p class="articleContent">
            <!-- content here -->
            <?php echo $report['content']?>
          </p><br>

          <?php
            // Include the database configuration file
            include_once 'inc/connection.php';

            // Get images from the database
            $query = $connection->query("SELECT * FROM images WHERE report_id = $report_id");

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

          <br><br>
        </div>
      </div>

      <form action="respond-to-report.php?id=<?=$report_id?>" method="post">
			
			<fieldset>
				<legend><h1>Actions</h1></legend><br>
				<p>
					<label for="comments">Comments : </label>
					<input type="text" name="comments" id="" placeholder="add your comments here">
				</p><br>

				<p>
					<label for="cleanup-method">Required Action : </label>
                    <select name="cleanup-method" id="">
                        <option disabled selected> Select an option</option>
                        <option value="1">Green Flag</option>
                        <option value="2">Red Flag</option>
                        <option value="3">Immediate Cleanup</option>
                    </select>
                </p><br>

				<p>
					<button type="submit" name="submit">Done</button>
                </p>

			</fieldset>

		</form>

</body>
</html>