<?php
$servername = "localhost:8889";
$db = "Client_Management";
$dbusername = "iauser";
$fb_logo = "https://cdn.icon-icons.com/icons2/1826/PNG/512/4202110facebooklogosocialsocialmedia-115707_115594.png";
$dbpassword = "password";
$size = 200;
$conn = new mysqli($servername, $dbusername, $dbpassword, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);      
}
session_start();
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
?>

