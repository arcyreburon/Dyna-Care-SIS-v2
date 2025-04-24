<!DOCTYPE html>
<html lang="en">

<head>
  <title>DynaCareSIS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="assets/img/dynaa.png" rel="icon" class="rounded" alt="Rounded Image">

  <link href="https://fonts.googleapis.com/css?family=Rubik:400,700|Crimson+Text:400,400i" rel="stylesheet">
  <link rel="stylesheet" href="pharma/fonts/icomoon/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="pharma/css/bootstrap.min.css">
  <link rel="stylesheet" href="pharma/css/magnific-popup.css">
  <link rel="stylesheet" href="pharma/css/jquery-ui.css">
  <link rel="stylesheet" href="pharma/css/owl.carousel.min.css">
  <link rel="stylesheet" href="pharma/css/owl.theme.default.min.css">


  <link rel="stylesheet" href="pharma/css/aos.css">

  <link rel="stylesheet" href="pharma/css/style.css">

</head>

<body>

  <div class="site-wrap" style="background: #F4F1EB; border-bottom: 1px solid #ddd;">

<!-- Navbar with anchor links -->
<div class="sticky py-2 site-navbar" style="background: #fff; border-bottom: 2px solid #ddd;">
  <div class="d-flex align-items-center justify-content-between container" style="padding: 10px 20px;">
    
    <!-- Logo Section -->
    <div class="d-flex align-items-center site-logo">
      <img src="assets/img/dynaa.png" alt="Logo" style="margin-right: 10px; border-radius: 50%; width: 50px; height: 50px; object-fit: cover;">
      <a href="#home" class="fw-bold text-dark fs-4">DCSIS</a>
    </div>

    <!-- Navigation Links -->
    <div class="site-nav">
      <ul class="d-flex nav">
        <li class="nav-item">
          <a href="#home" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">HOME</a>
        </li>
        <li class="nav-item">
          <a href="#products" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">PRODUCTS</a>
        </li>
        <li class="nav-item">
          <a href="#about" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">ABOUT US</a>
        </li>
        <li class="nav-item">
          <a href="#contact" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">CONTACT</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<style>
  /* Ensure navbar is always on screen */
  .sticky {
    position: sticky;
    top: 0;
    z-index: 1000; /* Ensure it stays above other content */
    transition: all 0.3s ease; /* Smooth transition for all properties */
  }

  /* Styling for a more modern navbar */
  .site-navbar {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease; /* Smooth transition for all properties */
  }
  
  /* Apply to Bootstrap navigation links */
  .site-nav .nav-item .nav-link {
    font-size: 1rem;
    font-weight: 500;
    transition: color 0.3s ease, transform 0.2s ease; /* Smooth color and scale transition */
  }

  /* Hover effect */
  .site-nav .nav-item .nav-link:hover {
    color: #FF69B4 !important; /* Pink hover color with !important to override Bootstrap styles */
    text-decoration: none;
    transform: scale(1.1); /* Slight scale-up effect */
  }

  /* Active link animation (when clicked) */
  .site-nav .nav-item .nav-link:active {
    transform: scale(0.95); /* Slight scale-down on click */
    transition: transform 0.1s ease;
  }

  /* Responsive Design: Mobile Navigation */
  @media (max-width: 768px) {
    .site-nav {
      display: none;
    }
    .site-navbar {
      padding: 10px;
    }
    .site-nav.active {
      display: block;
      position: absolute;
      top: 60px;
      left: 0;
      right: 0;
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      z-index: 999;
      transition: all 0.3s ease; /* Smooth transition for all properties */
    }
    .site-nav .nav {
      flex-direction: column;
      align-items: center;
    }
    .site-nav .nav-item .nav-link {
      padding: 15px 20px;
      font-size: 1.2rem;
    }
  }
</style>



<!-- Optional: Add a toggle button for mobile view -->
<button class="btn btn-light d-md-none" id="navbar-toggler" style="border-radius: 50%;"><i class="mdi mdi-menu"></i></button>

<script>
  // Toggle mobile navbar visibility
  document.getElementById('navbar-toggler').addEventListener('click', function() {
    const nav = document.querySelector('.site-nav');
    nav.classList.toggle('active');
  });
</script>

<div id="home" class="site-blocks-cover" style="background-color: #F6F6F6; position: relative; padding-top: 20px; padding-left: 7rem; padding-right: 7rem;">
  <video autoplay muted loop id="background-video" class="background-video" disablePictureInPicture>
    <source src="assets/video/DYNACARE.mp4" type="video/mp4">
  </video>
  <div class="container-fluid">
    <div class="row">
      <!-- Carousel Section -->
      <div id="products" class="col-lg-6 col-md-6 col-sm-12" style="padding-top: 12rem;">
        <div class="carousel-1">
          <div>
            <div class="content-1">
              <h2 style="font-style: 'Poppins'; font-weight: 700;">Biogesic</h2>
              <span>Paracetamol</span>
            </div>
          </div>
          <div>
            <div class="content-1">
              <h2 style="font-style: 'Poppins'; font-weight: 700;">Neozep®Forte</h2>
              <span>Paracetamol</span>
            </div>
          </div>
          <div>
            <div class="content-1">
              <h2 style="font-style: 'Poppins'; font-weight: 700;">Moxylor</h2>
              <span>Amoxicillin</span>
            </div>
          </div>
          <div>
            <div class="content-1">
              <h2 style="font-style: 'Poppins'; font-weight: 700;">Indoplas</h2>
              <span>Face Mask</span>
            </div>
          </div>
        </div>
      </div>
      <!-- Text Content Section -->
      <div class="col-lg-6 col-md-6 col-sm-12" style="padding-top: 18rem; padding-right: 10rem; text-align: right;">
        <div class="align-self-center order-lg-2 mx-auto">
          <div class="site-block-cover-content text-center" style="position: relative; z-index: 1;">
            <h1 style="text-align: right; font-family: 'Poppins'; font-weight:900; font-style: normal; font-size: 80px; color:black;">DynaCareSIS</h1>
            <h1 style="text-align: right; font-family: 'Poppins'; font-weight:700; font-style: normal; font-size: 50px; white-space: nowrap; color: black; margin-top: -30px;">Health Solutions</h1>
            <h2 class="sub-title" style="text-transform: none; !important text-align: right; !important align-item: right; !important font-family: 'Poppins'; font-weight:400; font-style: normal; font-size: 22px; letter-spacing: 307; white-space: nowrap; color: black;margin-top: -30px; text-align: right;">Effective Medicine, New Medicine Everyday</h2>
            <p style="text-align: right;">
              <br>
              <a href="login.php" class="mt-3 px-5 py-3 btn btn-primary custom-button" style="font-weight: 700; border-radius:50px; text-align: right; !important">LOGIN</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Poppins';
    font-weight: 400;
  }

  .site-blocks-cover {
    position: relative;
  }

  .background-video {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    top: 0;
    left: 0;
  }

  .carousel-1 {
    width: 100%;
    display: flex;
    justify-content: center;
    height: 500px;
    gap: 10px;

    > div {
      flex: 0 0 120px;
      border-radius: 0.5rem;
      transition: 0.5s ease-in-out;
      cursor: pointer;
      box-shadow: 1px 5px 15px #1e0e3e;
      position: relative;
      overflow: hidden;

      &:nth-of-type(1) {
        background: url("assets/img/carousel/biogesic-paracetamol.png") no-repeat 50% / cover;
      }
      &:nth-of-type(2) {
        background: url("assets/img/carousel/neozep-forte-paracetamol.png") no-repeat 50% / cover;
      }
      &:nth-of-type(3) {
        background: url("assets/img/carousel/moxylor-amo.png") no-repeat 50% / cover;
      }
      &:nth-of-type(4) {
        background: url("assets/img/carousel/indoplas-mask.png") no-repeat 50% / cover;
      }

      .content-1 {
        font-size: 1.5rem;
        color: #fff;
        display: flex;
        align-items: center;
        padding: 15px;
        opacity: 0;
        flex-direction: column;
        height: 100%;
        justify-content: flex-end;
        background: rgb(255, 190, 206);
        background: linear-gradient(0deg, rgb(255, 175, 182) 0%, rgba(255, 255, 255, 0) 30%);
        transform: translateY(100%);
        transition: opacity 0.5s ease-in-out, transform 0.5s 0.2s;
        visibility: hidden;

        span {
          display: block;
          margin-top: 5px;
          font-size: 1.2rem;
        }
      }

      &:hover {
        flex: 0 0 250px;
        box-shadow: 1px 3px 15px #7645d8;
        transform: translateY(-30px);
      }

      &:hover .content-1 {
        opacity: 1;
        transform: translateY(0%);
        visibility: visible;
      }
    }
  }

  .custom-button {
    background-color: #FFB6C1; /* Light pink background */
    border-color: #FFB6C1; /* Light pink border */
    color: black; /* Initial text color */
    transition: background-color 0.3s, border-color 0.3s, color 0.3s; /* Smooth transition for hover effect */
  }

  .custom-button:hover,
  .custom-button:focus,
  .custom-button:active {
    background-color: #FFB6C1; /* Light pink on hover */
    border-color: #FFB6C1; /* Light pink border on hover */
    color: white !important; /* Change text color to white on hover */
  }

</style>

<!-- Products Section -->
<div class="bg-light py-5 site-section">
  <div class="container">

<!-- About Us Section -->
<div id="about" class="bg-light py-5 site-section">
  <div class="container">
    <div class="justify-content-center row">
      <div class="text-center col-12 title-section">
        <h2 style="font-size: 3rem;">ABOUT US</h2>
      </div>
    </div>

    <div class="row"">
      <!-- Left Side: Text Content -->
      <div class="col-lg-6" style="text-align: center !important;">
        <div class="p-4" style="margin-left: 20px; margin-right: 20px;">
          <h3 class="mb-3 text-dark">Who We Are</h3>
          <p class="text-dark" style="font-size: 18px; line-height: 1.8; margin-bottom: 20px;">
            We are a leading company dedicated to providing high-quality products and services. 
            With years of industry experience, we focus on innovation, customer satisfaction, 
            and sustainable growth. Our team is composed of passionate professionals committed to 
            excellence and integrity.
          </p>
        </div>
      </div>
      <!-- Right Side: Image -->
      <div class="col-lg-6" style="text-align: center !important;">
            <h3 class="mt-4 text-dark">Our Mission</h3>
          <p class="text-dark" style="font-size: 18px; line-height: 1.8; margin-bottom: 20px;">
            Our mission is to create solutions that make life easier and businesses more efficient. 
            We believe in building strong relationships with our clients, founded on trust and reliability. 
            Join us on our journey to make a meaningful impact in the world.
          </p>
      </div>
    </div>
  </div>
</div>



<!-- Contact Section -->
<div id="contact" class="bg-light py-5 site-section">
  <div class="container">
    <div class="justify-content-center row">
      <div class="text-center col-12 title-section">
        <h2 style="font-size: 3rem;">CONTACT US</h2>
      </div>
    </div>

    <div class="text-center row">
      <!-- Address -->
      <div class="mb-4 col-md-4">
        <div class="bg-white shadow p-4 rounded contact-box">
          <i class="mdi-map-marker text-primary mdi" style="font-size: 28px;"></i>
          <h5 class="mt-3">Our Address</h5>
          <p class="text-muted">17.650069, 120.414504, Santo Domingo, Lalawigan ng Ilocos Sur</p>
        </div>
      </div>

      <!-- Email -->
      <div class="mb-4 col-md-4">
        <div class="bg-white shadow p-4 rounded contact-box">
          <i class="text-primary mdi mdi-email" style="font-size: 28px;"></i>
          <h5 class="mt-3">Email Us</h5>
          <p class="text-muted"><a href="mailto:dynacare.sis@gmail.com" class="text-decoration-none">dynacare.sis@gmail.com</a></p>
        </div>
      </div>

      <!-- Phone -->
      <div class="mb-4 col-md-4">
        <div class="bg-white shadow p-4 rounded contact-box">
          <i class="text-primary mdi mdi-phone" style="font-size: 28px;"></i>
          <h5 class="mt-3">Call Us</h5>
          <p class="text-muted"><a href="tel:+9193686141" class="text-decoration-none">+919 368 6141</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  a {
    color: rgb(255, 129, 160);
  }

  a:hover{
    color: rgb(255, 129, 160);
  }
</style>



<!-- Footer Section -->
<footer class="bg-dark py-4 text-white text-center footer" style="border-top: 3px solid rgb(255, 152, 178);">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <p class="mb-2">&copy; DynaCareSIS System 2025. All Rights Reserved.</p>
        
        <!-- Social Media Icons -->
        <div class="mt-2 social-icons">
          <a href="#" class="mx-2 text-white"><i class='bx bxl-facebook-circle'></i></a>
          <a href="#" class="mx-2 text-white"><i class='bx bxl-twitter'></i></a>
          <a href="#" class="mx-2 text-white"><i class='bx bxl-instagram-alt'></i></a>
          <a href="#" class="mx-2 text-white"><i class='bx bxl-tiktok'></i></a>
        </div>
        
        <p class="mt-2 text-muted small">Designed by <b>Arcy Mae Christian S. Reburon<b></p>
      </div>
    </div>
  </div>
</footer>


  </div>

  <script src="pharma/js/jquery-3.3.1.min.js"></script>
  <script src="pharma/js/jquery-ui.js"></script>
  <script src="pharma/js/popper.min.js"></script>
  <script src="pharma/js/bootstrap.min.js"></script>
  <script src="pharma/js/owl.carousel.min.js"></script>
  <script src="pharma/js/jquery.magnific-popup.min.js"></script>
  <script src="pharma/js/aos.js"></script>

  <script src="pharma/js/main.js"></script>

  <style>
    * {
      font-family:'Poppins';
    }
    .product-img {
      width: 200px;
      height: 200px;
      object-fit: contain; /* Ensures the entire image fits inside without stretching */
      background-color: transparent; /* Removes background */
      transition: transform 0.3s ease-in-out;
    }

    .product-img:hover {
      transform: scale(1.1);
    }

    .owl-nav {
      display: none; /* Hides navigation arrows */
    }

    .owl-dots {
      margin-top: 15px;
    }

    .owl-dot span {
      width: 12px;
      height: 12px;
      margin: 5px;
      background: #ddd !important;
      border-radius: 50%;
    }

    .owl-dot.active span {
      background:rgb(255, 185, 226) !important;
    }

    html {
      scroll-behavior: smooth;
    }


    h2 {
      color: white;
      margin: 0;
      font-size: 28px;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes floatUp {
      0% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
      100% {
        transform: translateY(0);
      }
    }

    .nav-link {
      position: relative;
      transition: all 0.3s ease-in-out;
    }

    .nav-link:hover {
      background: linear-gradient(to right,rgb(184, 209, 255),rgb(255, 195, 215));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      color:rgb(255, 79, 164);
      transition: color 0.2s ease-in-out;
      border: 2px solidrgb(255, 169, 210);
      border-radius: 5px;
      padding: 5px;
    }

    .nav-link:active {
      transform: scale(1.1);
      color: #FF99CB;
    }
  </style>

<!-- Owl Carousel JavaScript -->
 <!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
  $(document).ready(function(){
    $(".medicine-carousel, .supplies-carousel").owlCarousel({
      loop: true,
      margin: 20,
      dots: true,
      autoplay: true,
      autoplayTimeout: 2500, // Speed: Change slides every 2.5 seconds
      autoplayHoverPause: false, // Keeps moving even when hovered
      responsive: {
        0: { items: 1 },
        600: { items: 2 },
        1000: { items: 3 }
      }
    });
  });
</script>

</body>

</html>

<!-- <div class="row">
      <div class="text-center col-12 title-section">
        <div class="title-box">
          <h2 class="px-5 py-3 text-uppercase btn btn-primary">Medicines</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 products-wrap">
        <div class="owl-carousel medicine-carousel">
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/product_01.jpg" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Paracetamol</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/product_02.png" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Cough Syrup</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/product_03.avif" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Vitamin C</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/product_04.jpg" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Antibiotics</a></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 row">
      <div class="text-center col-12 title-section">
        <div class="title-box">
          <h2 class="px-5 py-3 text-uppercase btn btn-success">Supplies</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 products-wrap">
        <div class="owl-carousel supplies-carousel">
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/supply_01.jpg" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Face Masks</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/supply_02.jpg" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Gloves</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/syringe.webp" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Syringes</a></h3>
          </div>
          <div class="text-center item">
            <a href="#"> 
              <img src="pharma/images/supply_04.jpg" alt="Image" class="product-img">
            </a>
            <h3 class="mt-3 text-dark"><a href="#">Bandages</a></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->