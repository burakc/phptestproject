<?php
include_once('db.php');
include_once('model.php');

// Initialize database connection
$conn = get_connect();

$user_id = isset($_GET['user'])
    ? (int)$_GET['user']
    : null;

if ($user_id) {
    // Get transactions balances
    $transactions = get_user_transactions_balances($user_id, $conn);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($transactions);
}
?>
