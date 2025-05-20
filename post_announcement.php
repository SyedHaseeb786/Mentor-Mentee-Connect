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
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --warning-color: #fca311;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--dark-color);
        }

        .container {
            max-width: 700px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: all 0.4s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .header {
            margin-bottom: 30px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .subtitle {
            color: #666;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        #announcement-form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
        }

        .form-group {
            position: relative;
        }

        #announcement-input {
            width: 100%;
            padding: 20px;
            margin-bottom: 10px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            resize: vertical;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-height: 200px;
            font-family: 'Poppins', sans-serif;
            background-color: rgba(248, 249, 250, 0.7);
        }

        #announcement-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        #announcement-form button {
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        #announcement-form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        #announcement-form button:active {
            transform: translateY(1px);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .character-count {
            text-align: right;
            font-size: 0.8rem;
            color: #666;
            margin-top: -15px;
        }

        /* Animation for the form */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #announcement-form {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-bullhorn"></i>
                Post Announcement
            </h1>
            <p class="subtitle">Share important updates with your mentees</p>
        </div>
        
        <form id="announcement-form" method="POST">
            <div class="form-group">
                <i class="fas fa-edit form-icon"></i>
                <textarea id="announcement-input" name="announcement" 
                          placeholder="Enter your announcement here..." required></textarea>
                <div class="character-count" id="char-count">0/1000 characters</div>
            </div>
            
            <button type="submit">
                <i class="fas fa-paper-plane"></i>
                Post Announcement
            </button>
        </form>
        
        <a href="mentor.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <script>
        // Character count functionality
        const textarea = document.getElementById('announcement-input');
        const charCount = document.getElementById('char-count');
        
        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = `${currentLength}/1000 characters`;
            
            if (currentLength > 1000) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = '#666';
            }
        });
    </script>
</body>
</html>