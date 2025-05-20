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
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }

        .container a {
            color: #1976d2;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Parent Meeting Details</h1>
        <div id="meetLinkDisplay">
            <?php if ($meeting_details): ?>
                <p><strong>Date:</strong> <?php echo $meeting_details['meeting_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $meeting_details['meeting_time']; ?></p>
                <p><strong>Google Meet Link:</strong> <a href="<?php echo $meeting_details['google_meet_link']; ?>" target="_blank">Join Meeting</a></p>
                <p><strong>Additional Notes:</strong> <?php echo $meeting_details['additional_notes']; ?></p>
            <?php else: ?>
                <p>No meeting scheduled yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>