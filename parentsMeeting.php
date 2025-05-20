<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_date = $_POST['meetingDate'];
    $meeting_time = $_POST['meetingTime'];
    $google_meet_link = $_POST['googleMeetLink'];
    $additional_notes = $_POST['additionalNotes'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'mentorship');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert meeting details into the database
    $stmt = $conn->prepare("INSERT INTO parent_meetings (meeting_date, meeting_time, google_meet_link, additional_notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $meeting_date, $meeting_time, $google_meet_link, $additional_notes);
    if ($stmt->execute()) {
        echo "<script>alert('Meeting scheduled successfully!'); window.location.href = 'mentor.php';</script>";
    } else {
        echo "<script>alert('Error scheduling meeting.');</script>";
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
    <title>Schedule Parents Meeting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6c63ff;
            --primary-light: #a29bfe;
            --secondary-color: #00b894;
            --secondary-light: #55efc4;
            --accent-color: #fd79a8;
            --light-color: #f8f9fa;
            --dark-color: #2d3436;
            --text-color: #2d3436;
            --text-light: #636e72;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), 
                        url('https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-color);
        }

        .meeting-container {
            background-color: white;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 90%;
            max-width: 650px;
            position: relative;
            overflow: hidden;
            margin: 30px 0;
        }

        .meeting-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .header-section h1 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 2.5rem;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header-section h1 i {
            font-size: 2.8rem;
            color: var(--secondary-color);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-section p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 80%;
            margin: 0 auto;
        }

        .form-container {
            padding: 0 20px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: var(--primary-color);
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group input[type="url"],
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background-color: #f9f9f9;
        }

        .form-group input[type="date"]:focus,
        .form-group input[type="time"]:focus,
        .form-group input[type="url"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-light);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-section {
            text-align: center;
            margin-top: 30px;
        }

        .submit-button {
            padding: 14px 35px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
        }

        .submit-button:active {
            transform: translateY(1px);
        }

        .decorative-element {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.1), rgba(0, 184, 148, 0.1));
            z-index: -1;
        }

        .element-1 {
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
        }

        .element-2 {
            bottom: -40px;
            left: -40px;
            width: 150px;
            height: 150px;
        }

        .meet-tip {
            background-color: #f0f7ff;
            border-left: 4px solid var(--primary-color);
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meet-tip i {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .meet-tip p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .meeting-container {
                padding: 30px 20px;
                width: 95%;
            }
            
            .header-section h1 {
                font-size: 2rem;
            }
            
            .header-section p {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="meeting-container">
        <div class="decorative-element element-1"></div>
        <div class="decorative-element element-2"></div>
        
        <div class="header-section">
            <h1><i class="fas fa-users"></i> Schedule Meeting</h1>
            <p>Organize a productive discussion with parents to review student progress and development</p>
        </div>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="meetingDate"><i class="far fa-calendar-alt"></i> Meeting Date</label>
                    <input type="date" id="meetingDate" name="meetingDate" required>
                </div>
                
                <div class="form-group">
                    <label for="meetingTime"><i class="far fa-clock"></i> Meeting Time</label>
                    <input type="time" id="meetingTime" name="meetingTime" required>
                </div>
                
                <div class="form-group">
                    <label for="googleMeetLink"><i class="fab fa-google"></i> Google Meet Link</label>
                    <input type="url" id="googleMeetLink" name="googleMeetLink" placeholder="https://meet.google.com/abc-defg-hij" required>
                    
                    <div class="meet-tip">
                        <i class="fas fa-lightbulb"></i>
                        <p>Create a new meeting link in Google Calendar or use an existing one</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="additionalNotes"><i class="far fa-edit"></i> Meeting Agenda</label>
                    <textarea id="additionalNotes" name="additionalNotes" placeholder="Outline the discussion points for the meeting..."></textarea>
                </div>
                
                <div class="submit-section">
                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i> Schedule Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.getElementById('meetingDate').min = new Date().toISOString().split('T')[0];
        
        // Add animation to form elements
        document.querySelectorAll('.form-group').forEach((group, index) => {
            group.style.opacity = '0';
            group.style.transform = 'translateY(20px)';
            group.style.transition = `all 0.5s ease ${index * 0.1}s`;
            
            setTimeout(() => {
                group.style.opacity = '1';
                group.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>