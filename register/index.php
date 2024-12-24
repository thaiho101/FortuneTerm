<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <title>Market Cost</title>
    <link rel="stylesheet" href="/secure/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/Icon/Moneybag.png">
</head>

<body>
    <div>
        <form action="" method='post'>
            <div id='firstNameSection'>
                <label for='firstName' class='NameLabel'>First Name</label>
                <input type='text' name='firstName' id='firstName' placeholder="Enter your first name" class='inputBox' required>
            </div>
            <div id='lastNameSection'>
                <label for='lastName' class='NameLabel'>Last Name</label>
                <input type='text' name='lastName' id='lastName' placeholder="Enter your last name" class='inputBox' required>
            </div>
            <div id='emailSection'>
                <label for='email' class='NameLabel'>Email</label>
                <input type='email' name='email' id='email' placeholder="Enter your email" class='inputBox' required>
            </div>
            <div id='passwordSection'>
                <label for='password' class='NameLabel'>Password</label>
                <input type='password' name='password' id='password' placeholder="Enter your password" class='inputBox' minlength="6" required>
            </div>
            <div id='signUpSection'>
                <button type='submit' name='signUp' id='signUpButton'>Sign up</button>
            </div>
        </form>
    </div>

<?php
require_once('../config.php');
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

        $insertQuery = "INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)";
        $statement = $conn->prepare($insertQuery);

        if ($statement) {
            $statement->bind_param('ssss', $email, $hashedPassword, $firstName, $lastName);

            if ($statement->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<div class='error'>Error: " . $conn->error . "</div>";
            }
            $statement->close();
        } else {
            echo "<div class='error'>Error preparing statement: " . $conn->error . "</div>";
        }
    }
}
?>

</body>
</html>
