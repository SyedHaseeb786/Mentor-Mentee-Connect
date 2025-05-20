<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

// Fetch announcements from the database
$conn = new mysqli('localhost', 'root', '', 'mentorship');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM announcements ORDER BY posted_at DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .announcement {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
        }

        .announcement p {
            margin: 0;
            font-size: 16px;
            color: #495057;
        }

        .posted-at {
            font-size: 14px;
            color: #6c757d;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Announcements</h1>
        <div id="announcement-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="announcement">
                        <p><?php echo $row['announcement']; ?></p>
                        <div class="posted-at">Posted at: <?php echo $row['posted_at']; ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>