<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentee') {
    header("Location: index.php");
    exit();
}

$mentee_email = $_SESSION['email'];

$conn = new mysqli('localhost', 'root', '', 'mentorship');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Print the logged-in mentee's email
echo "Logged-in mentee email: $mentee_email<br>";

$result = $conn->query("SELECT * FROM marks WHERE mentee_email = '$mentee_email'");

// Debugging: Check if any rows are returned
if ($result->num_rows > 0) {
    echo "Marks found for mentee: $mentee_email<br>";
} else {
    echo "No marks found for mentee: $mentee_email<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Marks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Your Marks</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>Subject 1</td>
                            <td><?php echo $row['subject1']; ?></td>
                        </tr>
                        <tr>
                            <td>Subject 2</td>
                            <td><?php echo $row['subject2']; ?></td>
                        </tr>
                        <tr>
                            <td>Subject 3</td>
                            <td><?php echo $row['subject3']; ?></td>
                        </tr>
                        <tr>
                            <td>Subject 4</td>
                            <td><?php echo $row['subject4']; ?></td>
                        </tr>
                        <tr>
                            <td>Subject 5</td>
                            <td><?php echo $row['subject5']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Overall Percentage</strong></td>
                            <td><?php echo $row['overall_percentage']; ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No marks found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>