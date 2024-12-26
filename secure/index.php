<!DOCTYPE html>
<?php
if(session_status() == PHP_SESSION_NONE)
{
        session_start();
}
require_once('../config.php');

?>
<html lang='en'>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Market Cost</title>
        <link rel="stylesheet" href="/secure/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="/Icon/Moneybag.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body id='wholePage'>
        <div id='navBar'><a href='../' id='homeLink'>Market Cost</a></div>
        <div id='loginParent'><div id='loginPanel'>
                <form method='post'>
                        <div class='loginLabel'>Login</div>

                        <div class='emailSection'>
                                <div class='userIcon'>
                                        <label for='emailNav'>
                                                <i class='bx bx-user'></i>
                                        </label>
                                </div>
                                <input type="text" id='emailNav' name='email' placeholder='Email' class='emailInputBox' required>
                        </div>

                        <div class='passwordSection'>
                                <div class='passwordIcon'>
                                        <label for="passwordNav">
                                                <i class='bx bxs-lock-alt ' ></i>
                                        </label>
                                </div>
                                <input type="password" id='passwordNav' name='password' placeholder="Password" class='passwordInputBox' required>
                        </div>

                        <div class='loginSection'>
                                <button type='submit' name='loginButton' class='loginButton'>Login</button>
                        </div>

                        <div class='registerSection'>
                                <div>Don't have an account?</div>
                                <a href='/register' class='registerLink'>Register</a>
                        </div> 
                </form>
        </div></div>
</body>

<?php


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Connect to the database
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Use a prepared statement for security
        $stmt = $conn->prepare("SELECT user_id, email, password, first_name, last_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['first_name'] = $row['first_name'];

                // Redirect to the homepage
                header("Location: ../");
                exit();
            } else {
                echo "<div class='message'>Your email or password are not correct. Please try again!</div>";
            }
        } else {
            echo "<div class='message'>No account found with this email.</div>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}


?>

</html>
