<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
{
        header("Location: /secure");
}
// require_once("index.php"); 
$userId = $_SESSION['user_id'];

// Set Cache-Control to no-cache, no-store, must-revalidate
header("Cache-Control: no-cache, no-store, must-revalidate");
// Set Pragma to no-cache (for HTTP/1.0 backward compatibility)
header("Pragma: no-cache");
// Set Expires to a past date to invalidate the cache
header("Expires: 0");

require_once("config.php"); 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $firstName = $row['first_name'];
} else {
    $firstName = "Guest"; // Default value if not found
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Cost</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="shoppingCart2.png">
</head>
<body>
    <div id="navigation">
        <div class='webTitle'>Market Cost</div>
        <div id='logOutSection'>
            <div class='greeting'>Greeting, <?php echo $firstName;?>!</div>
            <div>
                <form action="" method='get'>
                    <button type='submit' name='logOut' class='logOutButton'><i class='bx bx-log-out' ></i></button>
                </form>
            </div>
                    <?php
                        if (isset($_GET['logOut'])) {
                            $_SESSION = []; // Clear all session data
                            session_destroy(); // End the session
                            header("Location: ../secure");
                            exit();
                        }                    
                    ?>
        </div>
    </div>

    <div id="bottom">
        <div id="dataChange">
            <div class="insert">
                <div class='newTransactionLabel'>
                    <button class='newTransactionStyle'>New Transaction</button>
                </div>
                
                <form method='post'>
                    <div class='form-row'>
                        <button for='date' class='insertLabel dateLabel' >Date</button>
                        <input type='date' name='date' id='date' class='insertBox inserBoxDate' required>
                    </div>

                    <div class='form-row'>
                        <button for='store' class='insertLabel interface'><i class="fas fa-store"></i> Market</button>
                        <!-- <input type='text' name='store' id='store' class='insertBox'> -->
                        <input list="store" name='store' class='insertBox' required>
                        <datalist id="store">
                    <?php
                        $sql = "SELECT distinct market_name
                        FROM market_cost
                        WHERE user_id = $userId
                        ORDER BY market_name ASC";

                        $result = $conn->query($sql);

                        if($result->num_rows > 0)
                        {
                            while($row = $result->fetch_assoc())
                            {
                                echo "<option value='" . $row['market_name'] . "'></option>";
                            }
                        }
                    ?>
                        </datalist>
                    </div>

                    <div class='form-row'>
                        <button for='fbCost' class='insertLabel '>Food & Beverage Cost</button>
                        <input type='number' name='fbCost' id='fbCost' placeholder="$" class='insertBox insertBoxFBCost'>
                    </div>

                    <div class='form-row'>
                        <button for='otherCost' class='insertLabel'>Other Cost</button>
                        <input type='number' name='otherCost' id='otherCost' placeholder="$" class='insertBox insertBoxOtherCost'>
                    </div>

                    <div class='insertButtonDiv'>
                        <button type='submit' name='insert' class='insertButton'>Insert</button>
                    </div>
                </form>
            </div>

<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST) == 'insert')
    {
        // $userId on the top of the file
        $visitDate = $_POST['date'] == "" ? "N/A" : $_POST['date'];
        $marketName = $_POST['store']; //Need to convert marketName into market_id to push into market_cost table
        $FBCost = $_POST['fbCost'] == '' ? 0 : $_POST['fbCost'];
        $otherCost = $_POST['otherCost'];

        if (is_numeric($FBCost) && is_numeric($otherCost))
        {
            $insertQuery = "INSERT INTO market_cost (user_id, visit_date, market_name, food_bev_cost, other_cost) 
            VALUES (?, ?, ?, ?, ?)";
            $statement = $conn->prepare($insertQuery);
            $statement->bind_param('dssdd', $userId, $visitDate, $marketName, $FBCost, $otherCost);
            $statement->execute();
            $statement->close();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); 
        } else {
            header("Location: " . "/warning");
            exit(); 
        }

    }
}
?>

<?php
// Set default year and month to current values if not provided
$yearSelected = isset($_GET['year']) ? $_GET['year'] : date('Y');
$monthSelected = isset($_GET['month']) ? $_GET['month'] : date('m');
?>

            <div class="budget">
                <div class='budgetNotice'>
                    <div class='budgetNotification'>
                        <?php 
                            $sql = "SELECT amount
                            FROM budget
                            WHERE user_id = $userId
                            AND year = $yearSelected
                            AND month = $monthSelected";
    
                            $result = $conn->query($sql);
                            // $amoutBool =
                            $currentBudget = NULL;
                            if($result->num_rows > 0)
                            {
                                $row = $result->fetch_assoc();
                                $currentBudget = $row['amount'];
                                echo "You have set the budget for this month is: " . "<div id='currentBudgetSetSection'><div class='currentSetBudgetColor'>$" . $currentBudget . "</div>
                                                                                        <div class='checkIcon'><i class='fas fa-check'></i></div></div>";
                            } else {
                                echo "You have not set the budget for this month!";
                                echo "<div id='messageReminder'><i class='fas fa-exclamation-triangle'></i></div>";
                            }
                        ?>
                        
                    </div>
                    <input type="text" id='userInfo' class='hidden' value='<?php echo $userId ?>'>
                    <input type="text" id='currentBudget' class='hidden' value='<?php echo $currentBudget ?>'>
                            <!-- <div class='test'>Hello</div> -->
                </div>
                <div class='newTransactionLabel'>
                    <button class='setBudgetButton' onclick="setBudget()">Set Budget</button>
                </div>
                <div class='newTransactionLabel'>
                    <button class='showBudgetButton'>Show Budget</button>
                </div>
            </div>
        </div>

        <div id="dataAnalyze">
            <div class="filter">
                <form method='get' class='yearMonthDiv'>
                    <label for="year" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> Year:  </label>
                        <select name="year" class='yearOptionStyle'id='year'>
                    <?php
                        $sql = "SELECT distinct DATE_FORMAT(visit_date, '%Y') AS year
                                FROM market_cost
                                WHERE user_id = $userId
                                ORDER BY market_name DESC";

                        $result = $conn->query($sql);

                        if($result->num_rows > 0)
                        {
                            // echo "<option value='" . date('Y') . "'>" . date('Y') . "</option>";
                            while($row = $result->fetch_assoc())
                            {
                                if ($row['year'] == $yearSelected) {
                                    echo "<option value='" . $row['year'] . "' selected >" . $row['year'] . "</option>";
                                } else {
                                    echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                                }
                            }
                            echo "<option value=''>All Years</option>";
                        }
                    ?>
                        </select>
                    <label for="month" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> Month: </label>
                        <select name="month" class='monthOptionStyle' id='month'>
                            <?php
                                $monthSelectedByUser = $row['month'] == $monthSelected ? 'selected' : '';
                                for ($i = 1; $i < 13; $i++)
                                {
                                    $monthModified = '';
                                    if ($i < 10)
                                    {
                                        $monthModified = "0" . $i;
                                    } else {
                                        $monthModified = $i;
                                    }

                                    if ($i == $monthSelected)
                                    {
                                        echo "<option value='" . $monthModified . "' selected >" . "" . $monthModified . "</option>";
                                    } else {
                                        echo "<option value='" . $monthModified . "'>" . "" . $monthModified . "</option>";
                                    }
                                };
                            ?>
                                <option value="">All Months</option>
                        </select>
                        <button type="submit" name='filter' class='filterButton'><i class="fa fa-filter"></i></button>
                </form>
            </div>
<?php

?>
            <div class="header">
                <table class='headerTable'>
                    <thead>
                        <tr class='headerRow'>
                            <th class='fontStyle expand wrapText tdLength'>Day of Week</th>
                            <th class='fontStyle expand tdLength'>Date</th>
                            <th class='fontStyle expand tdLength'>Store</th>
                            <th class='fontStyle expand wrapText tdLength'>Food & Beverage Cost</th>
                            <th class='fontStyle expand wrapText tdLength'>Other Cost</th>
                            <th class='fontStyle expand tdLength actionStyle' colspan='2'><i class='fa fa-cog fa-spin'></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="content">
<?php
////////////
// if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET) == 'filter')
// {
//     $yearSelected = $_GET['year'];
//     $monthSelected = $_GET['month'];

// Set default year and month to current values if not provided
// $yearSelected = isset($_GET['year']) ? $_GET['year'] : date('Y');
// $monthSelected = isset($_GET['month']) ? $_GET['month'] : date('m');
/////////////////
$sql = "SELECT market_cost_id as mcID, DATE_FORMAT(visit_date, '%W') AS DayOfWeek, DATE_FORMAT(visit_date, '%m/%d/%Y') as ShoppingDate, market_name, food_bev_cost, other_cost 
        FROM market_cost mc
        WHERE user_id = ? 
        AND deleted = 'N' ";

if ($yearSelected == '' && $monthSelected == '')
{
    $sql .= "ORDER BY visit_date DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
}

if ($yearSelected && $monthSelected)
{
    $sql .= "AND DATE_FORMAT(visit_date, '%Y') = ?
            AND DATE_FORMAT(visit_date, '%m') = ?
            ORDER BY visit_date DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
}
$statement->execute();
        $result = $statement->get_result();

        if($result->num_rows > 0)
        {
            echo "<table border='1'>";
                while($row = $result->fetch_assoc())
                {
                    $numFBColor = $row['food_bev_cost'] == 0 ? 'silverNumber' : 'blueNumber';
                    $numOtherColor = $row['other_cost'] == 0 ? 'silverNumber' : 'blueNumber';
                    echo "<tr class='rowHighLight fontStyle' id= " . $row['mcID'] . ">
                            <td class='expand tdLength'>" . $row['DayOfWeek'] . "</td>
                            <td class='expand tdLength'>" . $row['ShoppingDate'] . "</td>
                            <td class='expand tdLength'>" . $row['market_name'] . "</td>
                            <td class='expand tdLength totalCostStyle " . $numFBColor . "'>" . number_format($row['food_bev_cost'],2). "</td>
                            <td class='expand tdLength totalCostStyle " . $numOtherColor . "'>" . number_format($row['other_cost'],2). "</td>
                            <td class='gridTableColor dataEditStyle'>
                                    <button id=" ."edit_". $row['mcID'] . " onclick='editClick(".$row['mcID'].", event)' name='edit' class='editSubmitStyle'><i class='fas fa-pen'></i></button>
                            </td>
                            <td class='gridTableColor dataDeleteStyle'>
                                    <button id=" ."delete_" . $row['mcID'] . " name='delete' class='deleteSubmitStyle'><i class='fas fa-trash-alt'></i></button>
                            </td>";
                }
            echo "</table>";
        }
        $statement->close();
////////////////
?>
            </div>
            <div class="totalSummary">
<?php
    $sqlTotal = "SELECT SUM(mc.food_bev_cost) AS totalFBCost, SUM(mc.other_cost) AS totalOtherCost 
                FROM market_cost mc
                WHERE user_id = ?
                AND deleted = 'N' ";

if ($yearSelected && ($monthSelected == '')) {
    // Only year is selected
    $sqlTotal .= "AND DATE_FORMAT(visit_date, '%Y') = ?";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('is', $userId, $yearSelected);
} elseif ($yearSelected && $monthSelected) {
    // Both year and month are selected
    $sqlTotal .= " AND DATE_FORMAT(visit_date, '%Y') = ? 
                   AND DATE_FORMAT(visit_date, '%m') = ?";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
} else {
    // No filters
    $sqlTotal .= " ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('i', $userId);
}
    

    // $statement = $conn->prepare($sqlTotal);
    // $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
    $statement->execute();
    // $statement->execute();
        $resultTotal = $statement->get_result();

    // $resultTotal = $conn->query($sqlTotal);

    if($resultTotal->num_rows > 0)
    {
        echo "<table border='1' class='gridTableColor'>";
        while($row = $resultTotal->fetch_assoc())
        {
            $totalFBCost = $row['totalFBCost'];
            echo "<tr class='fontStyle'>
                <td class='gridTableColor redundantCol'></td> 
                <td class='gridTableColor totalCostLabel totalCostColor' colspan='2'>Total Cost</td> 
                <td class='gridTableColor totalCostStyle totalCostTextStyle totalCostColor'>$" . number_format($row['totalFBCost'],2) . "</td> 
                <td class='gridTableColor totalCostStyle totalCostTextStyle totalCostColor'>$" . number_format($row['totalOtherCost'],2) . "</td> 
                <td class='gridTableColor redundantIdStyle dataEditStyle'></td>
                <td class='gridTableColor redundantIdStyle dataDeleteStyle'></td>";
        }
    }
    $budget = 0;
    if (is_null($currentBudget) != 1)
    {
        $budget = $currentBudget;
    }
    $balanceForShopping = $budget - $totalFBCost;
    echo "<tr class='fontStyle'>
    <td class='gridTableColor redundantCol'></td> 
    <td class='gridTableColor totalCostLabel totalBudgetColor' colspan='2'>Total Budget</td> 
    <td class='gridTableColor budgetStyle totalCostStyle totalCostTextStyle totalBudgetColor'>$" . number_format($budget,2) . "</td>
    <td class='gridTableColor redundantIdStyle dataEditStyle' colspan='3'></td>
    </tr>";

    echo "<tr class='fontStyle'>
    <td class='gridTableColor redundantCol'></td> 
    <td class='gridTableColor totalCostLabel totalBalanceColor' colspan='2'>Balance for Shopping</td> 
    <td class='gridTableColor balanceStyle totalCostStyle totalCostTextStyle totalBalanceColor'>$" . number_format($balanceForShopping,2) . "</td>
    <td class='gridTableColor redundantIdStyle dataEditStyle' colspan='3'></td>
    </tr></tfoot>";      

    echo "</table>";

?>
            </div>
        </div>

        <div id="graph">Graph</div>
    </div>


    <script src="script.js"></script>
</body>
</html>
