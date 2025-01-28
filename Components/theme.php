<?php
// require_once("../config.php"); 
$userId = $_SESSION['user_id']; 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}
////[Theme Type]/////////-->Header
//Update Theme selected into users database
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['themeType']))
{
    $themeType = $_POST['themeType'];
    $_SESSION['themeType'] = $themeType; // Save in session for persistence

    $sql = "UPDATE users SET preferred_theme = ? 
            WHERE user_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $themeType, $userId);
    $stmt->execute();
    $stmt->close();
}

//Invoke the preferred theme from users database
$sql = "SELECT preferred_theme FROM users
        WHERE user_id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc())
{
    $_SESSION['themeType'] = $row['preferred_theme'];
} else {
    $_SESSION['themeType'] = 'SkyLight'; //Default theme
}
$stmt->close();

$theme = $_SESSION['themeType'];
if ($theme == 'SkyLight')
{
    $theme = "skyLightStyle.css";
} else if ($theme == 'PinkCharm') {
    $theme = "pinkCharmStyle.css";
} else if ($theme == 'Midnight') {
    $theme = "midnightStyle.css";
}

////[Theme Type]/////////-->Bottom
?>
