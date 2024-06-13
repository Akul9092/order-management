<?php 
include ("sqlConnection.php");
require_once('loginRequired.php');
$id = $_SESSION['userid'];
if ($_SESSION['usertype']==1){
    $sql = "SELECT * FROM Admin WHERE Id = '$id'";
    $results = $conn->query($sql);
    $field = $results->fetch_assoc();
    $username = $field["Username"];
}
else{
    $sql = "SELECT * FROM Clients WHERE Client_Id = '$id'";
    $results = $conn->query($sql);
    $field = $results->fetch_assoc();
    $username = $field["Username"];
}
if(isset($_POST["save"])){
    $newusername = filter_input(INPUT_POST, 'newusername');
    $oldusername = filter_input(INPUT_POST, 'oldusername');
    $newpassword = filter_input(INPUT_POST, 'newpassword');
    $cnewpassword = filter_input(INPUT_POST, 'cnewpassword');
    $tempid = $_SESSION["userid"];
    $usernameError = "";
    $passwordError = "";
    $sql = "SELECT COUNT(Username) as checker from Clients where Username = '$newusername'";
    $field = $conn->query($sql)->fetch_assoc();
    $exists = $field['checker'];
    $sql = "SELECT COUNT(Username) as checker2 from Admin where Username = '$newusername'";
    $field = $conn->query($sql)->fetch_assoc();
    $exists = $exists + $field['checker2'];
    if ($exists == 1){
        $usernameError = "Username already exists";
    }
    else if (strlen($newusername) < 4){
        $usernameError = "Username needs to be a minimum of 4 characters";
    }
    else if(strlen($newusername)>24){
        $usernameError = "Username is too long";
    }
    else if ($newusername!=$oldusername){
        switch($_SESSION["usertype"]){
        case 1:
            $sql = "UPDATE Admin SET Username = '$newusername' WHERE Id = $tempid";
            if ($conn->query($sql)){
                $_SESSION["username"] = $newusername;
                $_SESSION["message"] = "Saved Account Details!";
                header("location: home.php");
            }
            else{
                $usernameError = "Connection failed";
            }
            break;
        case 2:
            $sql = "UPDATE Clients SET Username = '$newusername'WHERE Client_Id = $tempid";
            if ($conn->query($sql)){
                $_SESSION["username"] = $newusername;
                $_SESSION["message"] = "Saved Account Details!";
                header("location: home.php");
            }
            else{
                $usernameError = "Connection failed";
            }
            break;
        }
    }
    if($newpassword != $cnewpassword){
        $passwordError = "Passwords do not match";
    }
    else if ($newpassword!=""){
        switch($_SESSION["usertype"]){
        case 1:
            $newpassword = md5($newpassword);
            $sql = "UPDATE Admin SET Password = '$newpassword' WHERE Id = $tempid";
            if ($conn->query($sql)){
                header("location: home.php");
            }
            else{
                $passwordError = "Connection failed";
            }
            break;
        case 2:
            $newpassword = md5($newpassword);
            $sql = "UPDATE Clients SET Password = '$newpassword' WHERE Client_Id = $tempid";
            if ($conn->query($sql)){
                header("location: home.php");
            }
            else{
                $passwordError = "Connection failed";
            }
            break;
        }
    }
    
}
if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION);
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a href="home.php">Home</a>
        <a href="orders.php">Orders</a>
        <a class = "active" href="account.php">Account</a>
        <a href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394" class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <head>
        <title>Account</title>
        <h1>Account</h1>
    </head>
    <body>
        <form action="account.php" method="POST">
            <h4>Change Username</h4><br>
            <?php if (isset($_POST["save"])){echo "<span class='error'>$usernameError</span><br>";} ?>
            <input type="text" name="newusername" placeholder = "New Username" value="<?php echo $username; ?>"><br><br>
            <input type="hidden" name="oldusername" value="<?php echo $username; ?>">
            <h4>Change Password</h4><br>
            <?php if (isset($_POST["save"])){echo "<span class='error'>$passwordError</span><br>";} ?>
            <input type="password" name="newpassword" placeholder="New Password"><br>
            <input type="password" name="cnewpassword" placeholder="Confirm New Password"><br>
            <button type="submit" name="save" value="save">Save</button>
            <button type ="submit" name="logout" value="logout" style="background-color:#323a45; color:white">Logout</button>
        </form>
    </body>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
</html>
