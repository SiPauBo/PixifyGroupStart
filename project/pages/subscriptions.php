
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
  <title>Pixify - Subscription Plans</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css"/>
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

    footer .container {
      max-width: 600px;
    }

    footer hr {
      border: 0.5px solid #e0e0e0;
    }

    footer p {
      margin: 0;
      line-height: 1.5;
    }

  </style>

<?php include '../includes/font.php'; ?>
</head>
<body>

  <?php include "../includes/navbar.php"?>

  <div class="container my-5">
    <h2 class="text-center fw-bold mb-5">Unlock These Exclusive Benefits</h2>
    <div class="row justify-content-center">

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

      <div class="col-md-4">
        <div class="card text-center shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Premium Plan</h5>
            <span class="badge bg-warning mb-3">Premium</span>
            <p class="card-text">
              Go all in no limits, no compromises! Publish an <strong>unlimited number of posts</strong> and build a professional portfolio with ease. No need to choose or delete—every shot and design can have its spotlight. Perfect for professionals demanding the best tools to showcase their work.
            </p>
            <p class="fw-bold">Cost: <span class="text-warning">€14.99/month</span></p>
            <button class="btn btn-custom">Get Premium</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php include "../includes/footer.php"?>

</body>
</html>
