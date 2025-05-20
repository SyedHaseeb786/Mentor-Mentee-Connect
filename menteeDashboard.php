<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

// Check for success message from certificate submission
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentee Dashboard</title>
    <link rel="stylesheet" href="style.css">
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
            --success-color: #4bb543;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: cover;
            background-attachment: fixed;
            color: var(--light-color);
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.15);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
            text-align: center;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            margin: 3rem auto;
            position: relative;
            overflow: hidden;
        }

        .dashboard-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: -1;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .dashboard-container h1 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
        }

        .dashboard-container h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }

        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .user-info i {
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 240, 240, 0.9));
            padding: 1.8rem;
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: none;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 20px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            color: var(--primary-color);
            margin-bottom: 0.8rem;
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card h2 i {
            font-size: 1.6rem;
            color: var(--secondary-color);
        }

        .card p {
            color: #555;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .card .card-footer {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-link {
            margin-top: 2.5rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .logout-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .alert {
            margin-bottom: 2rem;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background-color: var(--success-color);
        }

        .floating-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .floating-icons i {
            position: absolute;
            color: rgba(255,255,255,0.1);
            font-size: 1.5rem;
            animation: float 15s linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1.5rem;
                width: 95%;
            }
            
            .cards {
                grid-template-columns: 1fr;
            }
            
            .dashboard-container h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Floating background icons -->
        <div class="floating-icons">
            <?php 
            $icons = ['fa-graduation-cap', 'fa-book', 'fa-user-graduate', 'fa-chalkboard-teacher', 'fa-certificate', 'fa-calendar-check'];
            for ($i = 0; $i < 15; $i++) {
                $icon = $icons[array_rand($icons)];
                echo '<i class="fas '.$icon.'" style="left: '.rand(0, 100).'%; top: '.rand(100, 120).'%; animation-delay: '.rand(0, 10).'s; font-size: '.rand(1, 3).'rem;"></i>';
            }
            ?>
        </div>
        
        <h1>Mentee Dashboard</h1>
        
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="cards">
            <div class="card" onclick="navigateTo('view_progress.php')">
                <div>
                    <h2><i class="fas fa-chart-line"></i> View Progress</h2>
                    <p>Track your academic journey with detailed marks and attendance records in real-time.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> View your progress
                </div>
            </div>
            
            <div class="card" onclick="navigateTo('view_announcements.php')">
                <div>
                    <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
                    <p>Stay updated with the latest news, meeting links, and important notices from your mentor.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> See announcements
                </div>
            </div>
            
            <div class="card" onclick="navigateTo('contact_mentor.php')">
                <div>
                    <h2><i class="fas fa-comments"></i> Contact Mentor</h2>
                    <p>Directly communicate with your mentor for guidance, questions, or support.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Send message
                </div>
            </div>
            
            <div class="card" onclick="navigateTo('view_meetings.php')">
                <div>
                    <h2><i class="fas fa-users"></i> Parent Meetings</h2>
                    <p>Access details about upcoming parent-teacher meetings and past meeting notes.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Meeting details
                </div>
            </div>
            
            <div class="card" onclick="navigateTo('submit_certificate.php')">
                <div>
                    <h2><i class="fas fa-award"></i> Submit Certificate</h2>
                    <p>Upload your achievement certificates and credentials for mentor verification.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Upload credentials
                </div>
            </div>
            
            <div class="card" onclick="navigateTo('resources.php')">
                <div>
                    <h2><i class="fas fa-book-open"></i> Learning Resources</h2>
                    <p>Access study materials, recommended readings, and additional learning resources.</p>
                </div>
                <div class="card-footer">
                    <i class="fas fa-arrow-right"></i> Explore resources
                </div>
            </div>
        </div>
        
        <a href="logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function navigateTo(page) {
            // Add a ripple effect when clicking cards
            const ripple = document.createElement('div');
            ripple.style.position = 'fixed';
            ripple.style.borderRadius = '50%';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 600ms linear';
            ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            ripple.style.pointerEvents = 'none';
            
            // Get click position
            const x = event.clientX;
            const y = event.clientY;
            
            // Position the ripple
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            ripple.style.width = ripple.style.height = '20px';
            
            document.body.appendChild(ripple);
            
            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
                window.location.href = page;
            }, 500);
        }
        
        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(10);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>