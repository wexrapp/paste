<?php
// Import config.php
require_once("config.php");

if (!isset($_GET["p"])) {
    exit("Error: p not defined.");
}

$content = "";
$title = "";
$userIP = "";
$password = "";


session_start();
$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 1; // Set to 0 if not logged in


if ($stmt = mysqli_prepare($mysqli, "SELECT title, content, user_ip, password FROM pastes WHERE id = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_id);
    $param_id = $_GET["p"];

    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $temp_title, $temp_content, $temp_user_ip, $temp_password);

    while ($stmt->fetch()) {
        $title = $temp_title;
        $content = $temp_content;
        $userIP = $temp_user_ip;
        $password = $temp_password;
    }
}

// Check if password is required and if it matches
$showContent = true;
if (!empty($password) && !isset($_POST['password'])) {
    $showContent = false;
}

if ($showContent && isset($_POST['password'])) {
    $inputPassword = $_POST['password'];
    if ($inputPassword !== $password) {
        $showContent = false;
        $passwordError = "Invalid password.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
      <!-- Primary Meta Tags -->
  <title>Paste - <?php echo htmlspecialchars($title); ?></title>
  <meta name="description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta name="keywords" content="pastebox, paste, sharing, text, code snippets, free, captcha-free, fast, Paste WeXR">
     <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://paste.wexr.tech/<?php echo htmlspecialchars($param_id); ?>">
    <meta property="og:title" content="Paste WeXR - A Free Pastebox Without Captcha and Fast">
    <meta property="og:description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta property="og:image" content="https://paste.wexr.tech/og.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://paste.wexr.tech/<?php echo htmlspecialchars($param_id); ?>">
    <meta property="twitter:title" content="Paste WeXR - A Free Pastebox Without Captcha and Fast">
    <meta property="twitter:description" content="Paste WeXR is a free and fast pastebox service that allows you to share text and code snippets without the hassle of captcha challenges. Enjoy quick and easy pasting with Paste WeXR.">
    <meta property="twitter:image" content="https://paste.wexr.tech/og.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://paste.wexr.tech/favicon.png">
    
    <!-- Canonical URL (optional, if needed) -->
    <link rel="canonical" href="https://paste.wexr.tech/<?php echo htmlspecialchars($param_id); ?>">
     
     <style>
        /* Dark mode styles */
        body {
            background: #222831;
            color: #CBD5E0;
        }

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
        
        ::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    border-radius: 100px;
    border-style: solid;
    border-color: transparent;

    background: orange;
}
    </style>
</head>
<body> 
 
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
            <a href="user.php" style="background:#F4C430;" class="  text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base mr-4">Register</a>
        <?php endif; ?>
        
          
        </div>
      </div>
    </div>
  </nav>
  
  
  
   <?php if ($showContent) : ?>
  <div class="Paste">
     <div class="container mx-auto max-w-3xl mt-10 dashboard-container rounded shadow-lg px-8 py-6">
       <h1 class="text-xl font-bold"><?php echo htmlspecialchars($title); ?></h1>
    </div>
    <br/>
    
     <div class="container mx-auto max-w-3xl  dashboard-container rounded shadow-lg">
         
                         <textarea style="height: 800px;" class="px-2 shadow appearance-none dashboard-container border-none rounded w-full    leading-tight focus:outline-none focus:shadow-outline text-white " name="content" placeholder="Start typing here...." readonly><?php echo htmlspecialchars($content); ?></textarea>
 
    </div>
    
     
      <center>
           <br/>
            <style>
                #qrcode {
                    margin: 20px auto;
                    padding: 10px;
                    background-color: #fff;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                    border-radius: 5px;
                    display: inline-block;
                }
            </style>
            <div id="qrcode"></div>
            <br/>
            <button style="background:#F4C430;" class=" text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base" id="copyButton" onclick="copyURL()">Copy URL</button>
      </center>
      
  </div>
  
  
     
            

            <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
            <script>
                // Generate QR code for the current URL
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: window.location.href,
                    width: 128,
                    height: 128
                });

                // Copy URL to clipboard
                function copyURL() {
                    var url = window.location.href;
                    navigator.clipboard.writeText(url)
                        .then(function() {
                            alert("URL copied to clipboard!");
                        })
                        .catch(function(error) {
                            console.error("Failed to copy URL: ", error);
                        });
                }
            </script> 
            
            </div>
        <?php else: ?>
        
        
        
        <div   class="container mx-auto max-w-md h-screen flex justify-center items-center">
    <div  style="background: #2D3748;" class="form-container shadow-md rounded px-8 pt-6 pb-8 mb-4">
       <form method="POST" action="">
                                   <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline text-gray-700" type="text" name="password" placeholder="Enter Password">
 <br/>
 <div class=" pt-10"></div>
     <center> 
                        <button style="background:#F4C430;" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="login">
                           Submit
                        </button>
                        </center>
                    
           </form>
    </div>
</div>
 
        <?php endif; ?>
    </div>
    
   
    <div class="footer"> 
    </div>
      <style>
    /* Styling for the footer */
    footer {
        
        text-align: center;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    /* Styling for the powered by link */
    footer a {
         text-decoration: none;
        font-weight: bold;
    }

  
</style>

<footer>
    <div>
        Powered by <a href="https://wexr.tech" target="_blank"  >WeXR.tech</a>
    </div>
</footer>

</body>
</html>
