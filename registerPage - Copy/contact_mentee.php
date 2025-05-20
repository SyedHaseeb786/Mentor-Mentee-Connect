<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

// Database connection
$db = new mysqli('localhost', 'username', 'password', 'mentor_management');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get mentor ID (you would typically have this in session)
$mentor_id = 1; // This should come from your session in a real application

// Fetch mentees for this mentor
$stmt = $db->prepare("SELECT * FROM mentees WHERE mentor_id = ?");
$stmt->bind_param("i", $mentor_id);
$stmt->execute();
$result = $stmt->get_result();
$mentees = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Mentees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }
        .mentee-card {
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .mentee-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .mentee-name {
            font-weight: bold;
            color: #007bff;
        }
        .contact-info {
            margin-top: 10px;
        }
        .contact-info i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-users"></i> Your Mentees</h1>
        
        <div class="row">
            <?php foreach ($mentees as $mentee): ?>
                <div class="col-md-6">
                    <div class="card mentee-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mentee-name">
                                <i class="fas fa-user-graduate"></i> <?= htmlspecialchars($mentee['name']) ?>
                            </h5>
                            <div class="contact-info">
                                <p><i class="fas fa-envelope text-success"></i> <?= htmlspecialchars($mentee['email']) ?></p>
                                <p><i class="fas fa-phone text-primary"></i> <?= htmlspecialchars($mentee['phone']) ?></p>
                                <p><i class="fas fa-book text-info"></i> <?= htmlspecialchars($mentee['course']) ?></p>
                            </div>
                            <a href="message_mentee.php?id=<?= $mentee['id'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="dashboard.php" class="btn btn-primary back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $db->close(); ?>