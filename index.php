<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Clear background image */
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: url('https://eteia.agh.edu.pl/lib/kx7qnd/fullhd-illustration-1-l7vu3mg1.png') no-repeat center center fixed; /* Add your image path here */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container for the forms */
        .container {
            background: rgba(255, 255, 255, 0.95); /* More opaque white background */
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Softer shadow */
            border: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border */
            width: 450px;
            text-align: center;
        }

        /* RGB gradient (white to sky blue) for the Sign In card with border style */
        #signIn {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(135, 206, 235, 0.95)); /* White to sky blue gradient */
            border: 2px solid rgba(105, 176, 205, 0.8); /* Solid border with sky blue color */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        /* Form title */
        .form-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.4rem;
        }

        /* Input group styling */
        .input-group {
            position: relative;
            margin: 1rem 0;
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: none;
            border-bottom: 1px solid #757575;
            background-color: transparent;
            font-size: 15px;
        }

        .input-group input:focus {
            outline: none;
            border-bottom: 2px solid hsl(327, 90%, 28%);
        }

        .input-group input::placeholder {
            color: transparent;
        }

        .input-group label {
            position: absolute;
            left: 35px;
            top: 50%;
            transform: translateY(-50%);
            color: #757575;
            transition: 0.3s ease all;
            pointer-events: none;
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: -10px;
            font-size: 12px;
            color: hsl(327, 90%, 28%);
        }

        /* Button styling */
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgb(125, 125, 235);
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.9s;
        }

        .btn:hover {
            background-color: #07001f;
        }

        /* Links and icons */
        .or {
            margin: 1rem 0;
            font-size: 1.1rem;
        }

        .icons {
            margin: 1rem 0;
        }

        .icons i {
            color: rgb(125, 125, 235);
            font-size: 1.5rem;
            margin: 0 15px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .icons i:hover {
            color: #07001f;
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 0.9rem;
        }

        .links button {
            background: none;
            border: none;
            color: rgb(125, 125, 235);
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
        }

        .links button:hover {
            text-decoration: underline;
            color: blue;
        }

        /* Hide/show forms */
        #signup {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user-tag"></i>
                <select name="role" id="role" required>
                    <option value="">Select Role</option>
                    <option value="mentor">Mentor</option>
                    <option value="mentee">Mentee</option>
                </select>
                <label for="role">Role</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <p class="or">----------or--------</p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Already Have Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover"><a href="#">Recover Password</a></p>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">----------or--------</p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Don't have an account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>