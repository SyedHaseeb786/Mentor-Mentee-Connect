<?php
session_start(); // Start the session
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php"); // Redirect to login if not logged in as a mentor
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents Meeting (Mentor)</title>
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
        <form id="meetingForm">
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

        <div id="meetLinkDisplay" class="meet-link" style="display: none;">
            <p>Meeting Scheduled! Share this link with parents:</p>
            <a id="meetLink" target="_blank"></a>
        </div>
    </div>

    <script>
        document.getElementById('meetingForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const meetingDate = document.getElementById('meetingDate').value;
            const meetingTime = document.getElementById('meetingTime').value;
            const googleMeetLink = document.getElementById('googleMeetLink').value;
            const additionalNotes = document.getElementById('additionalNotes').value;

            const meetLinkDisplay = document.getElementById('meetLinkDisplay');
            const meetLink = document.getElementById('meetLink');
            meetLink.href = googleMeetLink;
            meetLink.textContent = googleMeetLink;
            meetLinkDisplay.style.display = 'block';

            const meetingDetails = {
                date: meetingDate,
                time: meetingTime,
                link: googleMeetLink,
                notes: additionalNotes
            };
            localStorage.setItem('meetingDetails', JSON.stringify(meetingDetails));
            console.log('Meeting Details:', meetingDetails);

            document.getElementById('meetingForm').reset();
        });
    </script>
</body>
</html>