<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Resources</title>
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
            --text-color: #333;
            --muted-color: #777;
            --card-bg: #fff;
            --border-color: #ddd;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f0f2f5 0%, #e1e6ea 100%);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .resources-container {
            background: var(--card-bg);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 960px;
            margin: 2rem auto;
        }

        .resources-container h1 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 2.5rem;
            text-align: center;
        }

        .resource-section {
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: #f9f9f9;
        }

        .resource-section h2 {
            color: var(--secondary-color);
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .quote-card {
            background-color: #e9ecef;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-style: italic;
            color: var(--muted-color);
            border-left: 5px solid var(--primary-color);
        }

        .quote-card p {
            margin-bottom: 0.5rem;
        }

        .quote-card cite {
            display: block;
            text-align: right;
            font-size: 0.9rem;
            color: var(--text-color);
            font-style: normal;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .video-item {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .video-item iframe {
            width: 100%;
            aspect-ratio: 16 / 9;
            border: none;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }

        .activity-list li {
            padding: 0.8rem 0;
            border-bottom: 1px dashed var(--border-color);
            display: flex;
            align-items: center;
        }

        .activity-list li:last-child {
            border-bottom: none;
        }

        .activity-list li i {
            color: var(--accent-color);
            margin-right: 0.8rem;
        }

        .article-list {
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }

        .article-list li {
            padding: 0.8rem 0;
            border-bottom: 1px dashed var(--border-color);
        }

        .article-list li:last-child {
            border-bottom: none;
        }

        .article-list li a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .article-list li a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
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
    <div class="resources-container">
        <h1>Learning Resources</h1>

        <div class="resource-section">
            <h2>Motivational Quotes</h2>
            <div class="quote-card">
                <p>"The only way to do great work is to love what you do."</p>
                <cite>- Steve Jobs</cite>
            </div>
            <div class="quote-card">
                <p>"Believe you can and you're halfway there."</p>
                <cite>- Theodore Roosevelt</cite>
            </div>
            </div>

        <div class="resource-section">
            <h2>Videos on AI Section</h2>
            <div class="video-grid">
                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="AI Basics" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/aircAruvnKk" title="Machine Learning Explained" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                </div>
        </div>

        <div class="resource-section">
            <h2>Daily Activities</h2>
            <ul class="activity-list">
                <li><i class="fas fa-check-square"></i> Solve at least 3 coding problems on LeetCode or HackerRank.</li>
                <li><i class="fas fa-book-reader"></i> Read one article or chapter related to your field of study.</li>
                <li><i class="fas fa-code"></i> Work on your personal project for at least one hour.</li>
                <li><i class="fas fa-brain"></i> Spend 15 minutes brainstorming new ideas or solutions.</li>
                <li><i class="fas fa-comments"></i> Engage in a discussion with a peer or mentor about a challenging topic.</li>
                </ul>
        </div>

        <div class="resource-section">
            <h2>Articles</h2>
            <ul class="article-list">
                <li><a href="https://www.example.com/article1" target="_blank">The Future of Artificial Intelligence</a></li>
                <li><a href="https://www.example.com/article2" target="_blank">Top 10 Skills for Software Developers in 2025</a></li>
                <li><a href="https://www.example.com/article3" target="_blank">Effective Time Management Strategies for Students</a></li>
                </ul>
        </div>

        <a href="menteeDashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>