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
    $query = "SELECT * FROM user WHERE id = '{$user_id}' LIMIT 1";

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
            header('Location: users.php?err=user_not_found');
        }
    } else {
        // query unsuccessful
        header('Location: users.php?err=query_failed');
    }
}

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    // checking required fields
    $req_fields = array('user_id', 'first_name', 'last_name', 'email');
    $errors = array_merge($errors, check_req_fields($req_fields));

    // checking max length
    $max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100);
    $errors = array_merge($errors, check_max_len($max_len_fields));

    // checking email address
    if (!is_email($_POST['email'])) {
        $errors[] = 'Email address is invalid.';
    }

    // checking if email address already exists
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $query = "SELECT * FROM user WHERE email = '{$email}' AND id != '{$user_id}' LIMIT 1";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $errors[] = 'Email address already exists';
        }
    }

    if (empty($errors)) {
        // no errors found... adding new record
        $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
        // email address is already sanitized

        $query = "UPDATE user SET ";
        $query .= "first_name = '{$first_name}', ";
        $query .= "last_name = '{$last_name}', ";
        $query .= "email = '{$email}' ";
        $query .= "WHERE id = {$user_id} LIMIT 1";

        $result = mysqli_query($connection, $query);

        if ($result) {
            // query successful... redirecting to users page
            header('Location: homepage.php?user_modified=true');
        } else {
            $errors[] = 'Failed to modify the record.';
        }


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
    <header>
        <br>
        <div class="loggedin">
            <h1>Welcome
                <?php echo $_SESSION['first_name']; ?>!
            </h1>
            <a style="color: red;" href="logout.php">Log Out</a><br><br>
        </div>

        <h3>Edit Your Details Here</h3>
        <hr><br>
        <main>

            <?php

            if (!empty($errors)) {
                display_errors($errors);
            }

            ?>

            <form action="profile.php" method="post" class="userform">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <p>
                    <label for="">First Name:</label>
                    <input type="text" name="first_name" <?php echo 'value="' . $first_name . '"'; ?>>
                </p><br>

                <p>
                    <label for="">Last Name:</label>
                    <input type="text" name="last_name" <?php echo 'value="' . $last_name . '"'; ?>>
                </p><br><br>

                <h3>Change Email</h3>
                <hr><br>


                <p>
                    <label for="">Email Address:</label>
                    <input type="text" name="email" <?php echo 'value="' . $email . '"'; ?>>
                </p><br><br>

                <h3>Change Password</h3>
                <hr><br>
                <p>
                    <label for="">Password:</label>
                    <span>******</span> | <a style="color: red;"
                        href="change-password-profile.php?user_id=<?php echo $user_id; ?>">Change
                        Password</a>
                </p><br>
                <p>
                    <button class="submit_btn" type="submit" name="submit">Save Changes</button>
                </p>

            </form>



        </main>
    </header>
</body>

</html>