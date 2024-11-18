<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixify - Subscription Plans</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <style>
    body {
      background-color: #f5f8fc;
    }
    .nav-link {
      color: #000;
      font-weight: bold;
    }
    .btn-custom {
      background-color: #e0ecff;
      color: #000;
      border: none;
      border-radius: 20px;
      font-weight: bold;
      padding: 10px 20px;
    }
    .btn-custom:hover {
      background-color: #d0d8f0;
    }
    footer {
      margin-top: 50px;
      font-size: 14px;
      text-align: center;
      color: gray;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="#">Pixify</a>
      <div class="collapse navbar-collapse">
        <!-- Links moved to the left -->
        <ul class="navbar-nav ms-3">
          <li class="nav-item"><a class="nav-link" href="#">Discover</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Subscriptions</a></li>
        </ul>
        <!-- Right-aligned buttons -->
        <div class="ms-auto">
          <button class="btn btn-outline-primary me-2">Login</button>
          <button class="btn btn-primary">Sign Up</button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container my-5">
    <h2 class="text-center fw-bold mb-5">Unlock These Exclusive Benefits</h2>
    <div class="row justify-content-center">
      <!-- Free Plan -->
      <div class="col-md-4">
        <div class="card text-center shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Free Plan</h5>
            <span class="badge bg-success mb-3">Free</span>
            <p class="card-text">
              Start your creative journey for free! Publish <strong>up to 30 posts</strong> without spending a dime. If you hit the limit, simply swap out an older post for a new one. Perfect for hobbyists or those starting their photography adventure!
            </p>
            <p class="fw-bold">Cost: <span class="text-success">Absolutely Free!</span></p>
            <button class="btn btn-custom">Get Free</button>
          </div>
        </div>
      </div>

      <!-- Advanced Plan -->
      <div class="col-md-4">
        <div class="card text-center shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Advanced Plan</h5>
            <span class="badge bg-primary mb-3">Advanced</span>
            <p class="card-text">
              Take your creativity to the next level! Share <strong>up to 250 posts</strong>, giving you plenty of room to showcase your photography and designs. Replace older posts to make space for new ones. Ideal for growing artists who want more freedom without breaking the bank!
            </p>
            <p class="fw-bold">Cost: <span class="text-primary">€4.99/month</span></p>
            <button class="btn btn-custom">Get Advanced</button>
          </div>
        </div>
      </div>

      <!-- Premium Plan -->
      <div class="col-md-4">
        <div class="card text-center shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Premium Plan</h5>
            <span class="badge bg-warning mb-3">Premium</span>
            <p class="card-text">
              Go all in—no limits, no compromises! Publish an <strong>unlimited number of posts</strong> and build a professional portfolio with ease. No need to choose or delete—every shot and design can have its spotlight. Perfect for professionals demanding the best tools to showcase their work.
            </p>
            <p class="fw-bold">Cost: <span class="text-warning">€14.99/month</span></p>
            <button class="btn btn-custom">Get Premium</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <footer>
    <div class="container">
      <div class="row">
        <div class="col">
          <p>Company</p>
          <p><a href="#" class="text-secondary">About Us</a></p>
          <p><a href="#" class="text-seco
