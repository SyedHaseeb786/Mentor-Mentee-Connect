<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

// Fetch the latest parent meeting details from the database
$conn = new mysqli('localhost', 'root', '', 'mentorship');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$meeting_query = "SELECT * FROM parent_meetings ORDER BY scheduled_at DESC LIMIT 1";
$meeting_result = $conn->query($meeting_query);
$meeting_details = $meeting_result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Meetings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2980b9;
            --secondary-color: #87CEEB;
            --accent-color: #f39c12;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --text-color: #333;
            --border-radius: 10px;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('meeting-bg.jpg') no-repeat center center fixed; /* Replace with your image path */
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        .container h1 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .container h1 i {
            font-size: 2.8rem;
            color: var(--accent-color);
        }

        .meeting-info {
            text-align: left;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid var(--secondary-color);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.8); /* Slightly more opaque for info */
        }

        .meeting-info p {
            color: var(--text-color);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .meeting-info p strong {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 5px;
        }

        .meeting-info p a {
            color: var(--accent-color);
            text-decoration: none;
            transition: text-decoration 0.3s ease;
        }

        .meeting-info p a:hover {
            text-decoration: underline;
        }

        .no-meeting {
            color: var(--muted-color);
            font-style: italic;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: var(--light-color);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .back-link:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-users"></i> Parent Meeting Details</h1>
        <div id="meetLinkDisplay">
            <?php if ($meeting_details): ?>
                <div class="meeting-info">
                    <p><strong><i class="fas fa-calendar-alt"></i> Date:</strong> <?php echo $meeting_details['meeting_date']; ?></p>
                    <p><strong><i class="fas fa-clock"></i> Time:</strong> <?php echo $meeting_details['meeting_time']; ?></p>
                    <p><strong><i class="fab fa-google-meet"></i> Google Meet Link:</strong> <a href="<?php echo $meeting_details['google_meet_link']; ?>" target="_blank">Join Meeting</a></p>
                    <p><strong><i class="fas fa-sticky-note"></i> Additional Notes:</strong> <?php echo $meeting_details['additional_notes']; ?></p>
                </div>
            <?php else: ?>
                <p class="no-meeting"><i class="fas fa-info-circle"></i> No meeting scheduled yet.</p>
            <?php endif; ?>
        </div>
        <a href="menteeDashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>