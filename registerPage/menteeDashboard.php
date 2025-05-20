<?php
session_start(); // Start the session
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php"); // Redirect to login if not logged in as a mentee
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_assignment'])) {
    $certificate_link = filter_var($_POST['certificate_link'], FILTER_SANITIZE_URL);
    
    // Validate URL
    if (filter_var($certificate_link, FILTER_VALIDATE_URL)) {
        // Here you would typically save to database
        // For now, we'll just show a success message
        $success_message = "Certificate submitted successfully!";
    } else {
        $error_message = "Please enter a valid URL for your certificate.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentee Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background image with overlay */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://trafft.com/wp-content/uploads/2022/10/3818436-768x365.jpg') no-repeat center center fixed; /* Add your image path here */
            background-size: cover;
        }

        /* Overlay to make text readable */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3); /* Dark overlay for better contrast */
            z-index: -1;
        }

        /* Dashboard container */
        .dashboard-container {
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            text-align: center;
            backdrop-filter: blur(10px); /* Blur effect for modern look */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Light border */
        }

        .dashboard-container h1 {
            color: #ffffff; /* White text for better contrast */
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 2.5rem;
        }

        .dashboard-container p {
            color: #e0e0e0; /* Light gray text */
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        /* Cards styling */
        .cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(173, 216, 230, 0.9)); /* White to sky blue gradient */
            padding: 25px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: 1px solid rgba(173, 216, 230, 0.5); /* Sky blue border */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #1b5e20; /* Dark green for contrast */
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .card p {
            color: #495057; /* Dark gray for readability */
            margin: 0;
            font-size: 1rem;
        }

        /* Logout link styling */
        .logout-link {
            margin-top: 20px;
            display: inline-block;
            color: #ffffff; /* White text */
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #cccccc; /* Light gray on hover */
            text-decoration: underline;
        }

        /* Assignment form styling */
        .assignment-form {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(200, 230, 200, 0.9));
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: left;
        }

        .assignment-form h3 {
            color: #1b5e20;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Mentee</h1>
        <p>You are logged in as <?php echo $_SESSION['email']; ?>.</p>
        
        <!-- Display success/error messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="cards">
            <div class="card" onclick="navigateTo('view_progress.php')">
                <h2>View Progress</h2>
                <p>Check your marks and attendance.</p>
            </div>
            <div class="card" onclick="navigateTo('view_announcements.php')">
                <h2>View Announcements</h2>
                <p>See announcements and meeting links.</p>
            </div>
            <div class="card" onclick="navigateTo('contact_mentor.php')">
                <h2>Contact Mentor</h2>
                <p>Send a message to your mentor.</p>
            </div>
            <div class="card" onclick="navigateTo('menteeGoogleMeetLink.php')">
                <h2>Parent Meeting Announcement</h2>
                <p>View parent meeting details.</p>
            </div>
            
            <!-- Assignment Submission Card -->
            <div class="assignment-form">
                <h3>Submit Certificate</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="certificate_link">Certificate URL:</label>
                        <input type="url" id="certificate_link" name="certificate_link" required placeholder="https://example.com/certificate.pdf">
                    </div>
                    <button type="submit" name="submit_assignment" class="submit-btn">Submit Certificate</button>
                </form>
            </div>
        </div>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page; // Navigate to the specified page
        }
    </script>
</body>
</html>