<?php
// Import config.php
require_once("config.php");

// Start the session (this should be done at the beginning of every PHP file that uses sessions)
session_start();

// Function to hash the password (using PHP password_hash function)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify the password (using PHP password_verify function)
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Function to check if the username already exists in the database
function isUsernameExists($username) {
    global $mysqli;
    $selectQuery = "SELECT `id` FROM `users` WHERE `username` = '$username'";
    $result = $mysqli->query($selectQuery);
    return $result->num_rows > 0;
} 

// Function to display a toast message
function displayToast($message, $type = 'error') {
    echo '<div class="toast toast-' . $type . '">' . $message . '</div>';
}

// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $errors = array();

    // Check for empty fields
    if (empty($_POST["username"])) {
        $errors["username"] = "Username is required.";
    }

    if (empty($_POST["password"])) {
        $errors["password"] = "Password is required.";
    }

    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required.";
    }

    // Validate username uniqueness
    $username = $_POST["username"];
    if (isUsernameExists($username)) {
        $errors["username"] = "Username already exists. Please choose a different username.";
    }

    if (count($errors) === 0) {
        // Handle user registration
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];

        // Hash the password before storing in the database
        $hashedPassword = hashPassword($password);

        // Perform the database insert operation (replace "users" with your actual table name)
        $insertQuery = "INSERT INTO `users` (`username`, `password`, `email`) VALUES ('$username', '$hashedPassword', '$email')";

        if ($mysqli->query($insertQuery)) {
            // Show a success toast
            displayToast('Registration successful! You can now login with your credentials.', 'success');
            // Reset the form values
            $_POST["username"] = $_POST["password"] = $_POST["email"] = "";
        } else {
            // Show an error toast
            displayToast('Registration failed. Please try again.');
        }
    }
}

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Handle user login
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Retrieve the user data from the database (replace "users" with your actual table name)
    $selectQuery = "SELECT `id`, `password` FROM `users` WHERE `username` = '$username'";

    if ($result = $mysqli->query($selectQuery)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPasswordFromDB = $row["password"];
            $userId = $row["id"];

            // Verify the password
            if (verifyPassword($password, $hashedPasswordFromDB)) {
                // Login successful! Create a session and store the user ID
                $_SESSION["user_id"] = $userId;

                // Show a success toast
                displayToast('Login successful! Redirecting to the dashboard...', 'success');

                // Add a JavaScript script to redirect after displaying the toast
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "welcome.php";
                    }, 2000); // Redirect after 2 seconds
                    </script>';
            } else {
                // Show an error toast
                displayToast('Login failed. Invalid username or password.');
            }
        } else {
            // Show an error toast
            displayToast('Login failed. Invalid username or password.');
        }
    } else {
        // Show an error toast
        displayToast('Login failed. Please try again.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration and Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   
</head>
<body>
    
    <div class="container mx-auto max-w-md h-screen flex justify-center items-center">
        <div class="form-container shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <!-- Form selection buttons -->
            <center>
                <div class="custom-one">
                    <a href="?register=1" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-l focus:outline-none focus:shadow-outline">
                        Register
                    </a>  
                    <a href="?login=1" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">
                        Login
                    </a>
                </div>
            </center>

            <!-- Register Form (shown when query parameter ?register=1) -->
            <?php if (isset($_GET['register']) && $_GET['register'] === '1'): ?>
             <style>
       .custom-one{display:none;}
   </style>
                <form method="post">
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2" for="username">
                            Username
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" id="username" type="text" name="username" placeholder="Enter username">
                        <?php if (isset($errors["username"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $errors["username"]; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" id="password" type="password" name="password" placeholder="Enter password">
                        <?php if (isset($errors["password"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $errors["password"]; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" id="email" type="email" name="email" placeholder="Enter email">
                        <?php if (isset($errors["email"])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $errors["email"]; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="register">
                            Register
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- Login Form (shown when query parameter ?login=1) -->
            <?php if (isset($_GET['login']) && $_GET['login'] === '1'): ?>
             <style>
       .custom-one{display:none;}
   </style>
                <form method="post">
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2" for="username">
                            Username
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" id="username" type="text" name="username" placeholder="Enter username">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" id="password" type="password" name="password" placeholder="Enter password">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="login">
                            Login
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    

   
     <style>
        .toast {
            display: none;
            position: fixed;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            z-index: 9999;
        }

        .toast-success {
            background-color: #4CAF50;
        }

        .toast-error {
            background-color: #F44336;
        }

          body {
        background: #222831;
            background-image:url('https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit@main/Login%20Page/html-login-page/assets/img/register_bg_2.png');
          background-size: cover;
 

        }
        
       @media (max-width: 760px) {
  body {
    background: #222831;
    background-image: url('https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit@main/Login%20Page/html-login-page/assets/img/register_bg_2.png');
    background-size: cover;
  }
}

        .form-container {
            background: #2D3748;
        }

        .form-container label {
            color: #CBD5E0;
        }

        .form-container input {
            color: #CBD5E0;
        }

        .form-container .bg-orange-500 {
            background-color: #FFA500;
        }

        .form-container .bg-orange-700 {
            background-color: #DD6B20;
        }

        .form-container .hover\:bg-orange-500:hover {
            background-color: #C05621;
        }

        .form-container .hover\:bg-orange-700:hover {
            background-color: #DD6B20;
        }

        .form-container .hover\:bg-orange-500:focus {
            background-color: #C05621;
        }

        .form-container .hover\:bg-orange-700:focus {
            background-color: #DD6B20;
        }
    </style>

    <script>
        // Check if there are any error messages in the page, and show the toasts accordingly
        document.addEventListener("DOMContentLoaded", function() {
            var errorToasts = document.getElementsByClassName("toast-error");
            for (var i = 0; i < errorToasts.length; i++) {
                errorToasts[i].style.display = "block";
            }
        });
    </script>
</body>
</html>
