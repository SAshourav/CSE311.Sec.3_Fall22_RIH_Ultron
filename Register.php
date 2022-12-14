<?php

session_start();

include('server/connection.php');

//if user has already registered then take user to account page

if(isset($_SESSION['logged_in'])){
  header('location: account.php');
  exit;
}

if(isset($_POST['register'])){

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  //if password do not match
  if($password != $confirmPassword){
    header('location: register.php?error=password did not match');

          //if password size is less than 6
  }else if(strlen($password) < 6){
    header('location: register.php?error=password must be at least 6 characters');

          //if there is no error
  }else{
      //check wether there is a user with this email or not
    $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
    $stmt1->bind_param('s',$email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->store_result();
    $stmt1->fetch();

    //if there is a user with this email
    if($num_rows != 0){
      header('location: register.php?error=user with this email already exists');

      //if there is no user with this email
    }else{
                    //create a new user
          $stmt = $conn->prepare("INSERT INTO users (user_name,user_email,user_password)
          VALUES (?,?,?)");

          $stmt->bind_param('sss', $name, $email, md5($password));

          //if account was created successfully
          if($stmt->execute()){
              $_SESSION['user_email'] = $email;
              $_SESSION['user_name'] = $name;
              $_SESSION['logged_in'] = true;
              header('location: account.php?register_success= You registered successfully');

              //if account was not created successfully
          }else{
              header('location:register.php?error=unable to create a account at this moment');
          }
    }

  }

}


?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | Urban</title>
    <!-- CSS only -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
      integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <!-- Here Is the navbar section-->

    <nav class="navbar navbar-expand-lg py-3 bg-white fixed-top">
      <div class="container-fluid">
        <img class="logo" src="assets/imgs/logo.png" alt="">
        <h2 class="brand">The Urban Shop</h2>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="shop.html">Shop</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">Blog</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact Us</a>
            </li>

            <li class="nav-item">
              <a href="cart.html"><i class="fas fa-shopping-bag"></i></a>
              <a href="Account.html"><i class="fas fa-user"></i></a>
            </li>

 
          </ul>
        </div>
      </div>
    </nav>


    <!--Register-->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form action="" id="register-form" method="POST" action="register.php">
              <p style="color: red"><?php if(isset($_GET['error'])){ echo $_GET['error']; } ?></p>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required/>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required/>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required/>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" name="register" id="register-btn" value="Register"/>
                </div>
                <div class="form-group">
                    <a id="login-url" href="login.php" class="btn">Do you have an account? Login</a>
                </div>
            </form>
        </div>
    </section>





         <!--Footer-->

         <footer class="mt-5 py-5">
            <div class="row container mx-auto pt-5">
             <div class="footer-one col-lg-3 col-md-6 col-sm-12">
               <img class="logo" src="assets/imgs/logo.png"/>
               <p class="pt-3">We provide the best products for the most affordable prices</p>
             </div>
             <div class="footer-one col-lg-3 col-md-6 col-sm-12">
               <h5 class="pb-2">Featured</h5>
               <ul class="text-uppercase">
                 <li><a href="#">men</a></li>
                 <li><a href="#">women</a></li>
                 <li><a href="#">boys</a></li>
                 <li><a href="#">girls</a></li>
                 <li><a href="#">new arrivals</a></li>
                 <li><a href="#">clothes</a></li>
               </ul>
             </div>
     
             <div class="footer-one col-lg-3 col-md-6 col-sm-12">
               <h5 class="pb-2">Contact Us</h5>
               <div>
                 <h6 class="text-uppercase">Address</h6>
                 <p>Basundhara R/A , Dhaka</p>
               </div>
               <div>
                 <h6 class="text-uppercase">Phone</h6>
                 <p>123 456 789</p>
               </div>
               <div>
                 <h6 class="text-uppercase">Email</h6>
                 <p>sabbir.ahmed5@northsouth.edu</p>
                 <p>sajid.mahmud7@northsouth.edu</p>
               </div>
             </div>
             <div class="footer-one col-lg-3 col-md-6 col-sm-12">
               <h5 class="pb-2">Instagram</h5>
               <div class="row">
                 <img src="assets/imgs/instagram/bag.jpg" class="img-fluid w-25 h-100 m-2"/>
                 <img src="assets/imgs/instagram/jacket.jpg" class="img-fluid w-25 h-100 m-2"/>
                 <img src="assets/imgs/instagram/shoe.jpg" class="img-fluid w-25 h-100 m-2"/>
                 <img src="assets/imgs/Featured1.jpg" class="img-fluid w-25 h-100 m-2"/>
                 <img src="assets/imgs/Featured2.jpg" class="img-fluid w-25 h-100 m-2"/>
               </div>
             </div>
            </div>
     
     
             <div class="copyright mt-5">
               <div class="row container mx-auto">
                 <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
                  <img src="assets/imgs/payment.jpg"/> 
                 </div>
                 <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2">
                    <p>eCommerce @ 2030 All Right Reserved</p>
                  </div>
                  <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
                   <a href="#"><i class="fab fa-facebook"></i></a>
                   <a href="#"><i class="fab fa-instagram"></i></a>
                   <a href="#"><i class="fab fa-twitter"></i></a>
                   <a href="#"><i class="fab fa-snapchat"></i></a>
                  </div>
               </div>
             </div>
     
          </footer>
     
     
         <!-- JavaScript Bundle with Popper -->
         <script
           src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
           integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
           crossorigin="anonymous"
         ></script>
       </body>
     </html>