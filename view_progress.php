<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'mentorship');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mentee_email = $_SESSION['email'];

// Fetch marks for the logged-in mentee
$marks_result = $conn->query("SELECT * FROM marks WHERE mentee_email = '$mentee_email'");

// Check if marks are found
if ($marks_result->num_rows > 0) {
    $row = $marks_result->fetch_assoc();
    $subject1 = $row['subject1'];
    $subject2 = $row['subject2'];
    $subject3 = $row['subject3'];
    $subject4 = $row['subject4'];
    $subject5 = $row['subject5'];
    $overall_percentage = $row['overall_percentage'];
} else {
    $subject1 = $subject2 = $subject3 = $subject4 = $subject5 = $overall_percentage = 0;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://trafft.com/wp-content/uploads/2022/10/3818436-768x365.jpg') no-repeat center center fixed;
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
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }

        .progress-container {
            background: rgba(255, 255, 255, 0.8); /* White background with slight transparency */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .progress-container h1 {
            color: #333333; /* Dark gray text */
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 2.5rem;
        }

        .progress-container p {
            color: #555555; /* Gray text */
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .progress-bar {
            width: 100%;
            height: 30px;
            background-color: #e0e0e0; /* Light gray background */
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: #4caf50; /* Green color for progress */
            width: <?php echo $overall_percentage; ?>%;
            transition: width 0.5s ease;
        }

        .progress-details {
            text-align: left;
            margin-top: 20px;
        }

        .progress-details h3 {
            color: #333333; /* Dark gray text */
            margin-bottom: 10px;
        }

        .progress-details p {
            color: #555555; /* Gray text */
            margin: 5px 0;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #333333; /* Dark gray text */
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #000000; /* Black text on hover */
            text-decoration: underline;
        }

        /* Card styling with yellow-to-blue gradient */
        .card {
            background: linear-gradient(135deg, rgb(255, 255, 0), rgb(0, 0, 255)); /* Yellow to blue gradient */
            padding: 25px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            color: #ffffff; /* White text for better contrast */
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .card p {
            color: #ffffff; /* White text for better contrast */
            margin: 0;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <h1>Your Progress</h1>
        <p>Here is your progress based on your marks.</p>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-bar-fill"></div>
        </div>

        <!-- Progress Details -->
        <div class="progress-details">
            <h3>Subject-wise Marks</h3>
            <p>Subject 1: <?php echo $subject1; ?> / 100</p>
            <p>Subject 2: <?php echo $subject2; ?> / 100</p>
            <p>Subject 3: <?php echo $subject3; ?> / 100</p>
            <p>Subject 4: <?php echo $subject4; ?> / 100</p>
            <p>Subject 5: <?php echo $subject5; ?> / 100</p>
            <p><strong>Overall Percentage: <?php echo $overall_percentage; ?>%</strong></p>
        </div>

        <!-- Back Link -->
        <a href="menteeDashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>