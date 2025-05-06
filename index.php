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

  <!-- Favicon -->
  <link href="assets/img/dynaa.png" rel="icon" class="rounded" alt="Rounded Image">
<!-- Fonts  -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Flowbite CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fff1f2',
              100: '#ffe4e6',
              200: '#fecdd3',
              300: '#fda4af',
              400: '#fb7185',
              500: '#f43f5e',
              600: '#e11d48',
              700: '#be123c',
              800: '#9f1239',
              900: '#881337',
            },
            secondary: {
              50: '#f8fafc',
              100: '#f1f5f9',
              200: '#e2e8f0',
              300: '#cbd5e1',
              400: '#94a3b8',
              500: '#64748b',
              600: '#475569',
              700: '#334155',
              800: '#1e293b',
              900: '#0f172a',
            },
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
        }
      }
    }
  </script>

  <style>
    .gradient-text {
      background: linear-gradient(90deg, #f43f5e, #fb7185);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    
    .team-member:hover .team-overlay {
      opacity: 1;
      transform: translateY(0);
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
  </style>


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
      <a href="#home" class="fw-bold text-dark fs-4">DynaCareSIS</a>
    </div>

    <!-- Navigation Links -->
    <div class="site-nav">
      <ul class="d-flex nav">
        <li class="nav-item">
          <a href="#home" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">HOME</a>
        </li>
        <li class="nav-item">
          <a href="#about" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">ABOUT US</a>
        </li>
        <li class="nav-item">
          <a href="#contact" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">CONTACT</a>
        </li>
        <li class="nav-item">
          <a href="login.php" class="px-3 text-dark nav-link" style="letter-spacing: 5px; font-size: 18px; font-weight: 500; !important">LOGIN</a>
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

<!-- Background Video -->
<video autoplay muted loop id="background-video" class="background-video" disablePictureInPicture>
    <source src="assets/video/DYNACARE.mp4" type="video/mp4">
</video>

<!-- Performance limit-->
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          let video = document.getElementById("background-video");

          // Check system performance using available RAM (navigator.deviceMemory)
          let deviceMemory = navigator.deviceMemory || 4; // Defaults to 4GB if unknown

          if (deviceMemory < 4) {
              video.pause(); // Pause video for lower-end laptops
          } else {
              video.play(); // Play video for high-end laptops
          }
      });
  </script>

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
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="right-text order-lg-2 mx-auto">
          <div class="site-block-cover-content" style="position: relative; z-index: 1; text-align: right;">
            <h1 style="font-family: 'Poppins'; font-weight:900; font-style: normal; font-size: 80px; color:black; margin-left: auto;">DynaCareSIS</h1>
            <h1 style="font-family: 'Poppins'; font-weight:700; font-style: normal; font-size: 50px; white-space: nowrap; color: black; margin-top: -30px; margin-left: auto;">Health Solutions</h1>
            <h2 class="sub-title" style="text-transform: none; font-family: 'Poppins'; font-weight:400; font-style: normal; font-size: 22px; letter-spacing: 307; white-space: nowrap; color: black; margin-top: -30px; margin-left: auto;">Effective Medicine, New Medicine Everyday</h2>
            <p style="text-align: right;">
          </div>
        </div>
      </div>

      <style>
        .right-text {
          width: 100%;
          display: flex;
          justify-content: flex-end;
          height: 500px;
          padding-top: 20rem;
          padding-right: 5rem;
        }
        
        .site-block-cover-content {
          max-width: 100%;
        }
      </style>

    </div>
  </div>
</div>

<style>

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .vl {
    border-left: 2px solid grey;
    opacity: 60%;
    height: 20px;
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
<div class="bg-light py-5 site-section">
  <div class="container">

<!-- Redesigned About Section -->
<section id="about" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <span class="text-primary-500 font-semibold">WHO WE ARE</span>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2">About <span class="gradient-text">DynaCareSIS</span></h2>
        <div class="w-20 h-1 bg-primary-500 mx-auto mt-4"></div>
      </div>
      
      <!-- About Content -->
      <div class="flex flex-col lg:flex-row items-center gap-12 mb-20">
        <div class="lg:w-1/2">
          <div class="relative rounded-xl overflow-hidden shadow-lg">
            <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="Our Team" class="w-full h-auto">
            <div class="absolute inset-0 bg-primary-500 opacity-20"></div>
          </div>
        </div>
        <div class="lg:w-1/2">
          <h3 class="text-3xl font-bold text-gray-900 mb-4">Our Story</h3>
          <p class="text-gray-600 mb-6">
            Founded in 2025, DynaCareSIS has been at the forefront of pharmaceutical innovation, delivering high-quality healthcare solutions to communities across the region. What started as a small local pharmacy has grown into a trusted name in healthcare.
          </p>
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-3">
                <p class="text-gray-600">Committed to excellence in pharmaceutical care and patient health</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-3">
                <p class="text-gray-600">Innovative solutions for modern healthcare challenges</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-3">
                <p class="text-gray-600">Trusted by healthcare professionals and patients alike</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Mission and Vision -->
      <div class="grid md:grid-cols-2 gap-8 mb-20">
        <div class="bg-primary-50 p-8 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
          <div class="flex items-center mb-4">
            <div class="bg-primary-100 p-3 rounded-full mr-4">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Our Mission</h3>
          </div>
          <p class="text-gray-600">
            To provide accessible, high-quality pharmaceutical products and healthcare solutions that improve lives. We are committed to innovation, integrity, and excellence in everything we do.
          </p>
        </div>
        <div class="bg-primary-50 p-8 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
          <div class="flex items-center mb-4">
            <div class="bg-primary-100 p-3 rounded-full mr-4">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Our Vision</h3>
          </div>
          <p class="text-gray-600">
            To be the leading healthcare solutions provider in the region, recognized for our commitment to quality, innovation, and patient care. We envision a healthier future for all communities we serve.
          </p>
        </div>
      </div>
      
      <!-- Stats -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-all duration-300">
          <div class="text-4xl font-bold text-primary-600 mb-2">50+</div>
          <div class="text-gray-600">Healthcare Products</div>
        </div>
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-all duration-300">
          <div class="text-4xl font-bold text-primary-600 mb-2">10K+</div>
          <div class="text-gray-600">Satisfied Customers</div>
        </div>
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-all duration-300">
          <div class="text-4xl font-bold text-primary-600 mb-2">15+</div>
          <div class="text-gray-600">Years of Experience</div>
        </div>
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-all duration-300">
          <div class="text-4xl font-bold text-primary-600 mb-2">24/7</div>
          <div class="text-gray-600">Customer Support</div>
        </div>
      </div>
      
      <!-- Team Section -->
      <div class="text-center mb-12">
        <h3 class="text-3xl font-bold text-gray-900">Meet Our <span class="gradient-text">Leadership Team</span></h3>
        <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Our dedicated team of healthcare professionals is committed to your well-being</p>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="team-member relative group">
          <div class="relative rounded-xl overflow-hidden">
            <img src="https://i.ibb.co/XxFSsdwF/owner.jpg" alt="Team Member" class="w-full h-64 object-cover">
            <div class="team-overlay absolute inset-0 bg-primary-600 bg-opacity-80 flex flex-col justify-end p-6 opacity-0 transform translate-y-4 transition-all duration-300">
              <h4 class="text-white font-bold text-xl">Nelia Villoria</h4>
              <p class="text-primary-100">Owner of Dyna Care Medical Trading</p>
              <p class="text-white mt-2 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
          </div>
        </div>
        <div class="team-member relative group">
          <div class="relative rounded-xl overflow-hidden">
            <img src="https://i.ibb.co/pvdjwKv9/494687773-1918053765612775-1183976506540027343-n.jpg" alt="Team Member" class="w-full h-64 object-cover">
            <div class="team-overlay absolute inset-0 bg-primary-600 bg-opacity-80 flex flex-col justify-end p-6 opacity-0 transform translate-y-4 transition-all duration-300">
              <h4 class="text-white font-bold text-xl">Jawyna Tine Palpalatoc</h4>
              <p class="text-primary-100">Manager</p>
              <p class="text-white mt-2 text-sm">Pharmacy Graduate and Board Passer, Dyna Care Medical Trading Manager, and currently studying as pilot</p>
            </div>
          </div>
        </div>
        <div class="team-member relative group">
          <div class="relative rounded-xl overflow-hidden">
            <img src="https://i.ibb.co/8gnYmnNg/494821002-712635941109313-4329778390210694307-n.jpg" alt="Team Member" class="w-full h-64 object-cover">
            <div class="team-overlay absolute inset-0 bg-primary-600 bg-opacity-80 flex flex-col justify-end p-6 opacity-0 transform translate-y-4 transition-all duration-300">
              <h4 class="text-white font-bold text-xl">Jacfil Austrin Palpalatoc</h4>
              <p class="text-primary-100">Manager of Jacfil Pharmacy</p>
              <p class="text-white mt-2 text-sm">CEO of Argonautix Solution, Owner of La-Nuvola Vape Lounge</p>
            </div>
          </div>
        </div>
        <div class="team-member relative group">
          <div class="relative rounded-xl overflow-hidden">
            <img src="https://i.ibb.co/RpgKppLW/messages-3.jpg" alt="Team Member" class="w-full h-64 object-cover">
            <div class="team-overlay absolute inset-0 bg-primary-600 bg-opacity-80 flex flex-col justify-end p-6 opacity-0 transform translate-y-4 transition-all duration-300">
              <h4 class="text-white font-bold text-xl">Reburon</h4>
              <p class="text-primary-100">Lorem</p>
              <p class="text-white mt-2 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Values Section -->
  <section class="py-16 bg-primary-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <span class="text-primary-500 font-semibold">OUR CORE VALUES</span>
        <h2 class="text-4xl font-bold text-gray-900 mt-2">What We Stand For</h2>
        <div class="w-20 h-1 bg-primary-500 mx-auto mt-4"></div>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Integrity</h3>
          <p class="text-gray-600">We uphold the highest ethical standards in all our operations and interactions.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
          <p class="text-gray-600">We continuously seek new ways to improve healthcare delivery and outcomes.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Community</h3>
          <p class="text-gray-600">We are committed to serving and improving the health of our communities.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <span class="text-primary-500 font-semibold">TESTIMONIALS</span>
        <h2 class="text-4xl font-bold text-gray-900 mt-2">What Our Customers Say</h2>
        <div class="w-20 h-1 bg-primary-500 mx-auto mt-4"></div>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-gray-50 p-6 rounded-xl">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
              <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Customer" class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="font-bold text-gray-900">Maria Garcia</h4>
              <div class="flex text-yellow-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
              </div>
            </div>
          </div>
          <p class="text-gray-600 italic">"The team at DynaCareSIS provided exceptional care when I needed it most. Their knowledge and compassion made all the difference in my recovery."</p>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-xl">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
              <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer" class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="font-bold text-gray-900">James Cabalayan</h4>
              <div class="flex text-yellow-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
              </div>
            </div>
          </div>
          <p class="text-gray-600 italic">"I've been a customer for years and always receive personalized attention. Their staff goes above and beyond to ensure I get the right medications."</p>
        </div>
        
        <div class="bg-gray-50 p-6 rounded-xl">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
              <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Customer" class="w-full h-full object-cover">
            </div>
            <div>
              <h4 class="font-bold text-gray-900">Gloria Pagayatan</h4>
              <div class="flex text-yellow-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
              </div>
            </div>
          </div>
          <p class="text-gray-600 italic">"As a physician, I trust DynaCareSIS for my patients' pharmaceutical needs. Their quality control and service are unmatched in the industry."</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section (kept your existing contact section) -->
  
  <br>
  <br>
  <br>

  <!-- Footer -->
  <footer style="color: black;">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-6 md:mb-0">
          <div class="flex items-center">
            <img src="assets/img/dynaa.png" alt="Logo" class="w-10 h-10 rounded-full mr-3">
            <span class="text-xl font-bold">DynaCareSIS</span>
          </div>
          <p style="color: black;" class="mt-2 text-gray-400">Effective Medicine, New Medicine Everyday</p>
        </div>
        <div style="color: black;" class="flex space-x-6">
          <a href="#" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"></path></svg>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
          </a>
        </div>
      </div>
      <div style="color: black;" class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
        <p>&copy; 2025 DynaCareSIS System. All rights reserved.</p>
        <p class="mt-1 text-sm">Designed by Arcy Mae Christian S. Reburon</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      document.getElementById('mobile-menu').classList.toggle('hidden');
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