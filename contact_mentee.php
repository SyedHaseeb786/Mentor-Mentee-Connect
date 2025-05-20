<?php
// contact_mentee.php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

// Hardcoded sample data for mentees and their parents
$mentees = [
    [
        'mentee_id' => 1,
        'mentee_name' => 'Syed Haseeb',
        'mentee_email' => 'syeh6135@gmail.com',
        'mentee_phone' => '9700890898',
        'parent_name' => 'Syed Irshad',
        'parent_email' => 'irirshad@gmail.com',
        'parent_phone' => '9989319609',
    ],
    [
        'mentee_id' => 2,
        'mentee_name' => 'Arusuru Rakesh',
        'mentee_email' => 'rakesharusur345@gmail.com',
        'mentee_phone' => '7654321098',
        'parent_name' => 'Arusuru Mohan',
        'parent_email' => 'mohana@gmail.com',
        'parent_phone' => '9543210987',
    ],
    [
        'mentee_id' => 3,
        'mentee_name' => 'Shaik Adil Basha',
        'mentee_email' => 'adilshaikb@gmail.com',
        'mentee_phone' => '5432109876',
        'parent_name' => 'Shaik Rashid',
        'parent_email' => 'shaikR@gmail.com',
        'parent_phone' => '9321098765',
    ],
    [
        'mentee_id' => 4,
        'mentee_name' => 'Thota ManikantaSrinu',
        'mentee_email' => 'sreenumanib@gmail.com',
        'mentee_phone' => '9432109876',
        'parent_name' => 'Thota Sai Prasad',
        'parent_email' => 'saiprasad@gmail.com',
        'parent_phone' => '9321098765',
    ],
    [
        'mentee_id' => 5,
        'mentee_name' => 'Sai Dileep', // Corrected line
        'mentee_email' => 'dileepxn@gmail.com',
        'mentee_phone' => '9432109876',
        'parent_name' => 'Sai Mohann',
        'parent_email' => 'mohansai@gmail.com',
        'parent_phone' => '9321098785',
    ],
    [
        'mentee_id' => 6,
        'mentee_name' => 'Venkatesh Reddy',
        'mentee_email' => 'venkateshr@gmail.com',
        'mentee_phone' => '9432109876',
        'parent_name' => 'Ajay Reddy',
        'parent_email' => 'ajayR@gmail.com',
        'parent_phone' => '9378098765',
    ]
    // Add more mentee data here
];

// In a real application without a database, you might filter this array
// based on some criteria related to the logged-in mentor if needed.
// For this hardcoded example, we'll display all the sample mentees.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Mentees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #e67e22;
            --light-color: #f8f9fa;
            --text-color: #333;
            --border-color: #ddd;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .contact-container {
            background-color: var(--light-color);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 95%;
            max-width: 1000px;
        }

        .contact-container h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 2.5rem;
        }

        .mentee-list {
            list-style: none;
            padding: 0;
        }

        .mentee-item {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns for mentee and parent info */
            gap: 20px;
        }

        .mentee-info, .parent-info {
            padding: 1rem;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .mentee-info h3, .parent-info h3 {
            color: var(--secondary-color);
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.4rem;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .contact-detail {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            color: var(--text-color);
        }

        .contact-detail strong {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .contact-detail a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-detail a:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            border: 1px solid var(--primary-color);
            margin-top: 2rem;
        }

        .back-link:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h1><i class="fas fa-user-graduate"></i> Contact Mentees</h1>
        <ul class="mentee-list">
            <?php if (!empty($mentees)): ?>
                <?php foreach ($mentees as $mentee): ?>
                    <li class="mentee-item">
                        <div class="mentee-info">
                            <h3>Mentee Details</h3>
                            <div class="contact-detail"><strong>Name:</strong> <?php echo htmlspecialchars($mentee['mentee_name']); ?></div>
                            <div class="contact-detail"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($mentee['mentee_email']); ?>"><?php echo htmlspecialchars($mentee['mentee_email']); ?></a></div>
                            <div class="contact-detail"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($mentee['mentee_phone']); ?>"><?php echo htmlspecialchars($mentee['mentee_phone']); ?></a></div>
                            </div>
                        <div class="parent-info">
                            <h3>Parent Details</h3>
                            <?php if (isset($mentee['parent_name'])): ?>
                                <div class="contact-detail"><strong>Name:</strong> <?php echo htmlspecialchars($mentee['parent_name']); ?></div>
                                <div class="contact-detail"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($mentee['parent_email']); ?>"><?php echo htmlspecialchars($mentee['parent_email']); ?></a></div>
                                <div class="contact-detail"><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($mentee['parent_phone']); ?>"><?php echo htmlspecialchars($mentee['parent_phone']); ?></a></div>
                                <?php else: ?>
                                <p>No parent details available.</p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No mentees found.</p>
            <?php endif; ?>
        </ul>
        <a href="mentor.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>