<?php 
session_start();

include("connection.php");
include("functions.php");

// Check if the form is submitted
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // Get form data
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    // Check if form fields are not empty and username is not numeric
    if(!empty($Username) && !empty($Password) && !empty($Email) && !is_numeric($Username))
    {
        // Handle image upload
        if(isset($_FILES['faceImage']) && $_FILES['faceImage']['error'] == 0) {
            $image = $_FILES['faceImage']['tmp_name'];
            $imageData = addslashes(file_get_contents($image)); // Convert image to binary data
        } else {
            $imageData = null;
        }

        // Save data to database
        $User_ID = random_num(4);
        $query = "INSERT INTO users (User_ID, Username, Password, Email, Image) VALUES ('$User_ID', '$Username', '$Password', '$Email', '$imageData')";

        // Execute query
        mysqli_query($con, $query);

        // Redirect to login page
        header("Location: login.php");
        die;
    }
    else
    {
        // Invalid form data, display error message
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Side Hustle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-xxl bg-white p-0">
        <!-- Spinner -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0" style="color: #FE7A36;">Side Hustle</h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.html" class="nav-item nav-link active">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="job-list.html" class="nav-item nav-link">Job</a>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                    <a href="./job-form.html" class="btn rounded-0 py-4 px-lg-5 d-none d-lg-block" style="color: white; background-color: #FE7A36;">Employer<i class="fa fa-arrow-right ms-3"></i></a>
                </div>
            </div>
        </nav>

        <!-- Sign Up -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Sign Up</h1>
                <div class="row g-4">  
                    <div class="col-md-3 wow fadeInUp" data-wow-delay="0.1s"></div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            <form method="post" enctype="multipart/form-data" name="signupForm">
                                <div style="font-size: 20px;margin: 10px;color: white;">Sign Up</div>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input id="text" type="text" name="Username" class="form-control" placeholder="Username">
                                            <label for="username">Username</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input id="text" type="email" name="Email" class="form-control" placeholder="Email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input id="text" type="password" name="Password" class="form-control" placeholder="Username">
                                            <label for="username">Password</label>
                                        </div>
                                    </div>
                                    <!-- Image Capture + Accept -->
                                  <div class="col-md-12" style="justify-content: center; text-align:center">
                                      <h5 style="font-size: 20px; text-align:center">Upload an Image of Yourself</h5>
                                      <div class="form-floating">
                                          <video id="video" width="400" height="300"></video>
                                      </div>
                                      <br><br><button id="toggleButton" type="button" class="btn btn-primary" style="background-color: #FE7A36; outline: 2px #FE7A36">Toggle Webcam</button>
                                      <button id="captureButton" type="button" class="btn btn-primary" style="background-color: #FE7A36; outline: 2px #FE7A36">Capture Image</button>
                                      <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
                                      <img id="capturedImage" width="400" height="300" style="display:none;">
                                  </div>
                                    <div class="col-12">
                                        <button class="btn w-100 py-3" type="submit" style="background-color: #FE7A36; color: white;">Sign Up</button>
                                        <br><br><h6 style="text-align: center;">Have an account? <a href="login.php" style="color: #FE7A36; text-align: center;">Click here to Sign In.</a></h6>
                                    </div>  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Company</h5>
                        <a class="btn btn-link text-white-50" href="./index.html">Home</a>
                        <a class="btn btn-link text-white-50" href="./job-list.html">Job Lists</a>
                        <a class="btn btn-link text-white-50" href="./job-form.html">Post Job</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <a class="btn btn-link text-white-50" href="./index.html">Home</a>
                        <a class="btn btn-link text-white-50" href="./about.html">About Us</a>
                        <a class="btn btn-link text-white-50" href="./contact.html">Contact Us</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Contact Us</h5>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>(00) 123 456</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>SideHustle@gmail.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">SideHustle</a> Unlocking Opportunities.							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- JavaScript code -->
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>	

  <script>
    // Function to validate the form
    function validateForm() {
        var username = document.forms["signupForm"]["Username"].value;
        var email = document.forms["signupForm"]["Email"].value;
        var password = document.forms["signupForm"]["Password"].value;
        var faceImage = document.forms["signupForm"]["faceImage"].value;

        // Check if any required field is empty
        if (username == "" || email == "" || password == "" || faceImage == "") {
            alert("Please fill in all required fields.");
            return false; // Prevent form submission
        }
    }      
    
    let stream;

    // Function to initialize the webcam stream
    function initWebcam() {
        if (!stream) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    var video = document.getElementById('video');
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(err) {
                    console.log("An error occurred: " + err);
                });
        }
    }

    // Toggle webcam stream
    document.getElementById('toggleButton').addEventListener('click', function() {
        initWebcam(); // Initialize webcam stream if not already initialized

        if (stream) {
            stream.getTracks().forEach(function(track) {
                track.stop();
            });
            stream = null;
            document.getElementById('video').srcObject = null;
        }
    });

    // Capture image from webcam
    document.getElementById('captureButton').addEventListener('click', function() {
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');

        // Draw the video frame onto the canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Get the image data from the canvas
        var imageData = canvas.toDataURL('image/png');

        // Display the captured image
        var capturedImage = document.getElementById('capturedImage');
        capturedImage.src = imageData;
        capturedImage.style.display = 'block';

        // Set the captured image data to a hidden input field
        document.getElementById('faceImage').value = imageData;
    });
  </script>
</body>
</html>