<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php
// checking if a user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$errors = array();
$user_id = '';
$first_name = '';
$last_name = '';
$email = '';

if (isset($_GET['user_id'])) {
    // getting the user information
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
    $query = "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            // user found
            $result = mysqli_fetch_assoc($result_set);
            $first_name = $result['first_name'];
            $last_name = $result['last_name'];
            $email = $result['email'];
        } else {
            // user not found
            header('Location: homepage.php?err=user_not_found');
        }
    } else {
        // query unsuccessful
        header('Location: homepage.php?err=query_failed');
    }
}

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // checking required fields
    $req_fields = array('user_id', 'password');
    $errors = array_merge($errors, check_req_fields($req_fields));

    // checking max length
    $max_len_fields = array('password' => 40);
    $errors = array_merge($errors, check_max_len($max_len_fields));

    if (empty($errors)) {
        // no errors found... adding new record
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashed_password = sha1($password);

        $query = "UPDATE user SET ";
        $query .= "password = '{$hashed_password}' ";
        $query .= "WHERE id = {$user_id} LIMIT 1";

        $result = mysqli_query($connection, $query);

        if ($result) {
            // query successful... redirecting to users page
            header('Location: homepage.php?user_modified=true');
        } else {
            $errors[] = 'Failed to update the password.';
        }

    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navStyle.css" />
    <link rel="stylesheet" href="css/mainStyle.css" />
    <script src="js/navjs.js" defer></script>

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <style>
        body {
            margin-left: 5%;
            margin-right: 5%;
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
            padding-left: 3%;
            padding-right: 3%;
            padding-top: 0.75%;
            padding-bottom: 0.75%;
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
    <br>

    <header>
        <div class="loggedin">
            <h1>Welcome
                <?php echo $_SESSION['first_name']; ?>!
            </h1> <a href="logout.php">Log Out</a>
        </div>
    </header>

    <main>
        <h3>Change Password<span></span></h3><br>

        <?php

        if (!empty($errors)) {
            display_errors($errors);
        }

        ?>

        <form action="change-password-profile.php" method="post" class="userform">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <p>
                <label for="">First Name:</label>
                <input type="text" name="first_name" <?php echo 'value="' . $first_name . '"'; ?>>
            </p><br>

            <p>
                <label for="">Last Name:</label>
                <input type="text" name="last_name" <?php echo 'value="' . $last_name . '"'; ?>>
            </p><br>

            <p>
                <label for="">Email Address:</label>
                <input type="text" name="email" <?php echo 'value="' . $email . '"'; ?>>
            </p><br>

            <p>
                <label for="">New Password:</label>
                <input type="password" name="password" id="password">
            </p><br>

            <p>
                <label for="">Show Password:</label>
                <input type="checkbox" name="showpassword" id="showpassword" style="width:20px;height:20px">
            </p><br>

            <p>
                <label for="">&nbsp;</label>
                <button class="submit_btn" type="submit" name="submit">Update Password</button>
            </p>

        </form>



    </main>
    <script src="js/jquery.js"></script>
    <script>
        $(document).ready(function () {
            $('#showpassword').click(function () {
                if ($('#showpassword').is(':checked')) {
                    $('#password').attr('type', 'text');
                } else {
                    $('#password').attr('type', 'password');
                }
            });
        });
    </script>
</body>

</html>