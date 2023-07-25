<?php
// Import config.php
require_once("config.php");

// Check if the user is logged in, if not, redirect to user.php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: user.php");
    exit();
}

// Function to fetch user's pastes
function getUserPastes($userId) {
    global $mysqli;
    $selectQuery = "SELECT * FROM `pastes` WHERE `written_by` = '$userId' ORDER BY `create_time` DESC";
    $result = $mysqli->query($selectQuery);
    $pastes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pastes[] = $row;
        }
    }
    return $pastes;
}

// Get the user ID from the session
$userId = $_SESSION["user_id"];

// Fetch the user's pastes
$pastes = getUserPastes($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            <a href="user.php" style="background:#F4C430;" class="  text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-orange-600 hover:text-white transition-colors duration-300 text-white text-base ">Register</a>
         <?php endif; ?>
        
          
        </div>
      </div>
    </div>
  </nav>
  
    <div class="container mx-auto max-w-3xl mt-10 dashboard-container rounded shadow-lg px-8 py-6">
        <h2 class="text-3xl font-bold mb-6">Dashboard</h2>

        <!-- Display user's pastes in a table -->
        <div class="table-container overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Paste ID</th>
                        <th class=" border px-4 py-2">Title</th>
                        <th class=" border px-4 py-2">Creation Date</th>
                         <th class=" border px-0 py-2">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pastes as $paste): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $paste["id"]; ?></td>
                        <td class="border px-4 py-2"><?php echo $paste["title"]; ?></td>
                        <td class="border px-4 py-2"><?php echo $paste["create_time"]; ?></td> 
                        <td class="border pl-4 py-2"><a href="/<?php echo $paste["id"]; ?>"><img width="20" height="20" src="https://img.icons8.com/ios-glyphs/30/FD7E14/visible--v1.png" alt="visible--v1"/></a></td>

                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
