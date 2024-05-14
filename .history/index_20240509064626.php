<?php
include "db.php";

$stmt = $con->prepare("INSERT INTO review (bintang, review) VALUES (?, ?)");
$stmt->bind_param("is", $bintang, $review);

// Set parameters and execute
$bintang = $_POST['bintang'];
$review = $_POST['review'];
$stmt->execute();

echo "Review added successfully";

$stmt->close();
$con->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Grand Emporium</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="src/css/style.css" />
    <script src="/fontawesome-main/fontawesome-main/all.js"></script>
    <link rel="stylesheet" href="src/css/style.css">
  </head>
  <body>

    <header class="header">
    <a href="#" class="logo"> Grand<span>Emporium</span> </a>
    <nav class="navbar">
        <a href="#home" class="active">Beranda</a>
        <a href="#about">About</a>
        <a href="#room">Room</a>
        <a href="#gallery">Gallery</a>
        <a href="#abot">Contact</a>

        <?php
        session_start();
        if (isset($_SESSION['user'])) {
         // reservation link -- kalau sudah login
            echo '<a href="reservation/reservation_form.php" class="btn" target="_self">Reservation</a>';
        } else {
         // login link -- kalau belum login
            echo '<a href="session/index.php" class="btn">Login</a>';
        }
        ?>
    </nav>
    <div id="menu-btn" class="fas fa-bars"></div>
</header>
    
      <!-- home -->
      <section class="home" id="home">

      <div class="swiper home-slider">

      <div class="swiper-wrapper">

            <div class="swiper-slide slide" style="background: url(img/home-slide1.webp) no-repeat;">
            <div class="content">
                  <h3>Welcome To Grand Emporium Hotel</h3>
                  <a href="#" class="btn"> visit our offer</a>
            </div>
            </div>

            <div class="swiper-slide slide" style="background: url(img/home-slide2.webp) no-repeat;">
               <div class="content">
                  <h3>Welcome To Grand Emporium Hotel</h3>
                  <a href="#" class="btn"> visit our offer</a>
               </div>
            </div>

            <div class="swiper-slide slide" style="background: url(img/home-slide3.jpeg) no-repeat;">
               <div class="content">
                  <h3>Welcome To Grand Emporium Hotel</h3>
                  <a href="#" class="btn"> visit our offer</a>
               </div>
            </div>

            <div class="swiper-slide slide" style="background: url(img/home-slide4.jpg) no-repeat;">
               <div class="content">
                  <h3>Welcome To Grand Emporium Hotel</h3>
                  <a href="#" class="btn"> visit our offer</a>
               </div>
            </div>

         </div>

         <div class="swiper-button-next"></div>
         <div class="swiper-button-prev"></div>

      </div>

      </section>
      <!--end-->


       <!-- availability -->

   <section class="availability">

      <form action="">

         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" class="input">
         </div>

         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" class="input">
         </div>

         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" id="" class="input">
               <option value="1">1 adults</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>

         <div class="box">
            <p>children <span>*</span></p>
            <select name="child" id="" class="input">
               <option value="1">1 child</option>
               <option value="2">2 child</option>
               <option value="3">3 child</option>
               <option value="4">4 child</option>
               <option value="5">5 child</option>
               <option value="6">6 child</option>
            </select>
         </div>

         <div class="box" >
            <p>rooms <span>*</span></p>
            <select name="rooms" id="" class="input">
               <option value="1">1 rooms</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>

         <!-- <input type="submit" value="check availability" class="btn"> -->
         <a href="reservation/reservation_form.php" class="btn"><br>Check availability</a>

      </form>

   </section>

   <!-- end -->


   <!-- about -->

   <section class="about" id="about">

      <div class="row">

         <div class="image">
            <img src="img/abouts.jpg" alt="">
         </div>

         <div class="content">
            <h3>about us</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione nesciunt blanditiis maxime natus repudiandae veritatis alias laboriosam neque cum! Est adipisci assumenda, ad debitis iusto laudantium repellat aliquam dolore voluptates.</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione nesciunt blanditiis maxime natus repudiandae veritatis alias laboriosam neque cum! Est adipisci assumenda, ad debitis iusto laudantium repellat aliquam dolore voluptates.</p>
         </div>

      </div>

   </section>

   <!-- end -->

   <!-- room -->

   <section class="room" id="room">

      <h1 class="heading">our room</h1>

      <div class="swiper room-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room5.jpg" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room4.jpg" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room3.jpg" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room1.jpg.crdownload" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room2.jpg" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <span class="price">$15.99/night</span>
                  <img src="img/ex-room.jpg.crdownload" alt="">
                  <a href="#" class="fas fa-shopping-cart"></a>
               </div>
               <div class="content">
                  <h3>exclusive room</h3>
                  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente nisi.</p>
                  <div class="stars">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <a href="reservation/reservation_form.php" class="btn">book now</a>
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- end -->

   <!-- services -->

   <section class="services">

      <div class="box-container">

         <div class="box">
            <img src="img/swimmer.png" alt="Swim">
            <h3>swimming pool</h3>
         </div>

         <div class="box">
            <img src="img/dinner.png" alt="Dinner">
            <h3>food & drink</h3>
         </div>

         <div class="box">
            <img src="img/table.png" alt="Restaurant">
            <h3>restaurant</h3>
         </div>

         <div class="box">
            <img src="img/exercise.png" alt="Fitness">
            <h3>fitness</h3>
         </div>

         <div class="box">
            <img src="img/massage.png" alt="Spa">
            <h3>beauty spa</h3>
         </div>

         <div class="box">
            <img src="img/beach-umbrella-and-hammock.png" alt="Resort">
            <h3>resort beach</h3>
         </div>

      </div>

   </section>

   <!-- end -->

   <!-- gallery -->

   <section class="gallery" id="gallery">

      <h1 class="heading">our gallery</h1>

      <div class="swiper gallery-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <img src="img/spageti.jpg" alt="">
               <div class="icon">
                  <i class="fas fa-magnifying-glass-plus"></i>
               </div>
            </div>

            <div class="swiper-slide slide">
               <img src="img/katsudon.jpeg" alt="spagetti">
               <div class="icon">
                  <i class="fas fa-magnifying-glass-plus"></i>
               </div>
            </div>

            <div class="swiper-slide slide">
               <img src="img/steak.jpg" alt="Steak">
               <div class="icon">
                  <i class="fas fa-magnifying-glass-plus"></i>
               </div>
            </div>

            <div class="swiper-slide slide">
               <img src="img/pizza.jpg" alt="">
               <div class="icon">
                  <i class="fas fa-magnifying-glass-plus"></i>
               </div>
            </div>


         </div>

      </div>

   </section>

   <!-- end -->

<section class="location" id="location">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.3881153611287!2d110.35409697595544!3d-7.854386778068241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5640c272c36b%3A0xc74f6e44ceb0382c!2sIwan%20Motor!5e0!3m2!1sid!2sid!4v1698632094363!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> 
</section>

<div class="review" id="review">
<h2>Add Review</h2>
<div class="stars">
            <input type="radio" id="star5" name="star" value="5">
            <label for="star5"></label>
            <input type="radio" id="star4half" name="star" value="4.5">
            <label for="star4half" class="star-half"></label>
            <input type="radio" id="star4" name="star" value="4">
            <label for="star4"></label>
            <input type="radio" id="star3half" name="star" value="3.5">
            <label for="star3half" class="star-half"></label>
            <input type="radio" id="star3" name="star" value="3">
            <label for="star3"></label>
            <input type="radio" id="star2half" name="star" value="2.5">
            <label for="star2half" class="star-half"></label>
            <input type="radio" id="star2" name="star" value="2">
            <label for="star2"></label>
            <input type="radio" id="star1half" name="star" value="1.5">
            <label for="star1half" class="star-half"></label>
            <input type="radio" id="star1" name="star" value="1">
            <label for="star1"></label>
            </div>
            <textarea name="review" placeholder="Write your review here..."></textarea>
        <button type="submit">Submit Review</button>
</div>


<!-- tentang -->
<div class="abot" id="abot">
        <div class="footer">
          <div class="row">
            <div class="footer-col">
              <h4>Ikuti Kami</h4>
              <div class="social-links">
                <a
                  href="https://www.facebook.com/GrandEmporiumHotel"
                  target="_blank"
                  ><i class="fab fa-facebook"></i
                ></a>
                <a href="https://twitter.com/GrandEmporium" target="_blank"
                  ><i class="fab fa-twitter"></i></a>
                <a
                  href="https://www.instagram.com/grandemporiumhotel"
                  target="_blank"
                  ><i class="fab fa-instagram"></i
                ></a>
                <a
                  href="https://api.whatsapp.com/send?phone=+123"
                  target="_blank"
                  ><i class="fab fa-whatsapp"></i
                ></a>
              </div>
            </div>
            <div class="footer-col">
              <h4>Our Company</h4>
              <ul>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Parners</a></li>
                <li><a href="#">Affiliate</a></li>
                <li><a href="#">About Us</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h4>Terms & Policies</h4>
              <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Payment Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Disclaimer</a></li>
                <li><a href="#">Help</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h4>Contact Address</h4>
              <ul>
                <li>
                  <a href="#"
                    ><i class="fa-solid fa-map"></i>45 Harmoni Street, Serenity
                    Town, Bliss City, 56789, Dreamland</a
                  >
                </li>
                <li><a href="#"><i class="fa-solid fa-phone"></i>+6281987654321</a></li>
                <li>
                  <a href="mailto:info@grandemporiumhotel-id.com"
                    ><i class="fa-solid fa-envelope"></i>info@grandemporiumhotel-id.com</a
                  >
                </li>
                <li><a href="#"></a></li>
              </ul>
            </div>
            <div class="footer-col">
              <p>© Grand Emporium Hotel 2023</p>
            </div>
          </div>
        </div>
      </div>
    </div>

      
   <script src="src/js/rating.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="src/js/script.js"></script>
  </body>
</html>




