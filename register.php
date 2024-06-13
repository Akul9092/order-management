<?php
    include("sqlConnection.php");
    if (isset($_POST['register'])){

    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $cpassword = filter_input(INPUT_POST, 'cpassword');
    $error = "";
    $sql = "SELECT COUNT(Username) as checker from Clients where Username = '$username'";
    $field = $conn->query($sql)->fetch_assoc();
    $exists = $field['checker'];
    $sql = "SELECT COUNT(Username) as checker2 from Admin where Username = '$username'";
    $field = $conn->query($sql)->fetch_assoc();
    $exists = $exists + $field['checker2'];
    if ($username=="" || $password=="" || $cpassword==""){
        $error = "Please fill in all fields";
    }
    else if ($exists == 1){
        $error = "Username already exists in database";
    }
    else if (strlen($username) > 24){
        $error = "Username is too long";
    }
    else if (strlen($username) < 4){
        $error = "Username is too short";
    }
    else if ($password != $cpassword){
        $error = "Passwords do not match";
    }
    else
         {

        $password = md5($password);
        $sql = "INSERT into Clients(Username, Password) VALUES ('$username', '$password')";
        if (!$conn->query($sql)){
                $error = "Unable to add to database";
            }
        $_SESSION['username'] = $username;
        $_SESSION['usertype'] = 2;
        header("location: home.php");
    //}
    }
    }
    
?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a href="login.php">Login</a>
        <a class = "active" href="register.php">Register</a>
    </div>
    <head>
        <title>Register</title>     
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Register</h1>
        <div>
            <form method="POST" action="register.php">
            <?php echo "<span class = 'error'>$error</span>"; ?><br>
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="password" name="cpassword" placeholder ="Confirm Password"><br>
            <button type="submit" name="register" value="Register">Register</button>
        </form>
        </div>
    </body>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
</html>
