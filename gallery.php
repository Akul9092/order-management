<?php
include ("sqlConnection.php");
require_once('loginRequired.php');
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
        <title>Gallery</title>
        <h1>Gallery</h1>
    </head>
    <body>
        <?php
        if (!$isClient){
            echo"<a href ='editGallery.php'><button class ='addOrder' style = 'font-size: 22px;'><b>Edit Gallery</b></button></a>";
        }
        ?>
        <div class ="slideshow">
             <?php
             $sql = "SELECT * From Gallery where notGallery = 0";
             $total = $conn->query("SELECT COUNT(Id) as total from Gallery where notGallery =  0")->fetch_assoc()['total'];
             $results = $conn->query($sql);
             $count = 1;
             while($field = $results->fetch_assoc()){
                 echo "<div class = 'mySlides'>";
                 echo "<div class = 'index'>$count / $total</div>";
                 echo "<img src=".$field["Link"]." class='center-img'>";
                 echo "<div class = 'text'>".$field["Caption"]."</div>";
                 echo "</div>";
                 $count++;
             }
             ?>
        </div>
        <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>


  <!-- Thumbnail images
  <div class="row">
    <div class="column">
      <img class="demo cursor" src="img_woods.jpg" style="width:100%" onclick="currentSlide(1)" alt="The Woods">
    </div>
    <div class="column"> 
      <img class="demo cursor" src="img_5terre.jpg" style="width:100%" onclick="currentSlide(2)" alt="Cinque Terre">
    </div>
    <div class="column">
      <img class="demo cursor" src="img_mountains.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">
    </div>
    <div class="column">
      <img class="demo cursor" src="img_lights.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">
    </div>
    <div class="column">
      <img class="demo cursor" src="img_nature.jpg" style="width:100%" onclick="currentSlide(5)" alt="Nature and sunrise">
    </div> 
    <div class="column">
      <img class="demo cursor" src="img_snow.jpg" style="width:100%" onclick="currentSlide(6)" alt="Snowy Mountains">
    </div>
  </div>
  -->
</div>
    </body>
    <footer class = "center">
            <img src ="Nurture Cakes.jpg" height="<?php echo $size ?>" width ="<?php echo $size ?>"/>
        </footer>
    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        // Next/previous controls
        function plusSlides(n) {
          showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
          showSlides(slideIndex = n);
        }

        function showSlides(n) {
          var i;
          var slides = document.getElementsByClassName("mySlides");
          var dots = document.getElementsByClassName("dot");
          if (n > slides.length) {slideIndex = 1} 
          if (n < 1) {slideIndex = slides.length}
          for (i = 0; i < slides.length; i++) {
              slides[i].style.display = "none"; 
          }
          for (i = 0; i < dots.length; i++) {
              dots[i].className = dots[i].className.replace(" active", "");
          }
          slides[slideIndex-1].style.display = "block"; 
          dots[slideIndex-1].className += " active";
        }
    </script>
</html>
    