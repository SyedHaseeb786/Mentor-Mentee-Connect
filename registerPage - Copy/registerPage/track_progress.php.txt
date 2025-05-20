<?php
session_start(); // Start the session
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php"); // Redirect to login if not logged in as a mentor
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Track Mentee Progress</h1>
        <p>View mentee progress and attendance here.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>Mentee</th>
                    <th>Progress</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Haseeb</td>
                    <td>80%</td>
                    <td>90%</td>
                </tr>
                <tr>
                    <td>Venkatesh</td>
                    <td>70%</td>
                    <td>85%</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>