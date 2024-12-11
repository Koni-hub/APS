<?php
if (!isset($_GET['user_id'])) {
    echo "Error: User ID not provided";
    exit();
}

$user_id = intval($_GET['user_id']);

include('./db_connect.php');

$sql = "SELECT tenants.*, houses.house_no, houses.description AS house_description, houses.price AS monthly_rate 
        FROM tenants 
        INNER JOIN houses ON tenants.house_id = houses.id 
        WHERE tenants.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Error: Tenant not found";
    exit();
}

$tenantInfo = $result->fetch_assoc();

$total_paid = 0;
$outstanding_balance = 0;
$last_payment_info = "N/A";
$overpaid_amount = 0;
$next_month_payable = $tenantInfo['monthly_rate'];

$sql_balance = "SELECT COALESCE(SUM(amount), 0) AS total_paid FROM payments WHERE tenant_id = ? ORDER BY date_created DESC LIMIT 1";
$stmt_balance = $conn->prepare($sql_balance);
$stmt_balance->bind_param("i", $user_id);
$stmt_balance->execute();
$result_balance = $stmt_balance->get_result();
$total_paid = $result_balance->fetch_assoc()['total_paid'];

$sql_last_payment = "SELECT (amount), date_created FROM payments WHERE tenant_id = ? ORDER BY date_created DESC LIMIT 1";
$stmt_last_payment = $conn->prepare($sql_last_payment);
$stmt_last_payment->bind_param("i", $user_id);
$stmt_last_payment->execute();
$result_last_payment = $stmt_last_payment->get_result();

if ($result_last_payment->num_rows === 1) {
    $row_last_payment = $result_last_payment->fetch_assoc();
    $last_payment_info = $row_last_payment['amount'] . " on " . date("M d, Y", strtotime($row_last_payment['date_created']));
}

$date_in = $tenantInfo['date_in'];
$price = $tenantInfo['monthly_rate'];
$current_date = date('Y-m-d');

$dateInObj = new DateTime($date_in);
$currentDateObj = new DateTime($current_date);

$interval = $dateInObj->diff($currentDateObj);

$months = ($interval->y * 12) + $interval->m;

if ($currentDateObj->format('d') < $dateInObj->format('d')) {
    $months--;
}

if ($months == 0) {
    $months = 1;
}

$payable = $price * $months;

$outstanding_balance = $payable - $total_paid;

if ($outstanding_balance < 0) {
    // Tenant has underpaid
    $next_month_payable = $price + $outstanding_balance;
} else {
    // Tenant has overpaid or is up to date
    $overpaid_amount = $total_paid - $payable;
    if ($overpaid_amount > 0) {
        $next_month_payable = max(0, $price - $overpaid_amount);
    } else {
        $next_month_payable = $price;
    }
}

if ($next_month_payable < 0) {
    $next_month_payable = 0;
}

$last_payment_date = ($last_payment_info == "N/A") ? $current_date : date("Y-m-d", strtotime($row_last_payment['date_created']));
$next_payment_month = date("M Y", strtotime($last_payment_date . " +1 month"));
$covered_period = date("M Y", strtotime($last_payment_date)) . " to " . $next_payment_month;

$get_count_payment_date = "SELECT COUNT(date_created) AS countDate FROM payments WHERE tenant_id = ?";
$stmt_count = $conn->prepare($get_count_payment_date);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_months = $result_count->num_rows > 0 ? $result_count->fetch_assoc()['countDate'] : 'N/A';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <div class="container mx-auto mt-6 p-6 text-center">
            <a href="rentee.php" class="float-right inline-block px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600">Back to Login</a>
        </div>
        <div class="flex items-center mb-6">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWviOnqIDxRswOj4pkCAagxDWFdG7k3lp1jeV-4WWcJvOQTfcpaRxSLYQiF98NA-iGTd8&usqp=CAU" alt="Profile Picture" class="w-24 h-24 rounded-full mr-4">
            <h1 class="text-3xl font-bold text-gray-800">Welcome <?php echo htmlspecialchars($tenantInfo['firstname'] . " " . $tenantInfo['lastname']); ?></h1>
        </div>
        <div class="flex flex-wrap -mx-4">
            <div class="w-full md:w-2/3 px-4 mb-6">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Tenant Details</h2>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Name</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($tenantInfo['firstname'] . " " . $tenantInfo['middlename'] . " " . $tenantInfo['lastname']); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Email</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($tenantInfo['email']); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Contact</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($tenantInfo['contact']); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">House No</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($tenantInfo['house_no']); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Description</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($tenantInfo['house_description']); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Monthly Rate</th>
                                    <td class="text-gray-800"><?php echo number_format($tenantInfo['monthly_rate'], 2); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Last Payment</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($last_payment_info); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Next Month Payable</th>
                                    <td class="text-gray-800">
                                        <?php
                                        $new_next_month_payable = $total_paid + $price;
                                        echo number_format($new_next_month_payable, 2);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/3 px-4">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Additional Information</h2>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Total Paid</th>
                                    <td class="text-gray-800"><?php echo number_format($total_paid, 2); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Covered Period</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($covered_period); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left font-medium text-gray-600">Total Months</th>
                                    <td class="text-gray-800"><?php echo htmlspecialchars($total_months); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>