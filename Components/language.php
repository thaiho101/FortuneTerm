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
    $newTransaction = "New Transaction";
    $dateTranslate = "Date";
    $marketTranslate = "Market";
    $foodAndBeverageCostTranslate = "Food & Beverage Cost";
    $otherCostTranslate = "Other Cost";
    $insertTranslate = "Insert";
    $haveSetButtonNotificationTranslate = "You have set the budget for this month is: ";
    $haveNotSetBudgetTranslate = "You have not set the budget for this month! ";
    $setBudgetTranslate = "Set Budget";
    $setYourBudgetTranslate = "Set Your Budget";
    $enterYourBudgetTranslate = "Enter Your Budget";
    $applyTranslate = "Apply";
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
    $shoppingPowerTranslate = "Shopping Power";
    $mostVisitedMarkets = "Most Visited Markets";
    $marketCostDistribution = "Market Cost Distribution";
    $foodAndBeverageTranslate = "Food & Beverage";
    $otherTranslate = "Other";
    $cancelTranslate = "Cancel";
} else if ($_SESSION['languageType'] == 'Vietnamese') {
    $newTransaction = "Giao Dịch Mới";
    $dateTranslate = "Ngày";
    $marketTranslate = "Chợ";
    $foodAndBeverageCostTranslate = "Chi phí Đồ ăn & Thức Uống";
    $otherCostTranslate = "Chi phí khác";
    $insertTranslate = "Thêm";
    $haveSetButtonNotificationTranslate = "Ngân sách bạn đã thiết lập cho tháng này là: ";
    $haveNotSetBudgetTranslate = "Bạn chưa thiết lập ngân sách cho tháng này! ";
    $setBudgetTranslate = "Đặt ngân sách";
    $setYourBudgetTranslate = "Đặt ngân sách của bạn";
    $enterYourBudgetTranslate = "Nhập ngân sách của bạn";
    $applyTranslate = "Áp dụng";
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
    $shoppingPowerTranslate = "Sức Mua Sắm";
    $mostVisitedMarkets = "Các Chợ Được Ghé Thăm Nhiều Nhất";
    $marketCostDistribution = "Phân Bổ Chi Phí";
    $foodAndBeverageTranslate = "Đồ ăn & Thức uống";
    $otherTranslate = "Khác";
    $cancelTranslate = "Hủy";
} else if ($_SESSION['languageType'] == 'Spanish') {
    $newTransaction = "Nueva Transacción";
    $dateTranslate = "Fecha";
    $marketTranslate = "Mercado";
    $foodAndBeverageCostTranslate = "Costo de Comida y Bebidas";
    $otherCostTranslate = "Otros Costos";
    $insertTranslate = "Insertar";
    $haveSetButtonNotificationTranslate = "El presupuesto establecido para este mes es: ";
    $haveNotSetBudgetTranslate = "¡No ha establecido el presupuesto para este mes!";
    $setBudgetTranslate = "Establecer Presupuesto";
    $setYourBudgetTranslate = "Establezca su Presupuesto";
    $enterYourBudgetTranslate = "Ingrese su Presupuesto";
    $applyTranslate = "Aplicar";
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
    $shoppingPowerTranslate = "Poder de Compra";
    $mostVisitedMarkets = "Mercados Más Visitados";
    $marketCostDistribution = "Distribución de Costos de Mercado";
    $foodAndBeverageTranslate = "Comida y Bebidas";
    $otherTranslate = "Otros";
    $cancelTranslate = "Cancelar";
} else if ($_SESSION['languageType'] == 'German') {
    $newTransaction = "Neue Transaktion";
    $dateTranslate = "Datum";
    $marketTranslate = "Markt";
    $foodAndBeverageCostTranslate = "Kosten für Essen und Trinken";
    $otherCostTranslate = "Andere Kosten";
    $insertTranslate = "Einfügen";
    $haveSetButtonNotificationTranslate = "Das für diesen Monat festgelegte Budget beträgt: ";
    $haveNotSetBudgetTranslate = "Sie haben das Budget für diesen Monat nicht festgelegt!";
    $setBudgetTranslate = "Budget festlegen";
    $setYourBudgetTranslate = "Ihr Budget festlegen";
    $enterYourBudgetTranslate = "Geben Sie Ihr Budget ein";
    $applyTranslate = "Anwenden";
    $showBudgetTranslate = "Budget anzeigen";
    $yourBudgetSummaryTranslate = "Ihre Budgetübersicht";
    $yearTranslate = "Jahr";
    $monthTranslate = "Monat";
    $budgetTranslate = "Budget";
    $totalBudgetTranslate = "Gesamtbudget";
    $closeTranslate = "Schließen";
    $dayOfWeekTranslate = "Wochentag";
    $totalCostTranslate = "Gesamtkosten";
    $balanceForShoppingTranslate = "Verfügbares Guthaben";
    $shoppingPowerTranslate = "Kaufkraft";
    $mostVisitedMarkets = "Meistbesuchte Märkte";
    $marketCostDistribution = "Marktkostenverteilung";
    $foodAndBeverageTranslate = "Essen & Trinken";
    $otherTranslate = "Andere";
    $cancelTranslate = "Abbrechen";
} else if ($_SESSION['languageType'] == 'French') {
    $newTransaction = "Nouvelle Transaction";
    $dateTranslate = "Date";
    $marketTranslate = "Marché";
    $foodAndBeverageCostTranslate = "Coût Nourriture et Boissons";
    $otherCostTranslate = "Autres Coûts";
    $insertTranslate = "Insérer";
    $haveSetButtonNotificationTranslate = "Le budget établi pour ce mois est : ";
    $haveNotSetBudgetTranslate = "Vous n'avez pas défini de budget pour ce mois !";
    $setBudgetTranslate = "Définir le Budget";
    $setYourBudgetTranslate = "Définissez votre Budget";
    $enterYourBudgetTranslate = "Entrez votre Budget";
    $applyTranslate = "Appliquer";
    $showBudgetTranslate = "Afficher le Budget";
    $yourBudgetSummaryTranslate = "Résumé de votre Budget";
    $yearTranslate = "Année";
    $monthTranslate = "Mois";
    $budgetTranslate = "Budget";
    $totalBudgetTranslate = "Budget Total";
    $closeTranslate = "Fermer";
    $dayOfWeekTranslate = "Jour de la Semaine";
    $totalCostTranslate = "Coût Total";
    $balanceForShoppingTranslate = "Solde pour les Achats";
    $shoppingPowerTranslate = "Pouvoir d'Achat";
    $mostVisitedMarkets = "Marchés les Plus Visités";
    $marketCostDistribution = "Répartition des Coûts du Marché";
    $foodAndBeverageTranslate = "Nourriture et Boissons";
    $otherTranslate = "Autres";
    $cancelTranslate = "Annuler";
} else if ($_SESSION['languageType'] == 'Korean') {
    $newTransaction = "새 거래";
    $dateTranslate = "날짜";
    $marketTranslate = "시장";
    $foodAndBeverageCostTranslate = "음식 및 음료 비용";
    $otherCostTranslate = "기타 비용";
    $insertTranslate = "삽입";
    $haveSetButtonNotificationTranslate = "이번 달에 설정된 예산은 다음과 같습니다: ";
    $haveNotSetBudgetTranslate = "이번 달 예산을 설정하지 않았습니다!";
    $setBudgetTranslate = "예산 설정";
    $setYourBudgetTranslate = "예산 설정";
    $enterYourBudgetTranslate = "예산을 입력하세요";
    $applyTranslate = "적용";
    $showBudgetTranslate = "예산 표시";
    $yourBudgetSummaryTranslate = "예산 요약";
    $yearTranslate = "년";
    $monthTranslate = "월";
    $budgetTranslate = "예산";
    $totalBudgetTranslate = "총 예산";
    $closeTranslate = "닫기";
    $dayOfWeekTranslate = "요일";
    $totalCostTranslate = "총 비용";
    $balanceForShoppingTranslate = "쇼핑 잔액";
    $shoppingPowerTranslate = "쇼핑력";
    $mostVisitedMarkets = "가장 많이 방문한 시장";
    $marketCostDistribution = "시장 비용 분포";
    $foodAndBeverageTranslate = "음식 및 음료";
    $otherTranslate = "기타";
    $cancelTranslate = "취소";
} else if ($_SESSION['languageType'] == 'Chinese') {
    $newTransaction = "新交易";
    $dateTranslate = "日期";
    $marketTranslate = "市场";
    $foodAndBeverageCostTranslate = "餐饮费用";
    $otherCostTranslate = "其他费用";
    $insertTranslate = "插入";
    $haveSetButtonNotificationTranslate = "您本月设定的预算是：";
    $haveNotSetBudgetTranslate = "您尚未设定本月的预算！";
    $setBudgetTranslate = "设定预算";
    $setYourBudgetTranslate = "设定您的预算";
    $enterYourBudgetTranslate = "输入您的预算";
    $applyTranslate = "应用";
    $showBudgetTranslate = "显示预算";
    $yourBudgetSummaryTranslate = "您的预算摘要";
    $yearTranslate = "年";
    $monthTranslate = "月";
    $budgetTranslate = "预算";
    $totalBudgetTranslate = "总预算";
    $closeTranslate = "关闭";
    $dayOfWeekTranslate = "星期几";
    $totalCostTranslate = "总费用";
    $balanceForShoppingTranslate = "可用购物余额";
    $shoppingPowerTranslate = "购物力";
    $mostVisitedMarkets = "访问最多的市场";
    $marketCostDistribution = "市场成本分布";
    $foodAndBeverageTranslate = "餐饮";
    $otherTranslate = "其他";
    $cancelTranslate = "取消";
} else if ($_SESSION['languageType'] == 'Japanese') {
    $newTransaction = "新しい取引";
    $dateTranslate = "日付";
    $marketTranslate = "市場";
    $foodAndBeverageCostTranslate = "食べ物と飲み物の費用";
    $otherCostTranslate = "その他の費用";
    $insertTranslate = "挿入";
    $haveSetButtonNotificationTranslate = "今月の設定された予算は次のとおりです: ";
    $haveNotSetBudgetTranslate = "今月の予算が設定されていません!";
    $setBudgetTranslate = "予算を設定する";
    $setYourBudgetTranslate = "予算を設定する";
    $enterYourBudgetTranslate = "予算を入力する";
    $applyTranslate = "適用する";
    $showBudgetTranslate = "予算を表示する";
    $yourBudgetSummaryTranslate = "予算概要";
    $yearTranslate = "年";
    $monthTranslate = "月";
    $budgetTranslate = "予算";
    $totalBudgetTranslate = "総予算";
    $closeTranslate = "閉じる";
    $dayOfWeekTranslate = "曜日";
    $totalCostTranslate = "合計費用";
    $balanceForShoppingTranslate = "買い物の残高";
    $shoppingPowerTranslate = "購買力";
    $mostVisitedMarkets = "最も訪問された市場";
    $marketCostDistribution = "市場コスト分布";
    $foodAndBeverageTranslate = "食べ物と飲み物";
    $otherTranslate = "その他";
    $cancelTranslate = "キャンセル";
} else {
    // Default to English if no language matches
    $newTransaction = "New Transaction";
    $dateTranslate = "Date";
    $marketTranslate = "Market";
    $foodAndBeverageCostTranslate = "Food & Beverage Cost";
    $otherCostTranslate = "Other Cost";
    $insertTranslate = "Insert";
    $haveSetButtonNotificationTranslate = "You have set the budget for this month is: ";
    $haveNotSetBudgetTranslate = "You have not set the budget for this month! ";
    $setBudgetTranslate = "Set Budget";
    $setYourBudgetTranslate = "Set Your Budget";
    $enterYourBudgetTranslate = "Enter Your Budget";
    $applyTranslate = "Apply";
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
    $shoppingPowerTranslate = "Shopping Power";
    $mostVisitedMarkets = "Most Visited Markets";
    $marketCostDistribution = "Market Cost Distribution";
    $foodAndBeverageTranslate = "Food & Beverage";
    $otherTranslate = "Other";
    $cancelTranslate = "Cancel";
}


////[Translation]/////////-->Bottom

?>