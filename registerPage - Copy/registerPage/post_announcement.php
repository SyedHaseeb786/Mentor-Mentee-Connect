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
    <title>Post Announcement (Mentor)</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        #announcement-form {
            display: flex;
            flex-direction: column;
        }

        #announcement-input {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        #announcement-form button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Post Announcement (Mentor)</h1>
        <form id="announcement-form">
            <textarea id="announcement-input" placeholder="Enter announcement text"></textarea>
            <button type="submit">Post Announcement</button>
        </form>
    </div>

    <script>
        document.getElementById('announcement-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const announcementInput = document.getElementById('announcement-input');
            const announcementText = announcementInput.value.trim();

            if (announcementText) {
                let announcements = JSON.parse(localStorage.getItem('announcements')) || [];
                announcements.push(announcementText);
                localStorage.setItem('announcements', JSON.stringify(announcements));

                announcementInput.value = '';
                alert('Announcement posted successfully!');
            }
        });
    </script>
</body>
</html>