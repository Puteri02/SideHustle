<?php 
session_start();

include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $UsernameOrEmail = $_POST['username_or_email'];
    $Password = $_POST['password'];

    if(!empty($UsernameOrEmail) && !empty($Password)) {

        // Check if the input is an email
        if (filter_var($UsernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, query using email
            $query = "SELECT * FROM users WHERE Email = '$UsernameOrEmail' LIMIT 1";
        } else {
            // If it's not an email, query using username
            $query = "SELECT * FROM users WHERE Username = '$UsernameOrEmail' LIMIT 1";
        }

        $result = mysqli_query($con, $query);

        if($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['Password'] === $Password) {
                $_SESSION['User_ID'] = $user_data['User_ID'];
                echo "<script>alert('Welcome ".$user_data['Username']."');</script>";
                echo "<script>window.location.href='index.html';</script>";
                die;
            }
        }
        echo "<script>alert('Wrong username or password!');</script>";
    } else {
        echo "<script>alert('Please enter username/email and password!');</script>";
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
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
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
        <!-- Navbar End -->

		<!-- Login Start -->
		<div class="container-xxl py-5">
            <div class="container">
				<h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Sign In</h1>
					<div class="row g-4">  
						<div class="col-md-3 wow fadeInUp" data-wow-delay="0.1s"></div>
						<div class="col-md-6">
							<div class="wow fadeInUp" data-wow-delay="0.5s">
								<form method="post">
								<div style="font-size: 20px;margin: 10px;color: white;">Login</div>
								<div class="row g-3">
									<div class="col-md-12">
										<div class="form-floating">
											<input id="text" type="text" name="username_or_email" class="form-control" placeholder="Username or Email"><br><br>
											<label for="username_or_email">Username or Email</label>
										</div>
									</div>
										<div class="col-md-12">
											<div class="form-floating">
											<input id="text" type="password" name="password" class="form-control" placeholder="Password"><br><br>
											<label for="pass">Password</label>
										</div>
									</div>
									<div class="col-12">
										<button class="btn w-100 py-3" type="submit" style="background-color: #FE7A36; color: white;">Sign In</button>
										<br><br><h6 style="text-align: center;">Don't have an account? <a href="signup-ekyc.php" style="color: #FE7A36; text-align: center;">Sign Up here.</a></h6>
									</div>	
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- Login End -->

	
	        <!-- Footer Start -->
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
        <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
