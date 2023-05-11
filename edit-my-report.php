<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
$report_id = $_GET['id'];
$query = "SELECT * FROM report WHERE id=$report_id";
$report_result_set = mysqli_query($connection, $query);
$report = mysqli_fetch_assoc($report_result_set);

if (isset($_POST['submit'])) {

    $content = $_POST['content'];
    $title = $_POST['title'];

    $errors = array();

    // check if the username and password has been entered
    if (!isset($_POST['title']) || strlen(trim($_POST['title'])) < 1) {
        $errors[] = 'Title is Missing / Invalid';
    }

    if (!isset($_POST['content']) || strlen(trim($_POST['content'])) < 1) {
        $errors[] = 'Content is Missing / Invalid';
    }

    if (empty($errors)) {
        $query1 = "UPDATE report SET title='$title',content='$content' WHERE id=$report_id";

        $result_set1 = mysqli_query($connection, $query1);

        verify_query($result_set1);

        $query = "DELETE FROM images WHERE report_id = $report_id";
        $result_set = mysqli_query($connection, $query);
        verify_query($result_set);

        $repIDquery = "INSERT INTO images(report_id) VALUES ($report_id)";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);
        header('Location: homepage.php');
    }
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
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <script src="js/navjs.js" defer></script>
    <title>Articles</title>
    <style>
        body {
            padding-left: 5%;
            padding-right: 5%;
        }

        h2 {
            margin-bottom: 20px;
        }

        .date {
            font-size: small;
            background: rgb(220, 0, 0);
            border-radius: 3px;
        }

        .articleContent {
            font-size: medium;
        }

        .summary {
            background: rgb(224, 221, 130);
            border-radius: 5px;
            padding: 1%;
        }

        .article-image {
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
            background-color: rgb(0, 200, 0);
        }

        #delete-report a {
            border-radius: 4px;
            padding: 1%;
            background: red;
        }

        .submit_btn {
            background-color: rgb(0, 200, 0);
            color: white;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly
        }

        .image-view {
            margin: 1%;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.6);
            white-space: normal;
            overflow: hidden;
            max-width: 400px;
        }
    </style>
</head>

<body>
    <?php include_once('inc/nav.php') ?>

    <div class="card">
        <div class="card-text-article">
            <span class="date">
                <?= date('F jS,Y', strtotime($report['date'])) ?>
            </span>
            <h1>
                <?php echo $report['title'] ?>
            </h1>

            <?php
            // Include the database configuration file
            include_once 'inc/connection.php';

            // Get images from the database
            $query = $connection->query("SELECT * FROM images WHERE report_id = $report_id");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $imageURL = "final/uploads/" . $row["file_name"];
                    ?>
                    <div class="image-container">
                        <img class="image-view" src="<?php echo $imageURL; ?>" alt="" />
                    </div>
                <?php }
            } else { ?>
                <p>No image(s) found...</p>
            <?php } ?>
            <br>
            <p class="articleContent">
                <!-- content here -->
                <?php echo $report['content'] ?>
            </p><br>
        </div>
    </div>

    <form action="edit-my-report.php?id=<?= $report_id ?>" method="post">

        <fieldset>
            <legend>
                <h1>Edit Report</h1>
            </legend>
            <?php
            if (isset($errors) && !empty($errors)) {
                echo '<p class="error">Invalid Title/Contet</p><br>';
            }
            ?>
            <p>
                <label for="title">Report Title:</label>
                <input type="text" name="title" id="" placeholder="Enter repor title">
            </p><br>
            <p>
                <label for="content">Report Content:</label>
            <p id="content-des">explain the impact such as attracting wildlife, terrible smell, etc...</p>
            <input type="text" name="content" id="" placeholder="Enter content here">
            </p><br>

            <label for="files">Images of location:</label>
            <p id="image-des">Upload pictures of the location, use clear and relevent photos of the location</p>
            <input type="file" name="files1[]" multiple id="image-btn"><br>
            <input type="submit" name="submit" value="SUBMIT" class="submit_btn">
        </fieldset>

    </form>
    <br>

    <p id="delete-report">
        <a href="delete-report.php?id=<?= $report_id ?>" onclick="return confirm('Are you sure?')">Delete this
            Report</a>
    </p>
</body>

</html>