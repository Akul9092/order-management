<?php
include ("sqlConnection.php");
require_once('loginRequired.php');

if (isset($_POST['editLink'])){
    $link = filter_input(INPUT_POST, 'link');
    $sql = "UPDATE Gallery SET Link = '$link' where Id = 4";
    $conn->query($sql);
}
$sql = "SELECT Link from Gallery where notGallery = 1";
$link = $conn->query($sql)->fetch_assoc()['Link'];
?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    
    <div class ="topnav">
        <a class ="active" href ="home.php">Home</a>
        <a href='orders.php'>Orders</a>  
        <a href="account.php">Account</a>
        <a href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394" class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <body>    
        <?php
        $message = $_SESSION['message'];
        echo "<p style='color:#00FF00'>".$message.'<p>';
        $_SESSION['message'] = "";
        //https://www.w3schools.com/howto/howto_js_alert.asp
        //Code below for Reminder
        if (!$isClient && $_SESSION['reminded'] == false){
            $currDate = date("Y-m-d");
            $minDate = date('Y-m-d', strtotime($currDate. ' + 4 days'));
            $sql = "SELECT * FROM Orders WHERE Date <= '$minDate' AND Pending = '1' ORDER BY Date  ASC";
            $results = $conn->query($sql);
            if($results->num_rows >= 1){
                echo "<div class = 'alert' id ='alert'>";
                echo "<span id = 'close' class='closebtn'>&times;</span>";
                echo "<b> The following orders are nearing their deadline: </b><br>";
                while ($field = $results->fetch_assoc()){
                    $currDate = date("Y-m-d");
                    $client = getClientUsername($field['Client_Id']);
                    $dueDate = $field['Date'];
                    $diff = date_diff($currDate, $dueDate);
                    //$daysLeft = date_diff($currDate, $dueDate, TRUE);
                    echo "$client's Order<br>";
                    echo "Date: ".date("M jS Y", strtotime($dueDate))."<br>";
                    echo "Days Left: ";
                    $dueDate = strtotime($dueDate);
                    $currDate = strtotime($currDate);
                    echo ($dueDate - $currDate)/60/60/24;
                    echo "<br>";
                    echo "<br>";
                }
                echo "</div>";
                $_SESSION['reminded'] = true;
            }
        }
        ?>
        <div class ="center">
            <button class = "main-right" onclick="location.href='account.php'"style = "background-color: #6eacff;">Account</button>
            <button class = "main-right" onclick="location.href='orders.php'"style = "background-color: #6eacff;">Orders</button>
            <button class = "main-right" onclick="location.href='gallery.php'" style = "background-color: #6eacff;">Gallery</button>
        </div>
        <?php 
        if(!$isClient){
            echo "<button class='openForm' onclick='unHideForm()'>Change Thumbnail</button>
            
        <div class = 'popupform' id ='popUp'>
            <form action ='home.php' method ='POST'>
                
                <input type='text' name='link' placeholder='Enter new link here'><br>
                <button type ='submit' value='editLink' name='editLink'>Save</button><br>
                <button type = 'button' class ='openForm' onclick='hideForm()' 
                style = 'background-color: #ff0000;'>Hide</button>
            </form>
        </div>";
        }
        ?>
        
        <div class ="center">
        <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>  
        <div class="fb-post" 
            data-href="<?php echo $link ?>"></div>
        </div>
        <br>
        
    </body>
        <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
    <script>
            var alert = document.getElementById('alert');
            var close = document.getElementsByClassName("closebtn")[0];
            close.onclick = function(){
                alert.style.display = "none";
            }
            function unHideForm(){
                document.getElementById("popUp").style.display = "block";
            }
            function hideForm(){
                document.getElementById("popUp").style.display = "none";
            }
        </script>
</html>

