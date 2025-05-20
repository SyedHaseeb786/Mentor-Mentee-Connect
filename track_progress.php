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
    <title>Track Progress | Learning Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-color: #2c3e50;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                            url('https://images.unsplash.com/photo-1550006891-4988418943a1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
        }

        .progress-container {
            background-color: var(--light-color);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
        }

        .progress-header {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--primary-color);
        }

        .progress-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .progress-header p {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        .progress-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .progress-table thead {
            background-color: var(--primary-color);
            color: var(--light-color);
        }

        .progress-table th, .progress-table td {
            padding: 1rem 1.2rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .progress-table th {
            font-weight: 600;
        }

        .progress-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .progress-table tbody tr:hover {
            background-color: #eee;
            transition: background-color 0.3s ease;
        }

        .progress-value {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .attendance-value {
            font-weight: 500;
            color: var(--success-color);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 2rem;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            border: 1px solid var(--primary-color);
        }

        .back-link:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <div class="progress-header">
            <h1><i class="fas fa-chart-line me-2"></i>Track Mentee Progress</h1>
            <p>Monitor the academic journey and engagement of your mentees.</p>
        </div>
        <table class="progress-table">
            <thead>
                <tr>
                    <th><i class="fas fa-user-graduate me-2"></i>Mentee</th>
                    <th><i class="fas fa-tasks me-2"></i>Progress</th>
                    <th><i class="fas fa-calendar-check me-2"></i>Attendance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Syed Haseeb</td>
                    <td class="progress-value">80%</td>
                    <td class="attendance-value">90%</td>
                </tr>
                <tr>
                    <td>Arusuru Rakesh</td>
                    <td class="progress-value">70%</td>
                    <td class="attendance-value">85%</td>
                </tr>
                <tr>
                    <td>Shaik Adil Basha</td>
                    <td class="progress-value">85%</td>
                    <td class="attendance-value">92%</td>
                </tr>
                <tr>
                    <td>Thota ManikantaSrinu</td>
                    <td class="progress-value">75%</td>
                    <td class="attendance-value">88%</td>
                </tr>
                <tr>
                    <td>Sai Dileep</td>
                    <td class="progress-value">92%</td>
                    <td class="attendance-value">95%</td>
                </tr>
                <tr>
                    <td>Venkatesh Reddy</td>
                    <td class="progress-value">65%</td>
                    <td class="attendance-value">80%</td>
                </tr>
                </tbody>
        </table>
        <a href="mentor.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>