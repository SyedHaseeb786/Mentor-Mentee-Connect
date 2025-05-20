<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_date = $_POST['meetingDate'];
    $meeting_time = $_POST['meetingTime'];
    $google_meet_link = $_POST['googleMeetLink'];
    $additional_notes = $_POST['additionalNotes'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'mentorship');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert meeting details into the database
    $stmt = $conn->prepare("INSERT INTO parent_meetings (meeting_date, meeting_time, google_meet_link, additional_notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $meeting_date, $meeting_time, $google_meet_link, $additional_notes);
    if ($stmt->execute()) {
        echo "<script>alert('Meeting scheduled successfully!'); window.location.href = 'mentor.php';</script>";
    } else {
        echo "<script>alert('Error scheduling meeting.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Parents Meeting</title>
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

        .meeting-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }

        .meeting-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .submit-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #00838f;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #006064;
        }

        .meet-link {
            margin-top: 20px;
            font-size: 18px;
            color: #00838f;
            word-break: break-all;
        }

        .meet-link a {
            color: #00838f;
            text-decoration: none;
        }

        .meet-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="meeting-container">
        <h1>Schedule Parents Meeting (Mentor)</h1>
        <form method="POST">
            <div class="form-group">
                <label for="meetingDate">Meeting Date</label>
                <input type="date" id="meetingDate" name="meetingDate" required>
            </div>
            <div class="form-group">
                <label for="meetingTime">Meeting Time</label>
                <input type="time" id="meetingTime" name="meetingTime" required>
            </div>
            <div class="form-group">
                <label for="googleMeetLink">Google Meet Link</label>
                <input type="url" id="googleMeetLink" name="googleMeetLink" placeholder="https://meet.google.com/xxx-yyyy-zzz" required>
            </div>
            <div class="form-group">
                <label for="additionalNotes">Additional Notes</label>
                <textarea id="additionalNotes" name="additionalNotes" placeholder="Add any additional notes for the meeting..."></textarea>
            </div>
            <button type="submit" class="submit-button">Schedule Meeting</button>
        </form>
    </div>
</body>
</html>