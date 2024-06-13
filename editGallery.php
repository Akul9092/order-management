<?php
include ("sqlConnection.php");
require_once('loginRequired.php');

if (isset($_POST['delete'])){
    $id = filter_input(INPUT_POST, 'id');
    $sql = "DELETE from Gallery where Id = $id ";
    if ($conn->query($sql)){
        $status = "Deleted slide";
        $statusDone = false;
    }
}
//if (isset)
?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a href="home.php">Home</a>
        <a href="orders.php">Orders</a>
        <a href="account.php">Account</a>
        <a class ="active" href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394" class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <head>
        <title>Edit Gallery</title>
        <h1>Edit Gallery</h1>
    </head>
    <body>
        <form action ="editSlide.php" method="POST">         
            <a href ='editSlide.php'><button type = 'submit' value = 'addSlide' name = 'addSlide' class ='addOrder'><b>Add</b></button></a>
        </form>
        <?php
        
        if (!$statusDone){
            echo "<p style='color:#00FF00'>".$status.'</p>'; 
            $statusDone = true;
        }
        ?>
        <table>
        <tr>
            <th>Index</th>
            <th>Image</th>
            <th>Caption</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        $sql = "SELECT * FROM Gallery where notGallery = 0";
        $results = $conn->query($sql);
        $count = 1;
        while($field = $results->fetch_assoc()){
            $id = $field["Id"];
            $link = $field["Link"];
            echo "<tr>";
            echo '<td>'.$count.'</td>';
            echo "<td><img src = '$link' class = 'galleryImage' height='200'></td>";
            echo '<td>'.$field["Caption"].'</td>';
            echo "<td><form action='editSlide.php' method='POST'>";
            echo "<input type = 'hidden' name = 'id' value = '$id'>";
            echo "<button type='submit' name='edit'><img src='https://image.flaticon.com/icons/svg/45/45250.svg' height = '10' width = '10'/></button>";
            echo "</form></td>";
            echo "<td><form action = 'editGallery.php' method = 'POST'>";
            echo "<input type = 'hidden' name = 'id' value = '$id'>";
            echo "<button type ='submit' name='delete' style = 'background-color:red'>"
            . "<img src='https://cdn4.iconfinder.com/data/icons/linecon/512/delete-512.png'height = '20' width = '20' /></button>";
            echo "</form></td>";
            echo "</tr>";
            $count++;
        }
        ?>
        </table>
    </body>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
</html>