<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>DynaCare Pharmacy</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/dyna.png" type="image/x-icon" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  
  <style>
    /* Preloader styles */
    #preloader {
      display: flex;
      align-items: center;
      justify-content: center;
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background: white;
      z-index: 9999;
    }

    .dot {
      width: 15px;
      height: 15px;
      margin: 0 5px;
      border-radius: 50%;
      background-color: #007bff;
      animation: dot-blink 1.4s infinite both;
    }

    .dot:nth-child(1) {
      animation-delay: 0s;
    }

    .dot:nth-child(2) {
      animation-delay: 0.2s;
    }

    .dot:nth-child(3) {
      animation-delay: 0.4s;
    }

    @keyframes dot-blink {
      0%, 80%, 100% {
        opacity: 0;
      }
      40% {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>

  <!-- Your content here -->
  <script>
    // Preloader fade out effect
    window.addEventListener('load', () => {
      const preloader = document.getElementById('preloader');
      preloader.style.transition = 'opacity 0.5s ease';
      preloader.style.opacity = '0';
      setTimeout(() => {
        preloader.style.display = 'none';
      }, 500);
    });
  </script>



