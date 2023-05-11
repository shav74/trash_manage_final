<?php session_start(); ?>

<?php  
if(isset($_POST['submit'])){ 

    include_once 'inc/connection.php';
    include_once 'inc/functions.php';

    $content = $_POST['content'];
    $title = $_POST['title'];
    $main_points = $_POST['main_points'];

	$errors = array();

		// check if the username and password has been entered
	if (!isset($_POST['title']) || strlen(trim($_POST['title'])) < 1 ) {
		$errors[] = 'Title is Missing / Invalid';
	}

	if (!isset($_POST['content']) || strlen(trim($_POST['content'])) < 1 ) {
		$errors[] = 'Content is Missing / Invalid';
	}

    if (!isset($_POST['main_points']) || strlen(trim($_POST['main_points'])) < 1 ) {
		$errors[] = 'Content is Missing / Invalid';
	}

    if (empty($errors)) {
        $query = "INSERT INTO article (title,main_points,content) VALUES ('$title','$main_points','$content')";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);

        $query = "SELECT * FROM article WHERE id = (SELECT MAX(ID) FROM article)";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            $report_details = mysqli_fetch_assoc($result_set);
            $article_id = $report_details['id'];
        }


        $targetDir = "final/uploads/" ; 
        $allowTypes = array('jpg','png','jpeg','gif'); 

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
     
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
    if(!empty($fileNames)){ 
        foreach($_FILES['files']['name'] as $key=>$val){ 
            // File upload path 
            $fileName = basename($_FILES['files']['name'][$key]); 
            $targetFilePath = $targetDir . $fileName; 
             
            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if(in_array($fileType, $allowTypes)){ 
                // Upload file to server 
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                    // Image db insert sql 
                    // $insertValuesSQL .= "('".$fileName."', NOW()),'".$report_id."'"; 
                    $insertValuesSQL .= "('$fileName', NOW(), '$article_id'),";
                }else{ 
                    $errorUpload .= $_FILES['files']['name'][$key].' | '; 
                } 
            }else{ 
                $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
            } 
        } 
         
        // Error message 
        $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
        $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
        $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
         
        if(!empty($insertValuesSQL)){ 
            // $insertValuesSQL = trim($insertValuesSQL, ','); 
            $insertValuesSQL = rtrim($insertValuesSQL, ',');
            // Insert image file name into database 
            // $insert = $connection->query("INSERT INTO images (file_name, uploaded_on,report_id) VALUES $insertValuesSQL");
            // $insert = $connection->query("INSERT INTO images (file_name, uploaded_on, report_id) VALUES ($insertValuesSQL)"); 
            $insert = $connection->query("INSERT INTO image (file_name, uploaded_on, article_id) VALUES $insertValuesSQL");

            if($insert){ 
                $statusMsg = "Files are uploaded successfully.".$errorMsg; 
            }else{ 
                $statusMsg = "Sorry, there was an error uploading your file."; 
            } 
        }else{ 
            $statusMsg = "Upload failed! ".$errorMsg; 
        } 
    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
        
        header('Location: homepage.php');
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
    <link
      rel="stylesheet"
      href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"
    />
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
        .submit_btn{
            background-color: rgb(0, 200, 0);
            color: white;
        }
        #content-des{
            font-size: small;
            /* background-color: yellow; */
            /* border-radius: 4px; */
        }
        #image-des{
            font-size: small;
            /* background-color: yellow; */
            /* border-radius: 4px; */
        }
        fieldset{
            padding: 2%;
            margin: 2%;
        }
        #image-btn{
            margin-top: 1%;
            color: red;
        }
        .error{
            color: red;
            font-size: small;
        }
    </style>
  </head>
  <body>
  <?php include_once('inc/nav.php')
//   ?loc_id=<?=$location['loc_id']
?>
  <form action="write-article.php" method="post" enctype="multipart/form-data">
        <fieldset>
			<legend><h1>Write Report</h1></legend>
                <?php 
					if (isset($errors) && !empty($errors)) {
						echo '<p class="error">Invalid Title/Contet</p><br>';
					}
				?>
                <p>
                    <label for="title">Article Title:</label>
                    <input type="text" name="title" id="" placeholder="Enter Article title">
                </p><br>
                <p>
                    <label for="main_points">Article Summary:</label>
                    <input type="text" name="main_points" id="" placeholder="Enter Article main points">
                </p><br>
                <p>
                    <label for="content">Article Content:</label>
                    <input type="text" name="content" id="" placeholder="Enter Article content">
                </p><br>

                <label for="files">Images of location:</label>
                <p id="image-des">Upload pictures of the location, use clear and relevent photos of the location</p>
                <input type="file" name="files[]" multiple id="image-btn"><br>
                <input type="submit" name="submit" value="SUBMIT" class="submit_btn">
        </fieldset>
</form>
  </body>
</html>
