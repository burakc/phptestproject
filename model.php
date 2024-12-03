<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    $statement = $conn->query('SELECT * FROM `users`');
    $users = array();
    while ($row = $statement->fetch()) {
        $users[$row['id']] = $row['name'];
    }

    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{     
    $query = "
        SELECT 
            strftime('%Y-%m', trdate) AS month,
            SUM(CASE WHEN account_to IN (SELECT id FROM user_accounts WHERE user_id = ?) THEN amount ELSE 0 END) -
            SUM(CASE WHEN account_from IN (SELECT id FROM user_accounts WHERE user_id = ?) THEN amount ELSE 0 END) AS balance
        FROM transactions
        WHERE account_to IN (SELECT id FROM user_accounts WHERE user_id = ?)
           OR account_from IN (SELECT id FROM user_accounts WHERE user_id = ?)
        GROUP BY strftime('%Y-%m', trdate)
        ORDER BY month;
    ";

    $statement = $conn->prepare($query);
    $statement->execute([$user_id, $user_id, $user_id, $user_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>
