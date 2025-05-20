<?php
session_start(); // Start the session
include 'connect.php';

// Handle Registration
if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password, role)
                        VALUES ('$firstName', '$lastName', '$email', '$password', '$role')";
        if ($conn->query($insertQuery)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Handle Login
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];

        // Redirect based on role
        if ($_SESSION['role'] == 'mentor') {
            echo "Redirecting to mentor dashboard...";
            header("Location: mentor.php"); // Ensure this path is correct
            exit();
        } elseif ($_SESSION['role'] == 'mentee') {
            header("Location: menteeDashboard.php"); // Update this to the correct file name
            exit();
        }
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>