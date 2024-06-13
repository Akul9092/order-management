<?php
include ("sqlConnection.php");
require_once('loginRequired.php');

$currDate = date("Y-m-d");
$minDate = date('Y-m-d', strtotime($currDate. ' + 5 days'));

if (isset($_POST['add'])){
    if ($isClient){
        $client_id = $_SESSION['userid'];
    }
    else{
        $client_id = filter_input(INPUT_POST, 'client');
    }
    //value is on when clicked
    $eggless = filter_input(INPUT_POST, 'eggless');
    $delivery = filter_input(INPUT_POST, 'delivery');
    $weight = filter_input(INPUT_POST, 'weight');
    $flavour = filter_input(INPUT_POST, 'flavour');
    $icing = filter_input(INPUT_POST, 'icing');
    $message = filter_input(INPUT_POST, 'message');
    $topping = filter_input(INPUT_POST, 'topping');
    $date = filter_input(INPUT_POST, 'date');
    $error = "";
    $value = ',';
    
    if ($eggless == "on"){
        $eggless = 1;
    }
    else{
        $eggless = 0;
    }
    if ($delivery == "on"){
        $delivery = 1;
    }
    else{
        $delivery = 0;
    }
    if(strlen($message)>100){
        $error="Message is too long";
    }
    else if (strlen($topping)>24){
        $error="Topping entered is too long";
    }

    else if(strpos($topping, $value)!=false){
        $error="Only one topping is allowed";
    }
    else{
        $price = $weight * 45 + 20;
        $error = "Successful";
        $sql = "INSERT into ORDERS(Client_Id, Flavour, Icing, Price, Weight, Topping, Message, Eggless, Delivery, Date) VALUES "
                . "('$client_id', '$flavour', '$icing', '$price', '$weight', '$topping', '$message', '$eggless', '$delivery', '$date') ";
        if ($conn->query($sql)){
                header("location: orders.php");
            }
    }    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
        <div class ="topnav">
            <a href ="home.php">Home</a>
            <a href='orders.php'>Orders</a>  
            <a href="account.php">Account</a>
            <a href="gallery.php">Gallery</a>
            <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394"
               class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
        </div>
        <title>Add Orders</title>
        <h1>Add Order</h1>
    </head>
    <body>
            <form action="addOrders.php" method="POST">
        <?php
            echo "<span class = 'error middle'>$error</span><br>";
            if (!$isClient){
                echo "<label>Client:</label><br>";
                echo "<select name ='client' id='client' placeholder='Client Name' required>";
                $sql = "SELECT * from Clients";
                $results = $conn->query($sql);
                while($field = $results->fetch_assoc()){
                    $client = $field['Username'];
                    $id = $field['Client_Id'];
                    echo "<option value = $id>$client</option>";
                }
            }
            
                ?>
            </select><br>      
            <label for="checkbox1"> Eggless</label>
            <input type="checkbox" id="checkbox1" name="eggless">
            <span class="checkmark"></span>
            <label for="checkbox2"> Delivery</label>
            <input type="checkbox" id="check2" name="delivery">     
            <span class="checkmark"></span>
            <br>
            <label for='weight'>Weight:</label><br>
            <input class = "slider" type='range' name ='weight' id='weight' min='1' max='9' step='0.5' value ='1' oninput="weightOutput.value = weight.value"><br>
            <output name ='weightOutput' id='weightOutput' style="color: #34a1eb">1</output><br>
            <img id = "decorLeft" src =" https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fstatic.onecms.io%2Fwp-content%2Fuploads%2Fsites%2F34%2F2019%2F07%2F12170640%2Fitalian-meringue-buttercream-d108976.jpg&q=85" height ="300"/>
            <select name ='flavour' id ='flavour' required>
                <option value="" selected disabled hidden>Flavour</option>
                <optgroup label="Normal">
                <option value = '1'>Chocolate Sponge</option>
                <option value = '2'>Black Forest</option>
                <option value = '3'>Vanilla</option>
                <option value = '4'>Strawberry</option>
                <option value = '5'>Mango</option>
                <option value = '6'>Butterscotch</option>
                </optgroup>
                <optgroup label="Signature">
                    <option value = '7'>Chocolate Mud</option>
                    <option value = '8'>Pineapple</option>
                    <option value = '9'>Rasmalai</option>
                    <option value = '10'>Tiramisu</option>
                </optgroup>
            </select>    
            <img id ="decorRight" src ="https://i.pinimg.com/originals/ce/9c/ba/ce9cba7cab13f6cd5d1a157120bd78b1.jpg" height="300"/>
            <br>
            
            <select name="icing" id="icing" required>
                <option value="" selected disabled hidden>Icing</option>
                <option value ='1'>Fresh Cream Vanilla</option>
                <option value ='2'>Chocolate Ganache</option>
                <option value ='3'>Cream Cheese Frosting</option>
                <option value ='4'>Fresh Cream Frosting</option>
                <option value ='5'>Fondant</option>
            </select>
            <br>
            <input type ='text' name ='topping' placeholder='Topping'><br>
            <input type ='text' name='message' placeholder='Message'><br>
            <label for='date'>Due Date:</label><br>
            <input type='date' name='date' id='date' min='<?php echo $minDate?>' required><br>
            
            <button type="submit" name = "add" value="add">Add</button> 
            <!--
            <button onclick="getLocation()">Current Location</button>
            
            
            <div id="map"></div>
            <script>
// Initialize and add the map
function initMap() {
  // The location of Uluru
  var home = {lat: 1.297723 , lng: 103.871397};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 12, center: home});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: home, map: map, draggable:true});
  var lat = marker.getPosition().lat()
  var lng = marker.getPosition().lng()
  document.getElementById("lat").value = lat;
  document.getElementById("lng").value = lng;
}

var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    coords = 
    var lat = 
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIggwDe5veNOCkqpcQNX6-JQgbyS133qk&callback=initMap">
    </script>
            !-->
        </form>
        <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
    </body>
</html>

<!Chocolate sponge cake 
Chocolate mud cake ( signature cake )
Black Forest cake with blue berry pie filling 
Fresh cream pineapple cake ( signature cake )
Vanilla  cake 
Mango cream cake made with mango purée and  layered with Alphonso mango filling 
Eggless tiramisu cake ( signature cake )
Butterscotch cake made with home made butterscotch sauce ans homemade almond praline

Frostings 
Fresh cream vanilla 
Chocolate ganache 
Cream cheese frosting 
Fresh cream frosting is the most commonly used frosting followed by 
chocolate ganache which is used on sponge cakes as well as in fondant cakes 
as it doesn’t melt at room temperature

"INSERT into ORDERS(Client_Id, Flavour, Icing, Price, Weight, Topping, Message, Eggless, Delivery, Date) "
. "VALUES ($client_id, $flavour, $icing, $price, $weight, $topping, $message, $eggless, $delivery, $date) ";
>