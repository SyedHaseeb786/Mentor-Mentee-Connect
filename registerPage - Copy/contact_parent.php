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

// Fetch parents of mentees for this mentor
$stmt = $db->prepare("SELECT p.*, m.name as mentee_name 
                     FROM parents p 
                     JOIN mentees m ON p.mentee_id = m.id 
                     WHERE m.mentor_id = ?");
$stmt->bind_param("i", $mentor_id);
$stmt->execute();
$result = $stmt->get_result();
$parents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Parents</title>
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
            color: #dc3545;
            margin-bottom: 30px;
        }
        .parent-card {
            border-left: 4px solid #dc3545;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .parent-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .parent-name {
            font-weight: bold;
            color: #dc3545;
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
        <h1><i class="fas fa-user-friends"></i> Parents of Your Mentees</h1>
        
        <div class="row">
            <?php foreach ($parents as $parent): ?>
                <div class="col-md-6">
                    <div class="card parent-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title parent-name">
                                <i class="fas fa-user-tie"></i> <?= htmlspecialchars($parent['name']) ?>
                            </h5>
                            <p class="text-muted">
                                <i class="fas fa-user-graduate"></i> Parent of: <?= htmlspecialchars($parent['mentee_name']) ?>
                            </p>
                            <div class="contact-info">
                                <p><i class="fas fa-envelope text-success"></i> <?= htmlspecialchars($parent['email']) ?></p>
                                <p><i class="fas fa-phone text-primary"></i> <?= htmlspecialchars($parent['phone']) ?></p>
                            </div>
                            <a href="message_parent.php?id=<?= $parent['id'] ?>" class="btn btn-outline-danger btn-sm">
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