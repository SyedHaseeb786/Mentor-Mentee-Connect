<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard | Learning Management System</title>
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
            background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), 
                        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }
        
        .dashboard-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        
        .dashboard-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
        
        .user-details {
            text-align: left;
        }
        
        .user-details .user-email {
            font-weight: 500;
            color: var(--text-color);
        }
        
        .user-details .user-role {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 2.5rem;
        }
        
        .card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card:hover::before {
            width: 8px;
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .card:hover .card-icon {
            transform: scale(1.1);
        }
        
        .card h2 {
            margin-bottom: 0.75rem;
            font-size: 1.4rem;
            font-weight: 600;
        }
        
        .card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0;
        }
        
        /* Card specific styles */
        .upload-marks-card::before {
            background-color: var(--primary-color);
        }
        
        .upload-marks-card .card-icon {
            color: var(--primary-color);
        }
        
        .track-progress-card::before {
            background-color: var(--success-color);
        }
        
        .track-progress-card .card-icon {
            color: var(--success-color);
        }
        
        .post-announcement-card::before {
            background-color: var(--warning-color);
        }
        
        .post-announcement-card .card-icon {
            color: var(--warning-color);
        }
        
        .parents-meeting-card::before {
            background-color: #6f42c1;
        }
        
        .parents-meeting-card .card-icon {
            color: #6f42c1;
        }
        
        .assignment-card::before {
            background-color: var(--accent-color);
        }
        
        .assignment-card .card-icon {
            color: var(--accent-color);
        }
        
        .contact-mentee-card::before {
            background-color: #28a745;
        }
        
        .contact-mentee-card .card-icon {
            color: #28a745;
        }
        
        .contact-parent-card::before {
            background-color: var(--danger-color);
        }
        
        .contact-parent-card .card-icon {
            color: var(--danger-color);
        }
        
        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background-color: var(--danger-color);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
            border: none;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        .logout-btn i {
            margin-right: 0.5rem;
        }
        
        /* Animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
        
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }
        .card:nth-child(7) { animation-delay: 0.7s; }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1.5rem;
                width: 95%;
            }
            
            .cards {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1><i class="fas fa-chalkboard-teacher me-2"></i>Mentor Dashboard</h1>
            <p>Manage your mentorship activities and student progress</p>
            
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?>
                </div>
                <div class="user-details">
                    <div class="user-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                    <div class="user-role">Mentor</div>
                </div>
            </div>
        </div>
        
        <div class="cards">
            <div class="card upload-marks-card" onclick="navigateTo('upload_marks.php')">
                <div class="card-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <h2>Upload Marks</h2>
                <p>Upload student marks via Excel spreadsheet</p>
            </div>
            
            <div class="card track-progress-card" onclick="navigateTo('track_progress.php')">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2>Track Progress</h2>
                <p>View mentee academic progress and attendance</p>
            </div>
            
            <div class="card post-announcement-card" onclick="navigateTo('post_announcement.php')">
                <div class="card-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h2>Post Announcement</h2>
                <p>Share announcements and meeting links</p>
            </div>
            
            <div class="card parents-meeting-card" onclick="navigateTo('parentsMeeting.php')">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h2>Parents Meeting</h2>
                <p>Schedule and manage parents' meetings</p>
            </div>
            
            <div class="card assignment-card" onclick="navigateTo('view_certificat.php')">
                <div class="card-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h2>View Assignments</h2>
                <p>Review and grade submitted certificates</p>
            </div>
            
            <div class="card contact-mentee-card" onclick="navigateTo('contact_mentee.php')">
                <div class="card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h2>Contact Mentee</h2>
                <p>View and message your mentees</p>
            </div>
            
            <div class="card contact-parent-card" onclick="navigateTo('contact_parent.php')">
                <div class="card-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h2>Contact Parent</h2>
                <p>Communicate with mentee's parents</p>
            </div>
        </div>
        
        <button class="logout-btn" onclick="navigateTo('logout.php')">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>