<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

// Array of mentor contact information (replace with your actual data)
$mentors = [
    [
        'name' => 'Venkateshwaro',
        'phone' => '9989319834',
        'email' => 'venkateshwaro.mentor@example.com'
    ],
    [
        'name' => 'Priya Sharma',
        'phone' => '8765432109',
        'email' => 'priya.sharma@example.com'
    ],
    [
        'name' => 'Rajesh Kumar',
        'phone' => '7012345678',
        'email' => 'rajesh.kumar@example.com'
    ],
    [
        'name' => 'Anjali Reddy',
        'phone' => '9876543210',
        'email' => 'anjali.reddy@example.com'
    ],
    [
        'name' => 'Suresh Menon',
        'phone' => '8098765432',
        'email' => 'suresh.menon@example.com'
    ],
    [
        'name' => 'Deepika Patel',
        'phone' => '7778889990',
        'email' => 'deepika.patel@example.com'
    ],
    [
        'name' => 'Karthik Srinivasan',
        'phone' => '9123456789',
        'email' => 'karthik.s@example.com'
    ],
    [
        'name' => 'Meera Nair',
        'phone' => '8887776665',
        'email' => 'meera.nair@example.com'
    ],
    [
        'name' => 'Amit Verma',
        'phone' => '7654321098',
        'email' => 'amit.verma@example.com'
    ],
    [
        'name' => 'Shalini Gupta',
        'phone' => '9012345671',
        'email' => 'shalini.gupta@example.com'
    ],
    [
        'name' => 'Ravi Chandran',
        'phone' => '8234567890',
        'email' => 'ravi.chandran@example.com'
    ],
    [
        'name' => 'Neha Singh',
        'phone' => '7901234567',
        'email' => 'neha.singh@example.com'
    ],
    [
        'name' => 'Arun Kumar',
        'phone' => '9345678901',
        'email' => 'arun.kumar@example.com'
    ],
    // You can continue adding more mentors here
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Mentors</title>
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
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contact-container {
            background: rgba(255, 255, 255, 0.15);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 800px;
            text-align: center;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
            overflow: auto; /* Enable scroll if many mentors */
        }

        .contact-container::before {
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

        .contact-container h2 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
        }

        .contact-container h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }

        .mentor-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden; /* To contain rounded borders */
        }

        .mentor-table th, .mentor-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--light-color);
        }

        .mentor-table th {
            background-color: rgba(255, 255, 255, 0.15);
            font-weight: bold;
        }

        .mentor-table tr:last-child td {
            border-bottom: none;
        }

        .mentor-table td a {
            color: var(--info-color);
            text-decoration: underline;
        }

        .back-link {
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
            margin-top: 20px;
        }

        .back-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h2>Contact Your Mentors</h2>

        <table class="mentor-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mentors as $mentor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mentor['name']); ?></td>
                        <td><?php echo htmlspecialchars($mentor['phone']); ?></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($mentor['email']); ?>"><?php echo htmlspecialchars($mentor['email']); ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="menteedashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>