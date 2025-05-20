<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $announcement = $_POST['announcement'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'mentorship');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert announcement into the database
    $stmt = $conn->prepare("INSERT INTO announcements (announcement) VALUES (?)");
    $stmt->bind_param("s", $announcement);
    if ($stmt->execute()) {
        echo "<script>alert('Announcement posted successfully!'); window.location.href = 'mentor.php';</script>";
    } else {
        echo "<script>alert('Error posting announcement.');</script>";
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
    <title>Post Announcement (Mentor)</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('mentor_background.jpg'); /* Add your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            color: #3498db; /* Blue heading color */
            margin-bottom: 25px;
            font-size: 2.5em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        #announcement-form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        #announcement-input {
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            font-size: 1em;
            transition: border-color 0.3s ease-in-out;
        }

        #announcement-input:focus {
            border-color: #3498db;
            outline: none;
        }

        #announcement-form button {
            padding: 15px 30px;
            background-color: #2ecc71; /* Green button color */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        #announcement-form button:hover {
            background-color: #27ae60;
            transform: scale(1.05);
        }

        /* Icon styling (example using Font Awesome - you'll need to include the Font Awesome CSS) */
        button::before {
          /* content: "\f044"; */ /* Example Font Awesome icon for edit/post */
          /* font-family: "Font Awesome 5 Free"; */
          /* font-weight: 900; */
          /* margin-right: 10px; */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Post Announcement (Mentor)</h1>
        <form id="announcement-form" method="POST">
            <textarea id="announcement-input" name="announcement" placeholder="Enter announcement text" required></textarea>
            <button type="submit">Post Announcement</button>
        </form>
    </div>
</body>
</html>