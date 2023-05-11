<?php session_start(); ?>

<?php

if (isset($_POST['submit'])) {

    include_once 'inc/connection.php';
    include_once 'inc/functions.php';

    $location_id = $_GET['loc_id'];
    $reporter_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $title = $_POST['title'];
    $report_id = null;


    $errors = array();

    // check if the username and password has been entered
    if (!isset($_POST['title']) || strlen(trim($_POST['title'])) < 1) {
        $errors[] = 'Title is Missing / Invalid';
    }

    if (!isset($_POST['content']) || strlen(trim($_POST['content'])) < 1) {
        $errors[] = 'Content is Missing / Invalid';
    }

    $query = "SELECT * FROM location WHERE loc_id = '$location_id'";
    $result_set = mysqli_query($connection, $query);

    verify_query($result_set);

    if (mysqli_num_rows($result_set) == 1) {
        $location = mysqli_fetch_assoc($result_set);
    }

    $location_name = $location['loc_name'];
    $longitude = $location['longitude'];
    $latitude = $location['latitude'];

    if (empty($errors)) {
        $query1 = "INSERT INTO report (loc_id,reporter_id,title,content,loc_name,longitude,latitude) VALUES ($location_id,$reporter_id,'$title','$content','$location_name','$longitude','$latitude')";

        $result_set1 = mysqli_query($connection, $query1);

        verify_query($result_set1);

        $query = "SELECT * FROM report WHERE id = (SELECT MAX(ID) FROM report)";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $report_details = mysqli_fetch_assoc($result_set);
            $report_id = $report_details['id'];
        }

        $repIDquery = "INSERT INTO images(report_id) VALUES ($report_id)";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);
        header("Location: homepage.php");

    }


    // File upload configuration
    $targetDir = "final/uploads/";
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
    $fileNames = array_filter($_FILES['files']['name']);
    if (!empty($fileNames)) {
        foreach ($_FILES['files']['name'] as $key => $val) {
            // File upload path 
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;

            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server 
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
                    // Image db insert sql 
                    // $insertValuesSQL .= "('".$fileName."', NOW()),'".$report_id."'"; 
                    $insertValuesSQL .= "('$fileName', NOW(), '$report_id'),";
                } else {
                    $errorUpload .= $_FILES['files']['name'][$key] . ' | ';
                }
            } else {
                $errorUploadType .= $_FILES['files']['name'][$key] . ' | ';
            }
        }

        // Error message 
        $errorUpload = !empty($errorUpload) ? 'Upload Error: ' . trim($errorUpload, ' | ') : '';
        $errorUploadType = !empty($errorUploadType) ? 'File Type Error: ' . trim($errorUploadType, ' | ') : '';
        $errorMsg = !empty($errorUpload) ? '<br/>' . $errorUpload . '<br/>' . $errorUploadType : '<br/>' . $errorUploadType;

        if (!empty($insertValuesSQL)) {
            // $insertValuesSQL = trim($insertValuesSQL, ','); 
            $insertValuesSQL = rtrim($insertValuesSQL, ',');
            // Insert image file name into database 
            // $insert = $connection->query("INSERT INTO images (file_name, uploaded_on,report_id) VALUES $insertValuesSQL");
            // $insert = $connection->query("INSERT INTO images (file_name, uploaded_on, report_id) VALUES ($insertValuesSQL)"); 
            $insert = $connection->query("INSERT INTO images (file_name, uploaded_on, report_id) VALUES $insertValuesSQL");

            if ($insert) {
                $statusMsg = "Files are uploaded successfully." . $errorMsg;
                header("Location: new-report.php");
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $statusMsg = "Upload failed! " . $errorMsg;
        }
    } else {
        $statusMsg = 'Please select a file to upload.';
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navStyle.css" />
    <link rel="stylesheet" href="css/mainStyle.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <style>
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
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

        .submit_btn {
            background-color: rgb(0, 200, 0);
            color: white;
        }

        #content-des {
            font-size: small;
            /* background-color: yellow; */
            /* border-radius: 4px; */
        }

        #image-des {
            font-size: small;
            /* background-color: yellow; */
            /* border-radius: 4px; */
        }

        fieldset {
            padding: 2%;
            margin: 2%;
        }

        #image-btn {
            margin-top: 1%;
            color: red;
        }

        .error {
            color: red;
            font-size: small;
        }
    </style>
</head>

<body>
    <?php include_once('inc/nav.php') ?>
    <form action="write-report.php?loc_id=<?php echo $_GET['loc_id'] ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>
                <h1>Write Report</h1>
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
            <input type="file" name="files[]" multiple id="image-btn"><br>
            <input type="submit" name="submit" value="SUBMIT" class="submit_btn">
        </fieldset>
    </form>
</body>

</html>