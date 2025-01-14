<?php
// require_once("../config.php"); 
$userId = $_SESSION['user_id']; 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}
////[Currency Type]/////////-->Header
//Update currency selected into users database
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['languageType']))
{
    $languageType = $_POST['languageType'];
    $_SESSION['languageType'] = $languageType; // Save in session for persistence

    $sql = "UPDATE users SET preferred_language = ? 
            WHERE user_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $languageType, $userId);
    $stmt->execute();
    $stmt->close();
}

//Invoke the preferred language from users database
$sql = "SELECT preferred_language FROM users
        WHERE user_id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc())
{
    $_SESSION['languageType'] = $row['preferred_language'];
} else {
    $_SESSION['languageType'] = 'English'; //Default language
}
$stmt->close();

////[Translation]/////////-->Header
if ($_SESSION['languageType'] == 'English') {
    // $greeting = "Welcome, ";         Set up in the navBar.php page
    $newTransaction = "New Transaction";
    $dateTranslate = "Date";
    $marketTranslate = "Market";
    $foodAndBeverageCostTranslate = "Food & Beverage Cost";
    $otherCostTranslate = "Other Cost";
    $insertTranslate = "Insert";
    $haveSetButtonNotificationTranslate = "You have set the budget for this month is: ";
    $haveNotSetBudgetTranslate = "You have not set the budget for this month! ";
    $setBudgetTranslate = "Set Budget";
    $showBudgetTranslate = "Show Budget";
    $yourBudgetSummaryTranslate = "Your Budget Summary";
    $yearTranslate = "Year";
    $monthTranslate = "Month";
    $budgetTranslate = "Budget";
    $totalBudgetTranslate = "Total Budget";
    $closeTranslate = "Close";
    $dayOfWeekTranslate = "Day of Week";
    $totalCostTranslate = "Total Cost";
    $balanceForShoppingTranslate = "Balance for Shopping";
    $mostVisitedMarkets = "Most Visited Markets";
    $marketCostDistribution = "Market Cost Distribution";
    $foodAndBeverageTranslate = "Food & Beverage";
    $otherTranslate = "Other";
    $cancelTranslate = "Cancel";
    // $settingTranslate = "Setting";      Set up in the Setting page
} else if ($_SESSION['languageType'] == 'Vietnamese') {
    // $greeting = "Xin chào, ";            Set up in the navBar.php page
    $newTransaction = "Giao Dịch Mới";
    $dateTranslate = "Ngày";
    $marketTranslate = "Chợ";
    $foodAndBeverageCostTranslate = "Chi phí Đồ ăn & Thức Uống";
    $otherCostTranslate = "Chi phí khác";
    $insertTranslate = "Thêm";
    $haveSetButtonNotificationTranslate = "Ngân sách bạn đã thiết lập cho tháng này là: ";
    $haveNotSetBudgetTranslate = "Bạn chưa thiết lập ngân sách cho tháng này! ";
    $setBudgetTranslate = "Đặt ngân sách";
    $showBudgetTranslate = "Hiển thị ngân sách";
    $yourBudgetSummaryTranslate = "Tổng quan ngân sách của bạn";
    $yearTranslate = "Năm";
    $monthTranslate = "Tháng";
    $budgetTranslate = "Ngân sách";
    $totalBudgetTranslate = "Tổng Ngân sách";
    $closeTranslate = "Đóng";
    $dayOfWeekTranslate = "Thứ";
    $totalCostTranslate = "Tổng Chi Phí";
    $balanceForShoppingTranslate = "Số Tiền Mua Sắm Còn Lại";
    $mostVisitedMarkets = "Các Chợ Được Ghé Thăm Nhiều Nhất";
    $marketCostDistribution = "Phân Bổ Chi Phí";
    $foodAndBeverageTranslate = "Đồ ăn & Thức uống";
    $otherTranslate = "Khác";
    // $settingTranslate = "Cài Đặt";           Set up in the Setting page
} else if ($_SESSION['languageType'] == 'Spanish') {
    // $greeting = "Bienvenido, ";         Set up in the navBar.php page
    $newTransaction = "Nueva Transacción";
    $dateTranslate = "Fecha";
    $marketTranslate = "Mercado";
    $foodAndBeverageCostTranslate = "Costo de Comida y Bebidas";
    $otherCostTranslate = "Otros Costos";
    $insertTranslate = "Insertar";
    $haveSetButtonNotificationTranslate = "El presupuesto establecido para este mes es: ";
    $haveNotSetBudgetTranslate = "¡No ha establecido el presupuesto para este mes!";
    $setBudgetTranslate = "Establecer Presupuesto";
    $showBudgetTranslate = "Mostrar Presupuesto";
    $yourBudgetSummaryTranslate = "Resumen de su Presupuesto";
    $yearTranslate = "Año";
    $monthTranslate = "Mes";
    $budgetTranslate = "Presupuesto";
    $totalBudgetTranslate = "Presupuesto Total";
    $closeTranslate = "Cerrar";
    $dayOfWeekTranslate = "Día de la Semana";
    $totalCostTranslate = "Costo Total";
    $balanceForShoppingTranslate = "Saldo para Compras";
    $mostVisitedMarkets = "Mercados Más Visitados";
    $marketCostDistribution = "Distribución de Costos de Mercado";
    $foodAndBeverageTranslate = "Comida y Bebidas";
    $otherTranslate = "Otros";
    $cancelTranslate = "Cancelar";
    // $settingTranslate = "Configuración";      Set up in the Setting page
}
////[Translation]/////////-->Bottom

?>