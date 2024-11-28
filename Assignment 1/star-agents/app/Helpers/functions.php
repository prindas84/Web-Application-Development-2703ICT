<?php

// Process the username by removing any odd numbers (1, 3, 5, 7, 9)
function process_username($reviewerName) {
    $alteredName = "";
    for ($i = 0; $i < strlen($reviewerName); $i++) {
        $char = $reviewerName[$i];
        if (!in_array($char, ['1', '3', '5', '7', '9'])) {
            $alteredName .= $char;
        }
    }
    return $alteredName;
}

// Check the review by counting the reviews for today by username and IP address
function check_review($reviewerName, $ipAddress) {
    $currentDate = date('d-m-Y');
    
    $nameCount = DB::select("
        SELECT COUNT(*) AS review_count 
        FROM Rating 
        WHERE reviewer_name = ? 
        AND date = ?
    ", [$reviewerName, $currentDate]);

    $ipCount = DB::select("
        SELECT COUNT(*) AS review_count 
        FROM Rating 
        WHERE ip_address = ? 
        AND date = ?
    ", [$ipAddress, $currentDate]);

    // If there are 2 or more reviews from the same user or IP today (current review is the third), flag all matching reviews and add the details to the flagged tables
    if ($nameCount[0]->review_count >= 2 || $ipCount[0]->review_count >= 2) {
        DB::insert("
            INSERT INTO Flagged_Username (username, date) 
            VALUES (?, ?)
        ", [$reviewerName, $currentDate]);

        DB::insert("
            INSERT INTO Flagged_IP (ip_address, date) 
            VALUES (?, ?)
        ", [$ipAddress, $currentDate]);

        updateFlaggedReviews($reviewerName, $ipAddress);

        return true;
    }

    // Check if the username or IP is flagged in the database
    return check_and_clean_flags($reviewerName, $ipAddress);
}

// Check and clean flagged records older than 1 month, then check for current flags
function check_and_clean_flags($reviewerName, $ipAddress) {
    $oneMonthPrior = date('d-m-Y', strtotime('-1 month'));

    DB::delete("
        DELETE FROM Flagged_Username
        WHERE date < ?
    ", [$oneMonthPrior]);

    DB::delete("
        DELETE FROM Flagged_IP
        WHERE date < ?
    ", [$oneMonthPrior]);

    $flaggedUsername = DB::select("
        SELECT * FROM Flagged_Username 
        WHERE username = ?
    ", [$reviewerName]);

    $flaggedIP = DB::select("
        SELECT * FROM Flagged_IP 
        WHERE ip_address = ?
    ", [$ipAddress]); 

    if (!empty($flaggedUsername) || !empty($flaggedIP)) {
        updateFlaggedReviews($reviewerName, $ipAddress);
    }

    // Return true if either the username or IP address is flagged
    return !empty($flaggedUsername) || !empty($flaggedIP);
}

// Update the flagged review field to 1 for any reviews in the last month by username or IP address
function updateFlaggedReviews($reviewerName, $ipAddress) {
    $oneMonthPrior = date('d-m-Y', strtotime('-1 month'));

    DB::update("
        UPDATE Rating
        SET flagged_review = 1
        WHERE (reviewer_name = ? OR ip_address = ?)
        AND date >= ?
    ", [$reviewerName, $ipAddress, $oneMonthPrior]);
}

?>
