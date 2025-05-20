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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            background-attachment: fixed;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 700;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .announcement {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .announcement:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .announcement::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.03) 0%, rgba(76, 201, 240, 0.03) 100%);
            z-index: 0;
        }

        .announcement-content {
            position: relative;
            z-index: 1;
        }

        .announcement p {
            margin: 0;
            font-size: 16px;
            color: #495057;
            line-height: 1.6;
        }

        .posted-at {
            font-size: 14px;
            color: #6c757d;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .no-announcements {
            text-align: center;
            padding: 40px;
            background: rgba(248, 249, 250, 0.7);
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }

        .no-announcements i {
            font-size: 48px;
            color: #adb5bd;
            margin-bottom: 15px;
        }

        .no-announcements p {
            color: #6c757d;
            font-size: 18px;
            margin: 0;
        }

        .floating-icons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }

        .floating-icons i {
            position: absolute;
            color: rgba(67, 97, 238, 0.1);
            font-size: 24px;
            animation: float 15s linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.2;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: var(--secondary-color);
            transform: translateX(-3px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            .announcement {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background icons -->
    <div class="floating-icons">
        <?php 
        $icons = ['fa-bullhorn', 'fa-calendar', 'fa-bell', 'fa-envelope', 'fa-info-circle'];
        for ($i = 0; $i < 12; $i++) {
            $icon = $icons[array_rand($icons)];
            echo '<i class="fas '.$icon.'" style="left: '.rand(0, 100).'%; top: '.rand(100, 120).'%; animation-delay: '.rand(0, 10).'s; font-size: '.rand(1, 3).'rem;"></i>';
        }
        ?>
    </div>

    <div class="container">
        <a href="mentee_dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <h1><i class="fas fa-bullhorn me-2"></i> Announcements</h1>
        
        <div id="announcement-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="announcement">
                        <div class="announcement-content">
                            <p><?php echo nl2br(htmlspecialchars($row['announcement'])); ?></p>
                            <div class="posted-at">
                                <i class="far fa-clock"></i>
                                Posted at: <?php echo date('F j, Y \a\t g:i A', strtotime($row['posted_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-announcements">
                    <i class="far fa-bell-slash"></i>
                    <p>No announcements found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to announcements when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const announcements = document.querySelectorAll('.announcement');
            announcements.forEach((announcement, index) => {
                setTimeout(() => {
                    announcement.style.opacity = '1';
                    announcement.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>