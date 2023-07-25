<?php
// Import config.php
require_once("config.php");

// Function to generate a unique ID of specified length
function generateUniqueID($length)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $id = '';

    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $id .= $characters[$index];
    }

    return $id;
}

// Function to get user's IP address
function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Check IP from shared internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Check IP passed from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Get IP address from remote address
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Check if the user is logged in (user ID is stored in the session)
session_start();
$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 1; // Set to 0 if not logged in

// Handle form submission for creating a new paste
if (isset($_POST["content"])) {
    // Get ID for paste
    $id = generateUniqueID(4);

    $title = isset($_POST["title"]) ? $_POST["title"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Prepare the SQL statement
    $sql = "INSERT INTO `pastes`(`id`, `title`, `content`, `password`, `user_ip`, `written_by`) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssi", $param_id, $param_title, $param_content, $param_password, $param_user_ip, $param_written_by);

        $param_id = $id;
        $param_title = $title;
        $param_content = $_POST["content"];
        $param_password = $password;
        $param_user_ip = getUserIP();
        $param_written_by = $userId; // Set to 0 if user not logged in

        if (mysqli_stmt_execute($stmt)) {
            // Check if the paste was successfully created
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Redirect to the paste URL (ID)
                header("Location: {$id}");
                exit();
            } else {
                echo (CREATE_INSERT_FAILED);
            }
        } else {
            echo (CREATE_INSERT_FAILED);
        }

        mysqli_stmt_close($stmt);
    }
} else {
    // Content not defined, do nothing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Primary Meta Tags -->
    <title>Paste WeXR - A Free Pastebox Without Captcha and Fast</title>
    <meta name="description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta name="keywords" content="pastebox, paste, sharing, text, code snippets, free, captcha-free, fast, Paste WeXR">
     <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://paste.wexr.tech/">
    <meta property="og:title" content="Paste WeXR - A Free Pastebox Without Captcha and Fast">
    <meta property="og:description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta property="og:image" content="https://paste.wexr.tech/og.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://paste.wexr.tech/">
    <meta property="twitter:title" content="Paste WeXR - A Free Pastebox Without Captcha and Fast">
    <meta property="twitter:description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta property="twitter:image" content="https://paste.wexr.tech/og.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://paste.wexr.tech/favicon.png">
    
    <!-- Canonical URL (optional, if needed) -->
    <link rel="canonical" href="https://paste.wexr.tech/"> 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add any custom styles here */
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
        
       body {
            background: #222831;
            background-image:url('https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit@main/Login%20Page/html-login-page/assets/img/register_bg_2.png');
          background-size: contain;
 

        }
        
       @media (max-width: 760px) {
  body {
    background: #222831;
    background-image: url('https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit@main/Login%20Page/html-login-page/assets/img/register_bg_2.png');
    background-size: cover;
  }
}

            
     
    </style>
</head>
<body>




  <!-- Hero Section -->
  <header style="height:100vh;" class=" ">
   <nav class="py-4 shadow-md">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between">
        <div class="text-2xl font-semibold">
          <a href="/" class="text-white">Paste</a>
        </div>
        <div class="flex space-x-4">
           <!-- User avatar or Register button -->
        <?php if ($userId !== 1) : ?>
            <!-- User is logged in, show user avatar -->
            <a href="welcome.php"  style="background:#F4C430;" class="  text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base ">Dashboard</a>
            
                 <a style="background:#F4C430;" class="  text-white px-2 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base " href="logout.php"><img  width="25" height="25" src="https://img.icons8.com/ios/50/40C057/exit--v1.png" alt="exit--v1"/></a>
      
        <?php else : ?>
            <!-- User is not logged in, show register button -->
            <a href="user.php" style="background:#F4C430;" class="  text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base ">Register</a>
         <?php endif; ?>
        
          
        </div>
      </div>
    </div>
  </nav>
    <div class="py-36 container mx-auto px-4">
      <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-5xl md:text-6xl font-semibold mb-6 text-white">Welcome to Paste</h1>
        <p class="text-xl md:text-2xl text-gray-300">The ultimate tool to simplify your code or text pasting & sharing experience.</p>
        <div class="mt-10">
          <a href="#paste"
            class="bg-orange-500 text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300">Paste Now</a>
        </div>
      </div>
    </div>
  </header>
  
  
  <div id="paste" style="min-height:100vh; " class="px-2">

    <style>
       

        .dashboard-container {
            background: #2D3748;
        }

        .dashboard-container h2 {
            color: #CBD5E0;
        }

        .dashboard-container .table-container {
            background: #2D3748;
        }

        .dashboard-container .table-container table {
            color: #CBD5E0;
        }

        .dashboard-container .table-container th {
            color: #CBD5E0;
        }

        .dashboard-container .table-container td {
            border-color: #4A5568;
        }
    </style> 
     <h2 class="text-3xl font-bold pt-10 mb-6"><center>Add Your Paste</center></h2>
    <div   class="container mx-auto max-w-3xl mt-10 dashboard-container rounded-lg shadow-lg px-2 py-6">
        

        <form method="POST" action="index.php" autocomplete="off">
             
                <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700 mb-4" type="text" name="title" placeholder="Title (optional)">
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700 mb-4 h-56" name="content" placeholder="Start typing here...."></textarea>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700 mb-4" type="password" name="password" placeholder="Password (optional)">
                <button style="background:#F4C430;"  class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Paste</button>
            </div>
        </form>
         
    </div> 
    </div>
      <?php if ($userId !== 1) : ?>

      
 <?php else : ?> 
   <!-- Call-to-Action Section -->
  <section class="bg-indigo-700 py-16 text-white">
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-semibold mb-6">Ready to simplify your copy-pasting experience?</h2>
        <p class="text-xl md:text-2xl text-gray-300 mb-8">Join thousands of satisfied users and get started with Paste
          today.</p>
        <div>
          <a href="user.php?register=1"
            class="bg-orange-500 text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300">Sign Up for Free</a>
        </div>
      </div>
    </div>
  </section>
        
        <?php endif; ?>
  <!-- Footer Section -->
  <footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4">
      <div class="text-center">
        <p>&copy; 2023 WeXR Technologies</p>
      </div>
    </div>
  </footer>
</body>
</html>
