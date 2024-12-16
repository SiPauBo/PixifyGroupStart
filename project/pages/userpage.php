<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixify - Profile Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css"/>
  <style>
    body {
      background-color: #f5f8fc;
    }

    .nav-link {
      color: #000;
      font-weight: bold;
    }

    .profile-banner {
      background-image: url('https://via.placeholder.com/1200x300');
      background-size: cover;
      background-position: center;
      height: 300px;
      position: relative;
    }

    .profile-details {
      position: relative;
      text-align: center;
      margin-top: -60px; /* Profile details overlap banner */
    }

    .profile-details img {
      border: 5px solid #fff;
      border-radius: 50%;
      height: 120px; /* Match image size */
      width: 120px;
    }

    .profile-name {
      font-size: 24px;
      font-weight: bold;
      color: #000;
      margin-top: 10px;
    }

    .btn-custom {
      background-color: #007bff;
      color: #fff;
      border: none; 
      padding: 10px 20px;
      border-radius: 5px; 
      font-weight: bold;
      margin-top: 10px;
    }

    .btn-custom:hover {
      background-color: #0056b3;
    }

    .gallery {
      margin-top: 50px;
    }

    .gallery img {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }

    footer {
      margin-top: 50px;
      font-size: 14px;
      text-align: center;
      color: gray;
    }
  </style>
   <?php include '../includes/font.php'; ?>
</head>
<body>

<?php include "../includes/navbar.php"?>
<div class="profile-banner"></div>

<div class="container">
  <div class="profile-details text-center">
    <img src="https://via.placeholder.com/100" alt="Profile Picture">
    <div class="profile-name">Rainer Winkler</div>
    <button class="btn-custom">Edit Name</button>
  </div>

  <div class="gallery row g-4 mt-4">
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
    <div class="col-md-3"><div class="placeholder"></div></div>
  </div>
</div>

<?php include "../includes/footer.php"?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
