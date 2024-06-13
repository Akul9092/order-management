<?php
include("sqlConnection.php");
class userAccount{
    private $username;
    private $password;
    public function userAccount($u, $p){
        $this->username = $u;
        $this->password = $U;
                
    }
    public function setUsername($u): void{
        $this->username = $u;
    }
    public function setPassword($p): void{
        $this->password = $p;
    }
    public function getUsername(){
        return $this->username;
    }
    public function getPassword(){
        return $this->password;
    }
}

if (isset($_POST['login'])){
    //$user = new $userAccount();
    //$user->setUsername(filter_input(INPUT_POST, 'username'));
    //$user->setPassword(filter_input(INPUT_POST, 'password'));
    //$username = filter_input(INPUT_POST, 'username');
    //$password = filter_input(INPUT_POST, 'password');
    $error = "";
    $user = new userAccount(filter_input(INPUT_POST, 'username'), filter_input(INPUT_POST, 'password'));
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    //$user = new userAccount;
    //$user->setUsername(filter_input(INPUT_POST, 'username'));
    //$user->setPassword(filter_input(INPUT_POST, 'password'));
    if ($username == "" || $password == ""){
        $error = "Please fill in all fields";
        header("location: login.php");
    }
    //$username = $user->getUsername();
    $sql = "SELECT * FROM Admin WHERE Username='$username'";
    $field = $conn->query($sql)->fetch_assoc();
    print($field);
    
    if (strcmp($field['Username'], $username) == 0){
        $password = md5($password);
        if ($password == $field['Password']){
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $field["Id"];
            $_SESSION['usertype'] = 1;   
            $error = "Success";
            header("location: home.php");
        }
        else{
            $error = "Incorrect Password";
        }
        
    }else{
        $sql = "SELECT * FROM Clients WHERE Username='$username'";
        $field = $conn->query($sql)->fetch_assoc();
        if (strcmp($field['Username'], $username) == 0){
        $password = md5($password);
            if ($password == $field['Password']){
                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userid'] = $field["Id"];
                $_SESSION['usertype'] = 2;   
                $error = "Success";
                header("location: home.php");
            }
            else{
                $error = "Incorrect Password";
            }
        }
        else{
            $error = "Could not find account in database";
        }
    }
     
}
     
     
?>

<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a class = "active" href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
    <head>
        <title>Login</title>
    <h1>Login</h1>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <form action="login.php" method="POST">
            <?php echo "<span class = 'error'>$error</span>"; ?><br>
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <a href ="register.php">Register</a><br>
            <button type="submit" name = "login" value="login">Login</button>
        </form>
        </div>
    </body>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
</html>
