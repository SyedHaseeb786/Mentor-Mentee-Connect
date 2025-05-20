<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

$success_message = '';
$error_message = '';

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "certificate_urls";

try {
    // Connect to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Verify user exists
    $mentee_email = $_SESSION['email'];
    $sql = "SELECT name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $mentee_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error_message = "User not found. Please complete your registration first.";
    } else {
        $user = $result->fetch_assoc();
        $mentee_name = $user['name'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_assignment'])) {
            $certificate_link = filter_var($_POST['certificate_link'], FILTER_SANITIZE_URL);
            
            if (filter_var($certificate_link, FILTER_VALIDATE_URL)) {
                // Check if certificate already exists
                $check_sql = "SELECT id FROM mentee_certificates WHERE mentee_email = ?";
                $check_stmt = $conn->prepare($check_sql);
                
                if (!$check_stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $check_stmt->bind_param("s", $mentee_email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    // Update existing certificate
                    $update_sql = "UPDATE mentee_certificates SET 
                                  certificate_url = ?, 
                                  submission_date = CURRENT_TIMESTAMP,
                                  status = 'submitted'
                                  WHERE mentee_email = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    
                    if (!$update_stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }
                    
                    $update_stmt->bind_param("ss", $certificate_link, $mentee_email);
                    
                    if ($update_stmt->execute()) {
                        $_SESSION['success_message'] = "Certificate updated successfully!";
                        header("Location: menteeDashboard.php");
                        exit();
                    } else {
                        throw new Exception("Update failed: " . $conn->error);
                    }
                } else {
                    // Insert new certificate
                    $insert_sql = "INSERT INTO mentee_certificates 
                                 (mentee_email, mentee_name, certificate_url) 
                                 VALUES (?, ?, ?)";
                    $insert_stmt = $conn->prepare($insert_sql);
                    
                    if (!$insert_stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }
                    
                    $insert_stmt->bind_param("sss", $mentee_email, $mentee_name, $certificate_link);
                    
                    if ($insert_stmt->execute()) {
                        $_SESSION['success_message'] = "Certificate submitted successfully!";
                        header("Location: menteeDashboard.php");
                        exit();
                    } else {
                        throw new Exception("Insert failed: " . $conn->error);
                    }
                }
            } else {
                $error_message = "Please enter a valid URL (must start with http:// or https://)";
            }
        }
    }
} catch (Exception $e) {
    $error_message = "System error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://trafft.com/wp-content/uploads/2022/10/3818436-768x365.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        
        .certificate-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .certificate-container h1 {
            color: #ffffff;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background-color: #218838;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .back-btn:hover {
            color: #cccccc;
            text-decoration: underline;
        }
        
        .alert {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .text-white {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1>Submit Certificate</h1>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($error_message) || $error_message != "User not found. Please complete your registration first."): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="certificate_link">Certificate URL:</label>
                    <input type="url" id="certificate_link" name="certificate_link" 
                           required placeholder="https://example.com/certificate.pdf"
                           class="form-control" 
                           value="<?php echo isset($_POST['certificate_link']) ? htmlspecialchars($_POST['certificate_link']) : ''; ?>">
                    <small class="text-white">Please enter the full URL including https://</small>
                </div>
                <button type="submit" name="submit_assignment" class="btn-submit">
                    Submit Certificate
                </button>
            </form>
        <?php endif; ?>
        
        <a href="menteeDashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>