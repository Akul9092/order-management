<?php
include ("sqlConnection.php");
require_once('loginRequired.php');

$flavour = array("Chocolate Sponge", "Black Forest", "Vanilla", "Strawberry", "Mango", "Butterscotch", "Chocolate Mud", "Pineapple", "Rasmalai", "Tiramisu");
$icing = array("Fresh Cream Vanilla", "Chocolate Ganache", "Cream Cheese Frosting", "Fresh Cream Frosting", "Fondant");

/*
if ($_SESSION["usertype"] == 2){
    $isClient = true;
}
else{
    $isClient = false;
}
function convertToCross($value){
    if($value==1){
        return ✖;
    }
}
function getClientUsername($id){
    $sql = "SELECT * from Clients where Client_Id = '$id'";
    $servername = "localhost:8889";
    $db = "Client_Management";
    $dbusername = "iauser";
    $dbpassword = "password";
    $conn = new mysqli($servername, $dbusername, $dbpassword, $db);
    $result =  $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row["Username"];
}
 * 
 */
if (isset($_POST['done'])){
    $orderId = filter_input(INPUT_POST, 'id');
    $sql = "UPDATE Orders SET Pending = 0 WHERE Order_Id = $orderId";
    if ($conn->query($sql)){
        $status = "Successfully marked an order as complete!";
        $statusDone = false;
    }
}
?>
<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="styles.css">
    <div class="topnav">
        <a href="home.php">Home</a>
        <a class ="active" href="orders.php">Orders</a>
        <a href="account.php">Account</a>
        <a href="gallery.php">Gallery</a>
        <a href="https://www.facebook.com/Nurture-Cakes-Gourmet-cakes-with-egg-and-eggless-cakes-168960999882394" class="fb"><img src="<?php echo $fb_logo ?>" height="13" width="13"></a>
    </div>
    <head>
        <title>Orders</title>
        <h1>Pending Orders</h1>
    </head>
    <body>
        
        <p>
            
            <a href ='addOrders.php'><button class ='addOrder'><b>Add Order</b></button></a>
            <?php
            if (!$isClient){
                echo "<div class = 'stats'>";
                echo "<h3>Statistics</h3>";
                $result = $conn->query("SELECT COUNT(Pending) as numPending from Orders where Pending = 1");
                $field = $result->fetch_assoc();
                $numPending = $field['numPending'];
                $result = $conn->query("SELECT COUNT(Pending) as total from Orders");
                $field = $result->fetch_assoc();
                $totalNum = $field['total'];
                $numCompleted = $totalNum - $numPending;
                echo "Pending Orders <span style = 'color:#FF0000'>$numPending</span><br>";
                echo "Completed Orders <span style = 'color:#00FF00'>$numCompleted</span><br>";
                echo "Total Orders <span style = 'color:#0000FF'>$totalNum</span>";  
                echo "</div>";
            }       
            ?>
        </p>
        <?php
        if (!$statusDone){
            echo "<p style='color:#00FF00'>".$status.'<p>'; 
            $statusDone = true;
        }
        ?>
        <table>
            <tr>
                <th>Date</th>
                <?php
                if (!$isClient){
                    echo "<th>Client</th>";
                }                
                ?>
                <th>Weight</th>
                <th>Flavour</th>
                <th>Icing</th>
                <th>Topping</th>
                <th>Message</th>
                <th>E</th>
                <th>D</th>
                <th>Price</th>
            </tr>
            <?php
            if ($isClient){
                $id = $_SESSION["userid"];
                $sql = "SELECT * from Orders where Pending = 1 and Client_Id = $id ORDER BY Date ASC"; 
            }
            else{
                $sql = "SELECT * from Orders where Pending = 1 ORDER BY Date ASC";               
            }

            $results = $conn->query($sql);
            while($field = $results->fetch_assoc()) {
                echo "<tr>";
                echo '<td>'.$field["Date"].'</td>';
                $id = $field["Client_Id"];
                if (!$isClient){
                    echo '<td>'.getClientUsername($id).'</td>';
                }
                echo '<td>'.$field["Weight"].'</td>';
                echo '<td>'.$flavour[$field["Flavour"] - 1].'</td>';
                echo '<td>'.$icing[$field["Icing"] - 1].'</td>';
                echo '<td>'.$field["Topping"].'</td>';
                echo '<td>'.$field["Message"].'</td>';
                echo '<td>'.convertToCross($field["Eggless"]).'</td>';
                echo '<td>'.convertToCross($field["Delivery"]).'</td>';
                echo '<td>'.$field["Price"].'</td>';
                echo "<td> <form action='editOrders.php' method='POST'>";
                $orderId = $field["Order_Id"];
                echo "<input type='hidden' name='id' value='$orderId'>";
                echo "<button type='submit' name='edit'><img src='https://image.flaticon.com/icons/svg/45/45250.svg' "
                . "height = '10' width = '10'/></button>";
                echo "</form> </td>";
                if (!$isClient){
                    echo "<td> <form action='orders.php' method='POST'>";
                    echo "<input type='hidden' name='id' value='$orderId'>";
                    echo "<button style = 'background-color: #0ffc03' type ='submit' name='done'>"
                    . "<img src='https://2bece72nsw461mtqvm1bba91-wpengine.netdna-ssl.com/wp-content/uploads/2015/01/checkmark.png'"
                            . "height = '10' width = '15' /></button>";
                    echo  "</form> </td>";
                    echo "</tr>";
                }
            }
            ?>            
        </table>
        <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
    </body>
</html>
