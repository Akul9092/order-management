<?php
include ("sqlConnection.php");
require_once('loginRequired.php');
$added = false;
$toAdd = false;
if (isset($_POST['addSlide'])){
    $toAdd = true;
}
else if (isset($_POST['save'])){
    
    $id = filter_input(INPUT_POST, 'id');
    $link = filter_input(INPUT_POST, 'link');
    $caption = filter_input(INPUT_POST, 'caption');
    
    $sql = "UPDATE Gallery SET Link = '$link', Caption = '$caption' WHERE Id=$id";
    if ($conn->query($sql)){  
        header("location: editGallery.php");
    }
}
else if (isset($_POST['edit'])){
    $id = filter_input(INPUT_POST, 'id');
    $sql = "SELECT * FROM Gallery where Id = $id";
    $results = $conn->query($sql);
    $field = $results->fetch_assoc();
}
else if (isset($_POST['add'])){
    $added = true;
    $link = filter_input(INPUT_POST, 'link');
    $caption = filter_input(INPUT_POST, 'caption');
    $sql = "INSERT into Gallery (Link, Caption) VALUES ('$link','$caption')";
    if ($conn->query($sql)){  
        header("location: editGallery.php");
    }
}

?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a href="home.php">Home</a>
        <a href="orders.php">Orders</a>
        <a href="account.php">Account</a>
        <a class ="active" href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394"
           class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <head>
        
        <?php if ($toAdd) {
            echo "<title>Add Slide</title>
        <h1>Add Slide</h1>";
        }
        else{
            echo "<title>Edit Slide</title>
        <h1>Edit Slide</h1>";
        }        
        ?>
        
    </head>
    <form action ="editSlide.php" method="POST">
        <label for ="link">Hyperlink of Image</label><br>
        <input type ="text" id = "link" name ="link" value="<?php if (!$toAdd) echo $field["Link"]?>"><br>
        <label for ="caption">Caption</label><br>
        <input type ="text" id = "caption" name ="caption" value="<?php if(!$toAdd) echo $field["Caption"]?>"><br>
        <?php if (!$toAdd) echo"<input type='hidden' name='id' value='$id'>" ?>
        <?php if($toAdd){ echo "<button type ='submit' name='add' value ='add'>Add</button>";} 
          else{ echo "<button type ='submit' name='save' value ='save'>Save</button>";}?>
    </form>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
</html>
