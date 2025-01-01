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

    <script src="https://www.gstatic.com/charts/loader.js"></script>


    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <div id="navigation">
        <div id='navBar'><a href='index.php' id='homeLink'>Market Cost</a></div>
        <div id='logOutSection'>
            <div class='greeting'>Welcome, <?php echo $firstName;?>!</div>
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
                    <a class='newTransactionStyle'  onclick="focusOnDate()">New Transaction</a>
                </div>
                
                <form method='post'>
                    <div class='form-row'>
                        <button for='date' class='insertLabel dateLabel' >Date</button>
                        <input type='date' name='date' id='date' class='insertBox inserBoxDate' required>
                    </div>

                    <div class='form-row'>
                        <button for='store' class='insertLabel interface'><i class="fas fa-store"></i> Market</button>

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
                        <input type='number' name='fbCost' id='fbCost' placeholder="$" class='insertBox insertBoxFBCost' step="0.01">
                    </div>

                    <div class='form-row'>
                        <button for='otherCost' class='insertLabel'>Other Cost</button>
                        <input type='number' name='otherCost' id='otherCost' placeholder="$" class='insertBox insertBoxOtherCost' step="0.01">
                    </div>

                    <div class='insertButtonDiv'>
                        <button type='submit' name='insert' class='insertButton'>Insert</button>
                    </div>
                </form>
            </div>

<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['insert']))
    {
        // $userId on the top of the file
        $visitDate = $_POST['date'];
        $marketName = $_POST['store']; //Need to convert marketName into market_id to push into market_cost table
        // $FBCost = $_POST['fbCost'] == '' ? 0 : $_POST['fbCost'];
        // $otherCost = $_POST['otherCost'] == '' ? 0 : $_POST['otherCost'];
        $FBCost = $_POST['fbCost'];
            if ($FBCost == '')
            {
                    $FBCost = 0;
            }
        $otherCost = $_POST['otherCost'];
            if ($otherCost == '')
            {
                    $otherCost = 0;
            }

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
                            WHERE user_id = ?
                            AND year = ?
                            AND month = ?";

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('iss', $userId, $yearSelected, $monthSelected);
                            $stmt->execute();
                            $result = $stmt->get_result();
    
                            // $result = $conn->query($sql);
                            // $amoutBool =
                            $currentBudget = NULL;
                            if($result->num_rows > 0)
                            {
                                $row = $result->fetch_assoc();
                                $currentBudget = $row['amount'];
                                echo "You have set the budget for this month is: " . "<div id='currentBudgetSetSection'><div class='currentSetBudgetColor'>$" . $currentBudget . " &#9989;</div>
                                                                                        </div>";
                            } else {
                                echo "You have not set the budget for this month!";
                                echo "<div id='messageReminder'><i class='fas fa-exclamation-triangle'></i></div>";
                            }

                            $stmt->close();
                        ?>
                        
                    </div>
                    <input type="text" id='userInfo' class='hidden' value='<?php echo $userId ?>'>
                    <input type="text" id='currentBudget' class='hidden' value='<?php echo $currentBudget ?>'>

                </div>
                <div class='newTransactionLabel'>
<?php 
if($yearSelected == '' || $monthSelected == '')
{
    $hiddenClass = 'hidden';
}
                echo "<button class='setBudgetButton $hiddenClass' onclick='setBudget()'>Set Budget</button>"
?>
                    <!-- <button class='setBudgetButton' onclick="setBudget()">Set Budget</button> -->
                </div>
                <div class='newTransactionLabel'>
                    <form method='post'>
                        <button id='openModal' type='button' name='showBudget' class='showBudgetButton' onclick="document.getElementById('showBudgetModal').showModal()">Show Budget</button>
                    </form>
                    <dialog id='showBudgetModal'>
                            <h2 id='budgetSummaryTitle'>Your Budget Summary</h2>
                            <div id='showBudgetContent'>
<?php
                        if ($yearSelected == '')
                        {
                            echo "The [Year] filter must be specified.";
                        }
                        $sql = "SELECT year, month, amount
                            FROM budget
                            WHERE user_id = ?
                            AND year = ?
                            ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('is', $userId, $yearSelected);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if($result->num_rows > 0)
                        {
                            echo "<table border='1'>";
                            echo "<th class='titleBackgroundShowBudget yearWidth'>Year</th>
                                    <th class='titleBackgroundShowBudget monthWidth'>Month</th>
                                    <th class='titleBackgroundShowBudget'>Budget</th>";
                            
                            $dataLoop = [];

                            while($row = $result->fetch_assoc())
                            {
                                $dataLoop[$row['month']] = $row;
                            }
                            
                            $totalBudget = 0;
                            for ($monthBudget = 1; $monthBudget <= 12; $monthBudget++)
                            {
                                if (isset($dataLoop[$monthBudget]))
                                {
                                    $row = $dataLoop[$monthBudget];
                                    echo "<tr class='budgetSummaryAlign'>
                                    <td class='budgetSymmaryWidth'>" . $row['year'] . "</td>
                                    <td class='budgetSymmaryWidth monthWidth'>" . $monthBudget . "</td>
                                    <td class='budgetSymmaryWidth blueBudgetSet'>" . $row['amount'] . " &#9989</td>
                                    </tr>";
                                    $totalBudget += $row['amount'];
                                } else {
                                    echo "<tr class='budgetSummaryAlign'>
                                    <td class='budgetSymmaryWidth'>" . $yearSelected . "</td>
                                    <td class='budgetSymmaryWidth monthWidth'>" . $monthBudget . "</td>
                                    <td class='budgetSymmaryWidth awaitingInput'>Awaiting input &#8644;</td>
                                    </tr>";
                                }
                            }
                            echo "<tr class='budgetSummaryAlign titleBackgroundShowBudget'><td>{$row['year']}</td>
                                    <td class='titleBackgroundShowBudget'>Total Budget</td>
                                    <td class='blueBudgetSet titleBackgroundShowBudget'>" . number_format($totalBudget,2) . "</td></tr>";
                            echo "</table>";
                        }
?>
</div>
                            <div id='closeButtonShowBudgetSection'>
                                <button id='closeButtonShowBudget' onclick="document.getElementById('showBudgetModal').close()">Close</button>
                            </div>
                    </dialog>
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
                                WHERE user_id = ?
                                AND deleted = 'N'
                                ORDER BY year DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
////[Year Selected]//////////-->Header
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

                            $allYearSelected = ($yearSelected == '') ? 'selected' : '';
                            echo "<option value='' $allYearSelected >All Years</option>"; 
                        }
////[Year Selected]/////////-->Bottom
                    ?>
                        </select>
                    <label for="month" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> Month: </label>
                        <select name="month" class='monthOptionStyle' id='month'>
                            <?php
////[Month Selected]/////////-->Header
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

                                $allMonthSelected = ($monthSelected == '') ? 'selected' : '';
                                echo "<option value='' $allMonthSelected>All Months</option>"; 
////[Month Selected]/////////-->Bottom
                            ?>
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
//// Year and Month Filter ////////////////// -->Head
$sql = "SELECT market_cost_id as mcID, DATE_FORMAT(visit_date, '%W') AS DayOfWeek, DATE_FORMAT(visit_date, '%m/%d/%Y') as ShoppingDate, market_name, food_bev_cost, other_cost 
        FROM market_cost mc
        WHERE user_id = ? 
        AND deleted = 'N' ";

if ($yearSelected == '' && $monthSelected == '')
{
    $sql .= "ORDER BY visit_date DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && $monthSelected) {
            $sql .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                    AND DATE_FORMAT(visit_date, '%m') = ?
                    ORDER BY visit_date DESC";
            $statement = $conn->prepare($sql);
            $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
        } else if ($yearSelected && $monthSelected == '') {
                    $sql .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                            ORDER BY visit_date DESC";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param('is', $userId, $yearSelected);
                } else if ($yearSelected == '' && $monthSelected) {
                            $sql .= "AND DATE_FORMAT(visit_date, '%m') = ?
                                    ORDER BY visit_date DESC";
                            $statement = $conn->prepare($sql);
                            $statement->bind_param('is', $userId, $monthSelected);
                        }
//// Year and Month Filter ////////////////// -->Bottom
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

?>
            </div>
            <div class="totalSummary">
<?php
//// SUM Filter ////////////-->Header
$sqlTotal = "SELECT SUM(mc.food_bev_cost) AS totalFBCost, SUM(mc.other_cost) AS totalOtherCost 
            FROM market_cost mc
            WHERE user_id = ?
            AND deleted = 'N' ";
if ($yearSelected == '' && $monthSelected == '') {
    // All years and months are selected
    $sqlTotal .= " ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && ($monthSelected == '')) {
    // Only year is selected
    $sqlTotal .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                    ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('is', $userId, $yearSelected);
} else if (($yearSelected == '') && $monthSelected) {
    // Only month is selected
    $sqlTotal .= "AND DATE_FORMAT(visit_date, '%m') = ?
                    ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('is', $userId, $monthSelected);
} else if ($yearSelected && $monthSelected) {
    // Both year and month are selected
    $sqlTotal .= " AND DATE_FORMAT(visit_date, '%Y') = ? 
                   AND DATE_FORMAT(visit_date, '%m') = ?
                   ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
}
////SUM Filter////////////-->Bottom  

$statement->execute();
$resultTotal = $statement->get_result();

    // $resultTotal = $conn->query($sqlTotal);

    if($resultTotal->num_rows > 0)
    {
        echo "<table border='1' class='gridTableColor'>";
        while($row = $resultTotal->fetch_assoc())
        {
            $totalFBCost = $row['totalFBCost'];
            $totalOtherCost = $row['totalOtherCost'];
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
    $statement->close();
?>
            </div>
        </div>

        <div id="graph">
            <div id="marketVisitedChart" style="width: 100%; height: 300px;"></div>
            <div id="costDistributionChart" style="width:100%; max-width:600px; height:250px;"></div>
        </div>
    </div>
<?php
// Fetch data from SQL for the CHART of VISIT the most
$sql = "SELECT market_name, COUNT(market_name) as total_visits
        FROM market_cost
        WHERE user_id = ?
        AND deleted = 'N'";
//// CHART Filter [The MOST VISITED MARKET CHART]//////-->Header
if (($yearSelected == '') && ($monthSelected == '')) {
    //[All Years + All Months]
    $sql .= " GROUP BY market_name
             ORDER BY total_visits DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && ($monthSelected == '')) {
        //[Year selected and All Months]
        $sql .= " AND DATE_FORMAT(visit_date, '%Y') = ?
                GROUP BY market_name
                ORDER BY total_visits DESC";
        $statement = $conn->prepare($sql);
        $statement->bind_param('is', $userId, $yearSelected);
        } else {
            //[Year selected and month selected]
            $sql .= " AND DATE_FORMAT(visit_date, '%Y') = ?
                    AND DATE_FORMAT(visit_date, '%m') = ?
                    GROUP BY market_name
                    ORDER BY total_visits DESC";
            $statement = $conn->prepare($sql);
            $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
            }
//// CHART Filter [The MOST VISITED MARKET CHART]//////-->Bottom
$statement->execute();
$result = $statement->get_result();

$chartData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData[] = [$row['market_name'], (int)$row['total_visits']];
    }
}

// Convert PHP array to JSON
$chartDataJson = json_encode($chartData);
$statement->close();
?>

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

        // Set Data
        const data = google.visualization.arrayToDataTable([
        ['Cost', 'Mhl'],
        ['Food & Beverage', <?php echo $totalFBCost ?>],
        ['Other', <?php echo $totalOtherCost ?>],
        ]);

        // Set Options
        const options = {
        title:'Market Cost Distribution',
        is3D:true,
        titleTextStyle: {
            fontSize: 15, // Set the font size (example: 24px)
            bold: true,   // Optional: make the title bold
            color: '#004C99' // Optional: set the title color
        }
        };

        // Fetch data from PHP
        const chartData = <?php echo $chartDataJson; ?>;
        // Add headers to the data
        chartData.unshift(['Market', 'Visits']);
        // Set Data
        const dataVisited = google.visualization.arrayToDataTable(chartData);

        const optionVisited = {
            title: 'Most Visited Markets',
            is3D: true,
            pieHole: 0.4,
            titleTextStyle: {
                fontSize: 15, // Set the font size (example: 24px)
                bold: true,   // Optional: make the title bold
                color: '#004C99' // Optional: set the title color
            }
        };

        // Draw
        const chart = new google.visualization.PieChart(document.getElementById('costDistributionChart'));
        chart.draw(data, options);

        const chartVisited = new google.visualization.PieChart(document.getElementById('marketVisitedChart'));
        chartVisited.draw(dataVisited, optionVisited);
        }
    </script>

    <script src="script.js"></script>
</body>
</html>
