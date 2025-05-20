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
    <title>Mentor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for light RGB colors and organized layout */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, rgba(240, 244, 248, 0.9), rgba(227, 233, 242, 0.9)), 
                        url('https://www.chieflearningofficer.com/wp-content/uploads/2023/01/AdobeStock_422946225.jpeg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
            text-align: center;
        }

        .dashboard-container h1 {
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            margin-bottom: 15px;
            color: #2575fc;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card p {
            color: #555;
            font-size: 1rem;
            line-height: 1.5;
        }

        .additional-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .additional-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #2575fc;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .additional-buttons button:hover {
            background-color: #1a5bbf;
            transform: translateY(-2px);
        }

        .additional-buttons button:active {
            transform: translateY(0);
        }

        /* New style for assignment card */
        .assignment-card {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
        }

        .assignment-card h2 {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Mentor</h1>
        <p>You are logged in as <?php echo htmlspecialchars($_SESSION['email']); ?>.</p>
        <div class="cards">
            <div class="card" onclick="navigateTo('upload_marks.php')">
                <h2>Upload Marks</h2>
                <p>Upload student marks via Excel.</p>
            </div>
            <div class="card" onclick="navigateTo('track_progress.php')">
                <h2>Track Progress</h2>
                <p>View mentee progress and attendance.</p>
            </div>
            <div class="card" onclick="navigateTo('post_announcement.php')">
                <h2>Post Announcement</h2>
                <p>Post announcements and meeting links.</p>
            </div>
            <div class="card" onclick="navigateTo('parentsMeeting.php')">
                <h2>Parents Meeting</h2>
                <p>Schedule and manage parents' meetings.</p>
            </div>
            <!-- New View Assignments Card -->
            <div class="card assignment-card" onclick="navigateTo('view_assignments.php')">
                <h2>View Assignments</h2>
                <p>Review and grade submitted certificates.</p>
            </div>
        </div>

        <div class="additional-buttons">
            <button onclick="navigateTo('view_reports.php')">View Reports</button>
            <button onclick="navigateTo('manage_mentees.php')">Manage Mentees</button>
        </div>
        <a href="logout.php" style="margin-top: 20px; display: inline-block; color: #2575fc; text-decoration: none;">Logout</a>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>