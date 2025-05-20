<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

// Database connection with corrected database name
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "certificate_urls"; // Changed from mentorship_db to certificate_urls

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all submitted certificates with additional details
$sql = "SELECT mc.id, mc.mentee_name, mc.certificate_url, mc.submission_date, mc.status, u.email 
        FROM mentee_certificates mc
        JOIN users u ON mc.mentee_email = u.email
        ORDER BY mc.submission_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .back-btn {
            margin-top: 20px;
        }
        .table th {
            background-color: #3498db;
            color: white;
        }
        .table tr:hover {
            background-color: #f1f9ff;
        }
        .status-submitted {
            color: #ffc107;
            font-weight: bold;
        }
        .status-approved {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submitted Certificates</h1>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mentee Name</th>
                        <th>Mentee Email</th>
                        <th>Certificate URL</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $statusClass = 'status-' . $row['status'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['mentee_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['certificate_url']) . "' target='_blank' class='btn btn-sm btn-outline-primary'>View Certificate</a></td>";
                            echo "<td>" . htmlspecialchars($row['submission_date']) . "</td>";
                            echo "<td><span class='$statusClass'>" . ucfirst(htmlspecialchars($row['status'])) . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No certificates submitted yet</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <a href="mentor.php" class="btn btn-primary back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>