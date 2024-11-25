<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixify - Discover and Share</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
   
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <!-- Header Section -->
    <section class="header-section text-center">
    <div class="row align-items-start ">
        <div class="container col text-center pt-5 mt-5">
            <h1 class="display-4">Discover and share your photos with the world</h1>
            <button class="btn btn-primary mt-3">Sign In</button>
        </div>
        <div class="container col mb-0">
           <img src="../images/cat-png.png" alt="cat" class="img-fluid mb-0">
        </div>
</div>
    </section>

    <!-- Main Content -->
    <section class="about-section text-center py-5">
    <h2 class="display-5">Transforming Creativity into Digital Masterpieces</h2>
    <div class="container mt-4">
        <p class="lead">
            At Pixify, we believe every creative idea has the potential to be turned into a digital masterpiece.
            Whether you're a photographer, graphic designer, or digital artist, our platform provides you with all the tools and resources needed to showcase your talent and connect with a global audience.
        </p>
        <p>
            With a user-friendly interface and cutting-edge features, Pixify allows you to enhance, edit, and share your works of art. 
            Join a community of like-minded individuals and professionals who are passionate about creativity and digital innovation. 
            Our platform makes it easy to get feedback, collaborate, and even monetize your work.
        </p>
        <p>
            From simple photo edits to advanced digital compositions, Pixify gives you everything you need to transform your creative visions into tangible digital creations.
            Start sharing your masterpieces with the world today!
        </p>
        <a href="#services" class="btn btn-primary mt-4">Explore Our Content</a>
    </div>
</section>


    <section class="services-section text-center py-5 bg-light">
        <h2 class="display-5 mb-4">What do we provide?</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <i class="bi bi-camera" style="font-size: 2rem;"></i>
                    <h4>Grow as a photographer</h4>
                    <p>Expand your skills with guidance from experienced photographers.</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                    <h4>Build your career</h4>
                    <p>Showcase your portfolio and reach potential clients.</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-bar-chart" style="font-size: 2rem;"></i>
                    <h4>See how you're performing</h4>
                    <p>Receive analytics on your work’s engagement and reach.</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-cash" style="font-size: 2rem;"></i>
                    <h4>Sell your work</h4>
                    <p>Monetize your photography through our platform.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="join-section text-center py-5  text-white">
        <h2>Join Our Community Today!</h2>
        <button class="btn btn-light mt-3">Sign Up</button>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
