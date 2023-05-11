<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/functions.php');

if (isset($_POST['submit'])) {

    $errors = array();

    // check if the details has been entered correctliy
    //validating
    if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {
        $errors[] = 'Username is Missing / Invalid';
    }

    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {
        $errors[] = 'Password is Missing / Invalid';
    }

    // check if there are any errors in the form then display is theres any...
    //TODO 
    if (empty($errors)) {
        // save username and password into variables securely hre
        //sanitizing data
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $hashed_password = sha1($password);

        // prepare database query to insert get time form now()
        $query = "SELECT * FROM user 
						WHERE email = '{$email}' 
						AND password = '{$hashed_password}' 
						LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);

        if (mysqli_num_rows($result_set) == 1) {
            // valid user found
            $user = mysqli_fetch_assoc($result_set);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['user_type'] = $user['user_type'];

            $query = "UPDATE user SET last_login = NOW() ";
            $query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

            $result_set = mysqli_query($connection, $query);

            verify_query($result_set);

            echo "<h1> login success</h1>";

            // redirect to home
            header("Location: homepage.php");
        }
    } else {
        echo "<h1> login failed</h1>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>testadddata</title>
</head>

<body>
    <form action="test-input-data.php" method="post">
        email<input type="text" name="email"><br>
        pass<input type="password" name="password"><br>
        <button type="submit" name="submit">SUBMIT</button>
    </form>
</body>

</html>