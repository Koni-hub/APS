<!DOCTYPE html>
<html lang="en">
<?php
// Connect to the database
include ('./db_connect.php');

// Function to sanitize input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Function to log user login
function logUserLogin($user_id, $username)
{
    global $conn;

    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $deviceInfo = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $conn->prepare("INSERT INTO user_audit_trails (user_id, username, ip_address, device_info) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $username, $ipAddress, $deviceInfo);
    $stmt->execute();
    $stmt->close();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and house ID are provided
    if (isset($_POST["email"]) && isset($_POST["house_id"])) {
        $email = sanitizeInput($_POST["email"]);
        $house_id = sanitizeInput($_POST["house_id"]);

        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email FROM tenants WHERE email=? AND house_id=?");
        $stmt->bind_param("ss", $email, $house_id);
        
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Tenant exists, redirect to dashboard
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();

            // Log the login attempt
            logUserLogin($user_id, $username);

            header("Location: dashboard.php?user_id=" . $user_id);
            exit();
        } else {
            $error_message = "Invalid email or house ID";
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, gray 0%, gray 50%, gray 100%);
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-page {
            background-image: url('pictures/sharlogs.png');
            background-size: cover;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
        }

        .login-heading {
            text-align: center;
            color: white;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: none;
        }

        input[type="email"],
        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #0056b3;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #004080;
        }

        @media only screen and (min-width: 768px) {
            .login-page {
                width: 400px;
            }
        }
    </style>
</head>

<body>
    <div class="login-page">
        <h1 class="login-heading">Login</h1>

        <?php if (isset($error_message)): ?>
            <p style="color: red;">
                <?php echo $error_message; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="house_id" placeholder="House ID" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>