<?php
// No session_start() here — already started in payment.php

function checkAndUnlockLoyalty($conn, int $user_id): bool
{
    // Lock the user row
    $stmt = mysqli_prepare($conn,
        "SELECT total_spent, loylaty_crredit FROM users WHERE users_id = ? FOR UPDATE"
    );
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_spent, $loyalty_credit);
    $fetch = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$fetch) return false;

    // Unlock loyalty if threshold reached
    if ($total_spent >= 100 && (int)$loyalty_credit === 0) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET loylaty_crredit = 1 WHERE users_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['loyalty_unlocked'] = true;
        return true;
    }

    return false;
}
?>