<?php
include ("sqlConnection.php");
require_once('loginRequired.php');
$currDate = date("Y-m-d");
$minDate = date('Y-m-d', strtotime($currDate. ' + 5 days'));
if ($_SESSION["usertype"] == 2){
    $isClient = true;
}
/*
if ($_SESSION["usertype"] == 2){
    $clientid = $_SESSION["userid"];
    $sql = "SELECT * FROM Orders WHERE Client_Id = $clientid";
    $results = $conn->query($sql);
    if ($results->num_rows == 0){
        $_SESSION["error"] = "You do not have any orders yet!";
    }
    else if ($results->num_rows == 1){
        $field = $results->fetch_assoc();
        
    }
}
*/
if (isset($_POST['edit'])){
    $_SESSION['orderid'] = $tempid = filter_input(INPUT_POST, 'id');
    $sql = "SELECT * FROM Orders where Order_Id = $tempid";
    $field = $conn->query($sql)->fetch_assoc();
    $client_id = $field["Client_Id"];
    $eggless = $field["Eggless"];
    $delivery = $field["Delivery"];
    $weight = $field["Weight"];
    $flavour = $field["Flavour"];
    $icing = $field["Icing"];
    $message = $field["Message"];
    $topping = $field["Topping"];
    $price = $field["Price"];
    $date = $field["Date"];
    
}
function selected($value, $dbValue){
    if ($value == $dbValue){
        echo 'selected';
    }
}
function checkBox($dbValue){
    if ($dbValue == 1){
        echo "checked";
    }
}
function ifClient($usertype){
    if($usertype==2){
        echo "disabled";
    }
}
if (isset($_POST['save'])){
    //value is on when clicked
    $neweggless = filter_input(INPUT_POST, 'eggless');
    $newdelivery = filter_input(INPUT_POST, 'delivery');
    $newweight = filter_input(INPUT_POST, 'weight');
    $newflavour = filter_input(INPUT_POST, 'flavour');
    $newicing = filter_input(INPUT_POST, 'icing');
    $newmessage = filter_input(INPUT_POST, 'message');
    $newtopping = filter_input(INPUT_POST, 'topping');
    if (!$isClient){
        $newprice = filter_input(INPUT_POST, 'price');
    }
    //Y-m-d
    $newdate = filter_input(INPUT_POST, 'date');
    $error = "";
    $value = ',';
    if ($neweggless == "on"){
        $neweggless = 1;
    }
    else{
        $neweggless = 0;
    }
    if ($newdelivery == "on"){
        $newdelivery = 1;
    }
    else{
        $newdelivery = 0;
    }
    if(strlen($newmessage)>100){
        $error="Message is too long";
    }
    else if (strlen($newtopping)>24){
        $error="Topping entered is too long";
    }

    else if(strpos($newtopping, $value)!=false){
        $error="Only one topping is allowed";
    }
    else{
        $error = "db fail";
        $tempid = $_SESSION['orderid'];
        $sql = "UPDATE Orders SET Flavour = '$newflavour', Icing = '$newicing', Topping = '$newtopping', Message = '$newmessage', Eggless = '$neweggless', Delivery = '$newdelivery', Date = '$newdate' WHERE Order_Id = $tempid";        
        if ($conn->query($sql)){
                if (!$isClient){
                    $conn->query("UPDATE Orders SET Price = '$newprice', Weight = '$newweight' where Order_Id = $tempid");
                }
                $error = "db success";
                header("location: orders.php");
            }        
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
        <a href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394" class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <head>
        <title>Edit Orders</title>
        <h1>Edit Order</h1>
    </head>
    
    <body>
        <?php echo "<span class = 'error'>$error</span>"; ?><br>
        <form action="editOrders.php" method="POST">
            <label>Client:</label><br>
            <select name ='client' id='client' placeholder='Client Name' required>
            
                <?php
                if (!$isClient){
                    $sql = "SELECT * from Clients";
                    $results = $conn->query($sql);
                    while($field = $results->fetch_assoc()){
                        $client = $field['Username'];
                        $id = $field['Client_Id'];
                        if ($client_id == $id){
                            echo "<option value = $id selected disabled >$client</option>";
                        }
                        else {
                            echo "<option value = $id disabled >$client</option>";
                        }
                    }
                }
                else{
                    $sql = "SELECT * from Clients where Client_Id = '$client_id'";
                    $field = $conn->query($sql)->fetch_assoc();
                    $client = $field['Username'];
                    echo "<option value = $id selected >$client</option>";
                }
                ?>
            </select><br>      
            <input type="checkbox" id="checkbox1" name="eggless" <?php checkBox($eggless)?>>
            <label for="checkbox1"> Eggless</label>
            <input type="checkbox" id="checkbox2" name="delivery" <?php checkBox($delivery)?>>            
            <label for="checkbox2"> Delivery</label><br>
            <label for='weight'>Weight:</label><br>
            <?php
            if (!$isClient){
                echo "<input type='range' class = 'slider' name ='weight' id='weight' min='1' max='9' step='0.5' value ='$weight' oninput='weightOutput.value = weight.value'><br>";
                echo "<output name ='weightOutput' id='weightOutput' style='color: #34a1eb'>$weight</output><br>";
            }
            else{
                echo "<output name ='weightOutput' id='weightOutput'style='color: #34a1eb'>$weight</output><br>";
            }
            
            ?>
            
            <label for='flavour'>Flavour:</label>
            <br>
            <img id = "decorLeft" src ="https://tatyanaseverydayfood.com/wp-content/uploads/2018/02/Strawberry-Tuxedo-Cake-4.jpg" height ="400"/>
            <select name ='flavour' id ='flavour'>
                <optgroup label="Normal">
                <option value = '1' <?php selected(1,$flavour)?>>Chocolate Sponge</option>
                <option value = '2'<?php selected(2,$flavour)?>>Black Forest</option>
                <option value = '3'<?php selected(3,$flavour)?>>Vanilla</option>
                <option value = '4'<?php selected(4,$flavour)?>>Strawberry</option>
                <option value = '5'<?php selected(5,$flavour)?>>Mango</option>
                <option value = '6'<?php selected(6,$flavour)?>>Butterscotch</option>
                </optgroup>
                <optgroup label="Signature">
                    <option value = '7'<?php selected(7,$flavour)?>>Chocolate Mud</option>
                    <option value = '8'<?php selected(8,$flavour)?>>Pineapple</option>
                    <option value = '9'<?php selected(9,$flavour)?>>Rasmalai</option>
                    <option value = '10'<?php selected(10,$flavour)?>>Tiramisu</option>
                </optgroup>
            </select>
            <img id = "decorRight" src ="https://www.justsotasty.com/wp-content/uploads/2017/10/Red-Velvet-Cake-4.jpg" height ="400"/><br>
            <label for='icing'>Icing:</label><br>
            <select name="icing" id="icing">
                <option value ='1'<?php selected(1,$icing)?>>Fresh Cream Vanilla</option>
                <option value ='2'<?php selected(2,$icing)?>>Chocolate Ganache</option>
                <option value ='3'<?php selected(3,$icing)?>>Cream Cheese Frosting</option>
                <option value ='4'<?php selected(4,$icing)?>>Fresh Cream Frosting</option>
                <option value ='5'<?php selected(5,$icing)?>>Fondant</option>
            </select><br>
            <label for='topping'>Topping:</label><br>
            <input type ='text' name ='topping' placeholder='Topping' value='<?php echo $topping; ?>'><br>
            <label for='message'>Message:</label><br>
            <input type ='text' name='message' placeholder='Message' required value='<?php echo $message; ?>'><br>
            <label for='date'>Due Date:</label><br>
            <input type='date' name='date' id='date' min='<?php echo $minDate?>' required value='<?php echo $date; ?>'><br>
            <label for='price'>Price:</label><br>
            <input type ="number" name="price" placeholder="Price" value="<?php echo $price ?>" <?php if ($isClient) echo "disabled"; ?>><br>
            <button type="submit" name = "save" value="save">Save</button>
        </form>
        
        <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
    </body>
</html>