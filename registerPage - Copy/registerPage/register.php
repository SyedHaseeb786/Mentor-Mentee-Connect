<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["signUp"])) {
        $fName = filter_var($_POST["fName"], FILTER_SANITIZE_STRING);
        $lName = filter_var($_POST["lName"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $role = $_POST["role"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fName, $lName, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            header("Location: homepage.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST["signIn"])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            header("Location: homepage.php");
        } else {
            echo "Invalid email or password";
        }
        $stmt->close();
    }
}
$conn->close();
?>