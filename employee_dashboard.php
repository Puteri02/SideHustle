<?php
session_start();
include("connection.php");

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['User_ID'];

// Fetch user information
//$user_query = "SELECT Username, Email, Resume FROM users WHERE User_ID = ?";
$user_query = "SELECT Username, Email, Resume, ProfilePic FROM users WHERE User_ID = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if (!$user_result) {
    die('Error fetching user information: ' . htmlspecialchars($user_stmt->error));
}
$user_data = $user_result->fetch_assoc();
if (!$user_data) {
    die('User data not found.');
}
$user_stmt->close();

// Fetch jobs applied by the user with status
$query = "SELECT jobs.Job_ID, jobs.Title, jobs.Company, jobs.Description, jobs.Location, jobs.WorkDate, job_applications.Applied_At, job_applications.Status
          FROM job_applications 
          JOIN jobs ON job_applications.Job_ID = jobs.Job_ID 
          WHERE job_applications.User_ID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die('Error fetching job applications: ' . htmlspecialchars($stmt->error));
}
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="employee_dashboard.php" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
            <h1 class="m-0" style="color: #FE7A36;">Side Hustle</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <!-- Display welcome message with username -->
                <a class="nav-link" href="#" id="username" role="button" style="color: #FE7A36;">
                    Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?>
                </a>                
                <a href="jobs_list.php" class="nav-item nav-link">Job</a>
                <a href="employee_dashboard.php" class="nav-item nav-link">Dashboard</a>
                <a href="logout.php" class="nav-item nav-link">Log Out</a>    
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- User Dashboard Content -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5">User Dashboard</h1>

            <!-- Personal Information -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4" id="applied-jobs" style="color: #FE7A36;">Personal Information</h2>
                            <div class="mb-3">
                                <label for="username" class="form-label"><b>Username:</b></label>
                                <p><?php echo htmlspecialchars($user_data['Username'] ?? ''); ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><b>Email:</b></label>
                                <p><?php echo htmlspecialchars($user_data['Email'] ?? ''); ?></p>
                            </div>
                            <div class="mb-3">
                                <label for="resume" class="form-label"><b>Resume:</b></label><br>
                                <?php 
                                $resume_file = htmlspecialchars($user_data['Resume']);
                                $resume_path = __DIR__ . '/' . $resume_file;
                                
                                if (!empty($resume_file) && file_exists($resume_path)): ?>
                                    <p>
                                        <a href="<?php echo $resume_file; ?>" target="_blank"><?php echo basename($resume_file); ?></a>
                                    </p>
                                <?php else: ?>
                                    <p>No resume uploaded or file not found</p>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="profilePic" class="form-label"><b>Profile Picture:</b></label><br>
                                <?php 
                                $profile_pic_file = htmlspecialchars($user_data['ProfilePic']);
                                $profile_pic_path = __DIR__ . '/' . $profile_pic_file;
                                
                                if (!empty($profile_pic_file) && file_exists($profile_pic_path)): ?>
                                    <img src="<?php echo $profile_pic_file; ?>" alt="Profile Picture" style="max-width: 200px;">
                                <?php else: ?>
                                    <p>No profile picture uploaded or file not found</p>
                                <?php endif; ?>
                            </div>      
                        </div>
                    </div>
                </div>
            </div><br><br><br>

            <!-- Job Applications -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4" id="applied-jobs" style="color: #FE7A36;">Your Job Applications</h2>
                            <?php if (!empty($jobs)): ?>
                                <div class="row">
                                    <?php foreach ($jobs as $job): ?>
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($job['Title'] ?? ''); ?></h5>
                                                    <?php if (!empty($job['Company'])): ?>
                                                        <p class="card-text"><strong>Company:</strong> <?php echo htmlspecialchars($job['Company']); ?></p>
                                                    <?php endif; ?>                                                    <p class="card-text"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['Description'] ?? '')); ?></p>
                                                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($job['Location'] ?? ''); ?></p>
                                                    <p class="card-text"><strong>Work Date:</strong> <?php echo htmlspecialchars($job['WorkDate'] ?? ''); ?></p>
                                                    <p class="card-text"><strong>Applied On:</strong> <?php echo htmlspecialchars($job['Applied_At'] ?? ''); ?></p>
                                                    <p class="card-text"><strong>Status:</strong> 
                                                        <?php 
                                                        $status = strtolower($job['Status'] ?? ''); 
                                                        $status_class = '';
                                                        switch ($status) {
                                                            case 'interview':
                                                                $status_class = 'bg-warning text-dark';
                                                                break;
                                                            case 'accepted':
                                                                $status_class = 'bg-success text-white';
                                                                break;
                                                            case 'rejected':
                                                                $status_class = 'bg-danger text-white';
                                                                break;
                                                            default:
                                                                $status_class = 'bg-secondary text-white'; // KIV or Applied
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-center">You haven't applied for any jobs yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedJobId;
            function openApplicationModal(jobId) {
                selectedJobId = jobId;
                $('#job-id').val(jobId);
                $('#application-modal').fadeIn();
            }

            $('#application-form').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: 'submit_application.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            alert(res.message);
                            $('#application-modal').fadeOut();
                            location.reload(); // Refresh the page to see the new application
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function() {
                        alert('Failed to submit application. Please try again.');
                    }
                });
            });
    </script>
</body>
</html>