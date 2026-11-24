<?php

function updateLateFees($conn, $userID) {

    $stmt = $conn->prepare(
        "UPDATE checkouts
         SET late_fee = LEAST(
             25, TIMESTAMPDIFF(DAY, due_date, NOW())
         )
         WHERE user_id = ?
         AND status = 'checked_out'
         AND return_date IS NULL
         AND due_date < NOW()"
    );

    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
}